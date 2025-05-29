<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Layanan;
use App\Models\Poli;
use App\Models\PasienBPJS;
use App\Models\Jadwaldokter;
use App\Models\Antrian;
use App\Models\Dokter;
use Carbon\Carbon;
use App\Models\PasienUmum;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Add this line
use App\Mail\AntrianStrukMail; // Add this line

class AntrianController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function getDoctors(Request $request)
    {
        $request->validate([
            'poli_id' => 'required|exists:poli,id',
            'hari' => 'required|string',
            'tanggal' => 'required|date'
        ]);

        $poliId = $request->poli_id;
        $hari = $request->hari;
        $tanggal = $request->tanggal;

        $today = Carbon::today()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        if ($tanggal !== $today && $tanggal !== $tomorrow) {
            return response()->json([]);
        }

        $query = Jadwaldokter::with('dokter')
            ->where('id_poli', $poliId)
            ->where('hari', $hari)
            ->where('kuotasisa', '>', 0);

        if ($tanggal === $today) {
            $now = Carbon::now()->format('H:i:s');
            $query->where('jam_mulai', '<=', $now)
                ->where('jam_selesai', '>=', $now);
        }

        $schedules = $query->get();

        return response()->json($schedules);
    }

    public function monitor()
    {
        return view('user.monitor');
    }
    public function downloadStruk()
    {
        if (!session('antrian_id')) {
            return redirect()->route('pesan');
        }

        $antrian = Antrian::with(['poli', 'layanan', 'pasienUmum', 'pasienBPJS', 'jadwaldokter.dokter'])
            ->find(session('antrian_id'));

        if (!$antrian) {
            return redirect()->route('pesan');
        }

        $pdf = PDF::loadView('user.struk-pdf', compact('antrian'));
        return $pdf->download('struk-antrian-' . $antrian->no_antrian . '.pdf');
    }

    public function cetakStruk()
    {
        if (!session('antrian_id')) {
            return redirect()->route('pesan');
        }

        $antrian = Antrian::with(['poli', 'layanan', 'pasienUmum', 'pasienBPJS', 'jadwaldokter.dokter'])
            ->find(session('antrian_id'));

        if (!$antrian) {
            return redirect()->route('pesan');
        }

        $pdf = PDF::loadView('user.struk-pdf', compact('antrian'));
        return $pdf->stream('struk-antrian-' . $antrian->no_antrian . '.pdf');
    }

    public function struk()
    {
        if (!session('antrian_id')) {
            return redirect()->route('pesan');
        }

        $antrian = Antrian::with(['poli', 'layanan', 'pasienUmum', 'pasienBPJS', 'jadwaldokter.dokter'])
            ->find(session('antrian_id'));

        if (!$antrian) {

            return redirect()->route('pesan');
        }

        return view('user.struk', compact('antrian'));
    }

    public function pesan()
    {
        $layanan = Layanan::all();
        $poli = Poli::all();

        $currentDay = strtolower(date('l'));
        $dokter = Dokter::with(['jadwalDokter' => function ($query) use ($currentDay) {
            $query->where('hari', $currentDay);
        }])->whereHas('jadwalDokter', function ($query) use ($currentDay) {
            $query->where('hari', $currentDay);
        })->get();
        $hariIni = now()->format('Y-m-d');
        $besok = now()->addDay()->format('Y-m-d');
        return view('user.pesan', compact('dokter', 'layanan', 'poli', 'hariIni', 'besok'));
    }

    public function simpan(Request $request)
    {
        // Validasi awal tanggal
        $today = Carbon::today()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $selectedDate = $request->tanggal_antrian;

        if (!in_array($selectedDate, [$today, $tomorrow])) {
            Log::error('Tanggal tidak valid: ' . $selectedDate);
            return back()->with('error', 'Hanya bisa mendaftar untuk hari ini atau besok');
        }

        // Validasi input (remain the same)
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => [
                'required',
                'numeric',
                'digits_between:10,15',
                'not_regex:/^(0|\+62|62)/',
            ],
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/@gmail\.com$/', $value)) {
                        $fail('Alamat email harus menggunakan domain @gmail.com');
                    }
                },
            ],
            'id_layanan' => 'required|exists:layanan,id',
            'id_poli' => 'required|exists:poli,id',
            'tanggal_antrian' => 'required|date',
            'id_jadwal' => [
                'required',
                'exists:jadwaldokter,id',
                function ($attribute, $value, $fail) use ($selectedDate) {
                    $schedule = Jadwaldokter::find($value);

                    // Jika tanggal adalah hari ini, cek waktu pelayanan
                    if ($selectedDate == Carbon::today()->format('Y-m-d')) {
                        $currentTime = date('H:i:s');
                        if ($schedule->jam_selesai < $currentTime) {
                            $fail('Jadwal yang dipilih sudah lewat waktu pelayanan.');
                        }
                    }

                    if ($schedule->kuotasisa <= 0) {
                        $fail('Kuota untuk jadwal ini sudah habis.');
                    }
                },
            ],
        ], [
            'no_telepon.not_regex' => ' telepon tidak boleh diNomorawali dengan 0, +62, atau 62 (contoh: 85732978938)',
            'email.email' => 'Format email tidak valid',
        ]);

        // Dapatkan data layanan
        $layanan = Layanan::findOrFail($request->id_layanan);

        try {
            DB::beginTransaction();

            // Handle pasien BPJS (remain the same)
            if ($layanan->nama_layanan == 'BPJS') {
                $request->validate(['nomor_bpjs' => 'required|numeric|digits:13']);

                $pasienBPJS = PasienBPJS::where('nomor_bpjs', $request->nomor_bpjs)->first();
                if (!$pasienBPJS) {
                    throw new \Exception('Nomor BPJS tidak terdaftar.');
                }

                // Cek antrian aktif
                $existingAntrian = Antrian::with('poli')
                    ->whereDate('tanggal_antrian', $selectedDate)
                    ->where('id_pasienbpjs', $pasienBPJS->id)
                    ->whereIn('status_antrian', ['menunggu', 'dipanggil'])
                    ->first();

                if ($existingAntrian) {
                    return back()
                        ->withInput()
                        ->with('warning', 'Anda sudah memiliki antrian aktif. Nomor antrian Anda: ' . $existingAntrian->poli->kode_poli . $existingAntrian->no_antrian);
                }

                $pasien = PasienBPJS::updateOrCreate(
                    ['nomor_bpjs' => $request->nomor_bpjs],
                    [
                        'nama' => $pasienBPJS->nama,
                        'no_telepon' => $request->no_telepon,
                        'email' => $request->email
                    ]
                );
                $pasienId = ['id_pasienbpjs' => $pasien->id];
            }
            // Handle pasien umum (remain the same)
            else {
                $request->validate(['nik' => 'required|numeric|digits:16']);

                $pasienUmum = PasienUmum::where('nik', $request->nik)->first();
                if (!$pasienUmum) {
                    throw new \Exception('Nomor NIK tidak terdaftar.');
                }

                // Cek antrian aktif
                $existingAntrian = Antrian::with('poli')
                    ->whereDate('tanggal_antrian', $selectedDate)
                    ->where('id_pasienumum', $pasienUmum->id)
                    ->whereIn('status_antrian', ['menunggu', 'dipanggil'])
                    ->first();

                if ($existingAntrian) {
                    return back()
                        ->withInput()
                        ->with('warning', 'Anda sudah memiliki antrian aktif. Nomor antrian Anda: ' . $existingAntrian->poli->kode_poli . $existingAntrian->no_antrian);
                }

                $pasien = PasienUmum::updateOrCreate(
                    ['nik' => $request->nik],
                    [
                        'nama' => $request->nama,
                        'no_telepon' => $request->no_telepon,
                        'email' => $request->email
                    ]
                );
                $pasienId = ['id_pasienumum' => $pasien->id];
            }

            // Hitung nomor antrian (remain the same)
            $countAntrianByJadwal = Antrian::whereDate('tanggal_antrian', $selectedDate)
                ->where('id_poli', $request->id_poli)
                ->where('id_jadwal', $request->id_jadwal)
                ->count();

            $noAntrian = str_pad($countAntrianByJadwal + 1, 3, '0', STR_PAD_LEFT);
            $waktuPesan = now();

            // Tentukan durasi pelayanan berdasarkan poli
            $poli = Poli::findOrFail($request->id_poli);
            $kodePoli = $poli->kode_poli;
            $durasiPelayanan = match ($kodePoli) {
                'U' => 15,  // Poli Umum
                'G' => 30,  // Poli Gigi & Mulut
                'K' => 15,  // Poli KIA/KB
                'D' => 20,  // Poli Kandungan
                'V' => 10,  // Poli Vaksinasi
                default => 15,
            };

            // Dapatkan jadwal dokter
            $jadwalDokter = Jadwaldokter::find($request->id_jadwal);

            // Hitung waktu estimasi
            if ($selectedDate == $tomorrow) {
                // Untuk antrian besok
                $antrianTerakhir = Antrian::whereDate('tanggal_antrian', $selectedDate)
                    ->where('id_poli', $request->id_poli)
                    ->where('id_jadwal', $request->id_jadwal)
                    ->orderBy('waktu_estimasi', 'desc')
                    ->first();

                if ($antrianTerakhir) {
                    // Jika sudah ada antrian sebelumnya, tambahkan durasi pelayanan ke estimasi terakhir
                    $waktuEstimasi = Carbon::parse($antrianTerakhir->waktu_estimasi)->addMinutes($durasiPelayanan);
                } else {
                    // Jika antrian pertama, gunakan jam mulai dokter + durasi pelayanan
                    $waktuEstimasi = Carbon::parse($selectedDate . ' ' . $jadwalDokter->jam_mulai)->addMinutes($durasiPelayanan);
                }
            } else {
                // Untuk antrian hari ini
                $antrianTerakhir = Antrian::whereDate('tanggal_antrian', $selectedDate)
                    ->where('id_poli', $request->id_poli)
                    ->where('id_jadwal', $request->id_jadwal)
                    ->orderBy('waktu_estimasi', 'desc')
                    ->first();

                if ($antrianTerakhir) {
                    $waktuEstimasiTerakhir = Carbon::parse($antrianTerakhir->waktu_estimasi);
                    $waktuPesanCarbon = Carbon::now();

                    // Jika waktu pesan lebih dari estimasi terakhir
                    if ($waktuPesanCarbon->greaterThan($waktuEstimasiTerakhir)) {
                        $waktuEstimasi = $waktuPesanCarbon->copy()->addMinutes($durasiPelayanan);
                    } else {
                        // Jika waktu pesan kurang dari estimasi terakhir
                        $waktuEstimasi = $waktuEstimasiTerakhir->copy()->addMinutes($durasiPelayanan);
                    }
                } else {
                    // Jika belum ada antrian hari ini
                    $waktuEstimasi = $waktuPesan->copy()->addMinutes($durasiPelayanan);
                }
            }

            // Buat antrian baru (remain the same)
            $antrian = Antrian::create(array_merge([
                'no_antrian' => $noAntrian,
                'id_layanan' => $request->id_layanan,
                'id_poli' => $request->id_poli,
                'tanggal_antrian' => $selectedDate,
                'waktu_pilih' => $waktuPesan,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'waktu_estimasi' => $waktuEstimasi,
                'status_antrian' => 'menunggu',
                'id_jadwal' => $request->id_jadwal,
            ], $pasienId));

            // Update kuota dokter (remain the same)
            $jadwalDokter->kuotasisa -= 1;
            $jadwalDokter->kuotadiambil += 1;
            $jadwalDokter->save();

            DB::commit();

            session(['antrian_id' => $antrian->id]);
            return redirect()->route('struk');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan antrian: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan antrian: ' . $e->getMessage())->withInput();
        }
    }



    public function cariPasien(Request $request)
    {
        $nik = $request->nik;
        $nomor_bpjs = $request->nomor_bpjs;

        if ($nik) {
            $pasien = PasienUmum::where('nik', $nik)->first();
        } elseif ($nomor_bpjs) {
            $pasien = PasienBPJS::where('nomor_bpjs', $nomor_bpjs)->first();
        } else {
            return response()->json(['status' => 'not_found']);
        }

        if ($pasien) {
            return response()->json([
                'status' => 'found',
                'data' => [
                    'nama' => $pasien->nama,
                    'no_telepon' => $pasien->no_telepon ?? '',
                    'email' => $pasien->email ?? ''
                ]
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }
}
