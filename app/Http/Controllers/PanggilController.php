<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AntrianNotification;
use App\Models\Antrian;
use App\Models\Poli;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AntrianExport;
use PDF;

class PanggilController extends Controller
{
    public function index(Request $request)
    {
        $activeCallExists = DB::table('antrian')
            ->whereDate('tanggal_antrian', Carbon::today())
            ->where('status_antrian', 'dipanggil')
            ->exists();

        $query = DB::table('antrian')
            ->leftJoin('pasien_umum', 'antrian.id_pasienumum', '=', 'pasien_umum.id')
            ->leftJoin('pasien_bpjs', 'antrian.id_pasienbpjs', '=', 'pasien_bpjs.id')
            ->join('poli', 'antrian.id_poli', '=', 'poli.id')
            ->join('layanan', 'antrian.id_layanan', '=', 'layanan.id')
            ->leftJoin('jadwaldokter', 'antrian.id_jadwal', '=', 'jadwaldokter.id')
            ->leftJoin('dokter', 'jadwaldokter.id_dokter', '=', 'dokter.id')
            ->select(
                'antrian.no_antrian',
                DB::raw('COALESCE(pasien_umum.nama, pasien_bpjs.nama) AS nama'),
                'poli.nama_poli',
                'poli.kode_poli',
                'layanan.nama_layanan',
                'antrian.tanggal_antrian',
                'antrian.waktu_estimasi',
                'antrian.panggilan_count',
                'antrian.status_antrian',
                'antrian.email',
                'antrian.no_telepon',
                'antrian.id',
                'dokter.nama_dokter'
            )
            ->whereDate('antrian.tanggal_antrian', Carbon::today())
            ->orderByRaw("CASE
                WHEN antrian.status_antrian = 'dipanggil' THEN 1
                WHEN antrian.status_antrian = 'menunggu' THEN 2
                WHEN antrian.status_antrian = 'pending' THEN 3
                ELSE 4
                END")
            ->orderBy('antrian.waktu_pilih', 'asc');

        if ($request->has('poli') && $request->poli != '') {
            $query->where('poli.nama_poli', $request->poli);
        }

        $antrians = $query->paginate(3);

        return view('admin.panggil', compact('antrians', 'activeCallExists'));
    }

    public function panggil($id)
    {
        $antrian = Antrian::with(['jadwaldokter', 'poli', 'pasienUmum', 'pasienBPJS'])->find($id);

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Data antrian tidak ditemukan'], 404);
        }

        if ($antrian->status_antrian !== 'menunggu') {
            return response()->json(['success' => false, 'message' => 'Antrian sudah dipanggil atau tidak valid'], 400);
        }

        $antrian->status_antrian = 'dipanggil';
        $antrian->panggilan_count = ($antrian->panggilan_count ?? 0) + 1;
        $antrian->last_panggilan = now();
        $antrian->save();

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil dipanggil',
            'no_antrian' => $antrian->poli->kode_poli . $antrian->no_antrian,
            'nama_dokter' => $antrian->jadwaldokter->dokter->nama_dokter ?? 'Dokter',
            'nama_pasien' => $antrian->pasien_umum->nama ?? $antrian->pasien_bpjs->nama ?? 'Pasien'
        ]);
    }

    public function ulangiPanggil(Request $request, $id)
    {
        $antrian = Antrian::with(['jadwaldokter', 'poli', 'pasienUmum', 'pasienBPJS'])->findOrFail($id);

        if ($antrian->status_antrian === 'tidak hadir') {
            return response()->json([
                'success' => false,
                'message' => 'Antrian sudah ditandai sebagai tidak hadir',
                'status' => $antrian->status_antrian,
                'count' => $antrian->panggilan_count,
                'antrian' => [
                    'no_antrian' => $antrian->kode_poli . $antrian->no_antrian,
                    'nama_pasien' => $antrian->pasienUmum->nama ?? $antrian->pasienBPJS->nama ?? 'Pasien',
                    'nama_poli' => $antrian->poli->nama_poli,
                    'nama_dokter' => $antrian->jadwaldokter->dokter->nama_dokter ?? 'Dokter'
                ]
            ]);
        }

        if ($antrian->panggilan_count >= 3) {
            $antrian->status_antrian = 'pending';
            $antrian->last_panggilan = now();
            $antrian->save();

            return response()->json([
                'success' => false,
                'message' => 'Panggilan sudah mencapai batas maksimal (3x)',
                'status' => 'pending',
                'count' => $antrian->panggilan_count,
                'antrian' => [
                    'no_antrian' => $antrian->kode_poli . $antrian->no_antrian,
                    'nama_pasien' => $antrian->pasien_umum->nama ?? $antrian->pasien_bpjs->nama ?? 'Pasien',
                    'nama_poli' => $antrian->poli->nama_poli,
                    'nama_dokter' => $antrian->jadwaldokter->dokter->nama_dokter ?? 'Dokter'
                ]
            ]);
        }

        $antrian->panggilan_count += 1;
        $antrian->last_panggilan = now();
        $antrian->status_antrian = 'dipanggil';
        $antrian->save();

        return response()->json([
            'success' => true,
            'message' => 'Panggilan diulangi (' . $antrian->panggilan_count . '/3)',
            'count' => $antrian->panggilan_count,
            'no_antrian' => $antrian->poli->kode_poli . $antrian->no_antrian,
            'nama_pasien' => $antrian->pasien_umum->nama ?? $antrian->pasien_bpjs->nama ?? 'Pasien',
            'nama_dokter' => $antrian->jadwaldokter->dokter->nama_dokter ?? 'Dokter'
        ]);
    }

    public function markAsNotPresent($id)
    {
        try {
            $antrian = Antrian::findOrFail($id);

            if ($antrian->status_antrian !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya antrian dengan status pending yang bisa ditandai tidak hadir'
                ]);
            }

            $antrian->status_antrian = 'tidak hadir';
            $antrian->save();

            return response()->json([
                'success' => true,
                'message' => 'Antrian ditandai sebagai tidak hadir'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function recallPending($id)
    {
        try {
            $antrian = Antrian::with(['poli', 'jadwaldokter.dokter', 'pasienUmum', 'pasienBPJS'])->findOrFail($id);

            if ($antrian->status_antrian !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya antrian dengan status pending yang bisa dipanggil ulang'
                ], 400);
            }

            // Reset panggilan count dan ubah status
            $antrian->update([
                'status_antrian' => 'dipanggil',
                'panggilan_count' => 1, // Reset ke 1 karena ini panggilan baru
                'last_panggilan' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrian pending berhasil dipanggil ulang',
                'no_antrian' => $antrian->poli->kode_poli . $antrian->no_antrian,
                'nama_pasien' => $antrian->pasienUmum->nama ?? $antrian->pasienBPJS->nama ?? 'Pasien',
                'nama_dokter' => $antrian->jadwaldokter->dokter->nama_dokter ?? 'Dokter'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatusSelesai($id)
    {
        try {
            $antrian = Antrian::findOrFail($id);

            $antrian->update([
                'status_antrian' => 'selesai',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status antrian berhasil diupdate menjadi selesai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCurrentCalls()
    {
        $dayMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $currentDay = $dayMap[date('l')];
        $currentTime = date('H:i:s');

        $activeDoctors = Dokter::whereHas('jadwalDokter', function ($query) use ($currentDay, $currentTime) {
            $query->where('hari', $currentDay)
                ->whereTime('jam_mulai', '<=', $currentTime)
                ->whereTime('jam_selesai', '>=', $currentTime);
        })
            ->with(['jadwalDokter' => function ($query) use ($currentDay, $currentTime) {
                $query->where('hari', $currentDay)
                    ->whereTime('jam_mulai', '<=', $currentTime)
                    ->whereTime('jam_selesai', '>=', $currentTime)
                    ->with('poli');
            }])
            ->get();

        $results = [];

        foreach ($activeDoctors as $doctor) {
            foreach ($doctor->jadwalDokter as $schedule) {
                $antrianDipanggil = Antrian::whereDate('tanggal_antrian', today())
                    ->where('id_jadwal', $schedule->id)
                    ->where('status_antrian', 'dipanggil')
                    ->with('pasienumum')
                    ->with('pasienbpjs')
                    ->orderBy('last_panggilan', 'desc')
                    ->first();

                $waitingList = Antrian::whereDate('tanggal_antrian', today())
                    ->where('id_jadwal', $schedule->id)
                    ->where('status_antrian', 'menunggu')
                    ->orderBy('no_antrian')
                    ->with(['poli:id,kode_poli,nama_poli', 'pasienumum', 'pasienbpjs'])
                    ->limit(3)
                    ->get();

                // Format waiting list
                $formattedWaitingList = $waitingList->map(function ($item) {
                    return [
                        'no_antrian' => $item->no_antrian,
                        'nama' => $item->pasienumum->nama ?? $item->pasienbpjs->nama ?? '-',
                        // tambahkan field lain yang diperlukan
                    ];
                });

                $results[] = [
                    'doctor_id' => $doctor->id,
                    'doctor_name' => $doctor->nama_dokter,
                    'poli_kode' => $schedule->poli->kode_poli,
                    'poli_name' => $schedule->poli->nama_poli,
                    'current_call' => $antrianDipanggil ? [
                        'no_antrian' => $antrianDipanggil->no_antrian,
                        'nama' => $antrianDipanggil->pasienumum->nama ?? $antrianDipanggil->pasienbpjs->nama ?? '-'
                    ] : null,
                    'waiting_list' => $formattedWaitingList
                ];
            }
        }

        return response()->json($results);
    }

    public function reportantrian()
    {
        $poli = Poli::all();
        return view('admin.reportantrian', compact('poli'));
    }
    public function showReportTable(Request $request)
    {
        $bulanInput = $request->input('bulan');
        $layananId = $request->input('layanan');

        // Mapping nama bulan ke angka
        $bulanMap = [
            'Januari' => '01',
            'Februari' => '02',
            'Maret' => '03',
            'April' => '04',
            'Mei' => '05',
            'Juni' => '06',
            'Juli' => '07',
            'Agustus' => '08',
            'September' => '09',
            'Oktober' => '10',
            'November' => '11',
            'Desember' => '12',
        ];

        $bulan = $bulanMap[$bulanInput] ?? date('m');

        // Ambil semua data antrian berdasarkan bulan dan poli
        $antrians = DB::table('antrian')
            ->leftJoin('pasien_umum', 'antrian.id_pasienumum', '=', 'pasien_umum.id')
            ->leftJoin('pasien_bpjs', 'antrian.id_pasienbpjs', '=', 'pasien_bpjs.id')
            ->join('poli', 'antrian.id_poli', '=', 'poli.id')
            ->join('layanan', 'antrian.id_layanan', '=', 'layanan.id')
            ->leftJoin('jadwaldokter', 'antrian.id_jadwal', '=', 'jadwaldokter.id')
            ->leftJoin('dokter', 'jadwaldokter.id_dokter', '=', 'dokter.id')
            ->select(
                'antrian.no_antrian',
                DB::raw('COALESCE(pasien_umum.nama, pasien_bpjs.nama) AS nama'),
                'poli.nama_poli',
                'poli.kode_poli',
                'layanan.nama_layanan',
                'antrian.tanggal_antrian',
                'antrian.waktu_pilih',
                'antrian.status_antrian',
                'dokter.nama_dokter'
            )
            ->whereMonth('antrian.tanggal_antrian', $bulan)
            ->when($layananId, function ($query, $layananId) {
                return $query->where('poli.id', $layananId);
            })
            ->orderBy('antrian.tanggal_antrian', 'asc')
            ->get();

        return view('admin.reporttable', compact('antrians'));
    }

    public function exportToPDF(Request $request)
    {
        $query = DB::table('antrian')
            ->leftJoin('pasien_umum', 'antrian.id_pasienumum', '=', 'pasien_umum.id')
            ->leftJoin('pasien_bpjs', 'antrian.id_pasienbpjs', '=', 'pasien_bpjs.id')
            ->join('poli', 'antrian.id_poli', '=', 'poli.id')
            ->join('layanan', 'antrian.id_layanan', '=', 'layanan.id')
            ->leftJoin('jadwaldokter', 'antrian.id_jadwal', '=', 'jadwaldokter.id')
            ->leftJoin('dokter', 'jadwaldokter.id_dokter', '=', 'dokter.id')
            ->select(
                'antrian.no_antrian',
                DB::raw('COALESCE(pasien_umum.nama, pasien_bpjs.nama) AS nama'),
                'poli.nama_poli',
                'poli.kode_poli',
                'layanan.nama_layanan',
                'antrian.tanggal_antrian',
                'antrian.waktu_pilih',
                'antrian.status_antrian',
                'dokter.nama_dokter'
            );

        if ($request->has('bulan') && $request->bulan != '') {
            $monthMap = [
                'Januari' => 1,
                'Februari' => 2,
                'Maret' => 3,
                'April' => 4,
                'Mei' => 5,
                'Juni' => 6,
                'Juli' => 7,
                'Agustus' => 8,
                'September' => 9,
                'Oktober' => 10,
                'November' => 11,
                'Desember' => 12
            ];

            $monthNumber = $monthMap[$request->bulan] ?? null;
            if ($monthNumber) {
                $query->whereMonth('antrian.tanggal_antrian', $monthNumber);
            }
        }

        if ($request->has('layanan') && $request->layanan != '') {
            $query->where('antrian.id_poli', $request->layanan); // Changed to specify antrian.id_poli
        }

        $antrians = $query->get();
        $pdf = PDF::loadView('admin.antrian_pdf', compact('antrians'));
        return $pdf->download('antrian_report.pdf');
    }

    public function exportToExcel(Request $request)
    {
        $query = Antrian::with(['poli', 'layanan', 'pasien']);

        if ($request->has('bulan') && $request->bulan != '') {
            $monthNumber = date('m', strtotime($request->bulan));
            $query->whereMonth('tanggal_antrian', $monthNumber);
        }

        if ($request->has('layanan') && $request->layanan != '') {
            $query->where('id_poli', $request->layanan);
        }

        $antrians = $query->get();

        return Excel::download(new AntrianExport($antrians), 'antrian_report.xlsx');
    }
    public function kirimEmail($id)
{
    try {
        // Get the antrian data
        $antrian = DB::table('antrian')
            ->leftJoin('pasien_umum', 'antrian.id_pasienumum', '=', 'pasien_umum.id')
            ->leftJoin('pasien_bpjs', 'antrian.id_pasienbpjs', '=', 'pasien_bpjs.id')
            ->join('poli', 'antrian.id_poli', '=', 'poli.id')
            ->join('layanan', 'antrian.id_layanan', '=', 'layanan.id')
            ->leftJoin('jadwaldokter', 'antrian.id_jadwal', '=', 'jadwaldokter.id')
            ->leftJoin('dokter', 'jadwaldokter.id_dokter', '=', 'dokter.id')
            ->select(
                'antrian.no_antrian',
                'antrian.id',
                'antrian.status_antrian',
                DB::raw('COALESCE(pasien_umum.nama, pasien_bpjs.nama) AS nama'),
                'poli.nama_poli',
                'poli.kode_poli',
                'layanan.nama_layanan',
                'antrian.tanggal_antrian',
                'antrian.waktu_estimasi',
                'antrian.email',
                'dokter.nama_dokter',
                'antrian.id_poli'
            )
            ->where('antrian.id', $id)
            ->first();

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan']);
        }

        if (empty($antrian->email)) {
            return response()->json(['success' => false, 'message' => 'Pasien tidak memiliki alamat email']);
        }

        // Get current called antrian for the same poli
        $currentCalled = DB::table('antrian')
            ->leftJoin('pasien_umum', 'antrian.id_pasienumum', '=', 'pasien_umum.id')
            ->leftJoin('pasien_bpjs', 'antrian.id_pasienbpjs', '=', 'pasien_bpjs.id')
            ->join('poli', 'antrian.id_poli', '=', 'poli.id')
            ->select(
                'antrian.no_antrian',
                DB::raw('COALESCE(pasien_umum.nama, pasien_bpjs.nama) AS nama')
            )
            ->where('antrian.id_poli', $antrian->id_poli)
            ->whereDate('antrian.tanggal_antrian', today())
            ->where('antrian.status_antrian', 'dipanggil')
            ->orderBy('antrian.last_panggilan', 'desc')
            ->first();

        // Get waiting count for the same poli
        $waitingCount = DB::table('antrian')
            ->where('id_poli', $antrian->id_poli)
            ->whereDate('tanggal_antrian', today())
            ->where('status_antrian', 'menunggu')
            ->count();

        // Add additional information to the antrian object
        $antrian->current_called = $currentCalled ? $currentCalled->kode_poli .''. $currentCalled->no_antrian . ' - ' . $currentCalled->nama : 'Tidak ada';
        $antrian->waiting_count = $waitingCount;
        $antrian->your_position = $this->getPositionInQueue($id, $antrian->id_poli);

        // Send email
        Mail::to($antrian->email)->send(new AntrianNotification($antrian));

        return response()->json(['success' => true, 'message' => 'Email berhasil dikirim']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Gagal mengirim email: ' . $e->getMessage()]);
    }
}

private function getPositionInQueue($antrianId, $poliId)
{
    $antrians = DB::table('antrian')
        ->where('id_poli', $poliId)
        ->whereDate('tanggal_antrian', today())
        ->where('status_antrian', 'menunggu')
        ->orderBy('no_antrian', 'asc')
        ->pluck('id')
        ->toArray();

    $position = array_search($antrianId, $antrians);

    return $position !== false ? $position + 1 : null;
}
}
