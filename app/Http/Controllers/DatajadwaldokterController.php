<?php

namespace App\Http\Controllers;

use App\Models\Jadwaldokter;
use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Http\Request;

class DatajadwaldokterController extends Controller
{
    public function getPoliByDoctor($doctorId)
    {
        try {
            $dokter = Dokter::with('polis')->findOrFail($doctorId);
            $polis = $dokter->polis;

            if ($polis->isEmpty()) {
                return response()->json([]);
            }

            return response()->json($polis);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data poli: ' . $e->getMessage()
            ], 500);
        }
    }

public function index(Request $request)
{
    $query = Jadwaldokter::with(['dokter', 'poli']); 

    if ($request->has('search')) {
        $search = $request->search;
        $query->whereHas('dokter', function ($q) use ($search) {
            $q->where('nama_dokter', 'like', "%$search%");
        })->orWhere('hari', 'like', "%$search%");
    }

    $jadwaldokter = $query->paginate(7);
    return view('admin.jadwaldokter', compact('jadwaldokter'));
}

    public function add()
    {
        try {
            $dokter = Dokter::all();
            $poli = Poli::all();
            return view('admin.tambahjadwaldokter', compact('dokter', 'poli'));
        } catch (\Exception $e) {
            return redirect()->route('jadwaldokter')->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dokter' => 'required|integer',
            'id_poli' => 'required|integer',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kuotasisa' => 'required|integer',
        ], [
            'id_poli.required' => 'Poli harus dipilih',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai'
        ]);

        Jadwaldokter::create([
            'id_dokter' => $request->id_dokter,
            'id_poli' => $request->id_poli,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuotasisa' => $request->kuotasisa,
            'kuotadipanggil' => 0,
            'kuotadiambil' => 0,
        ]);

        return redirect()->route('jadwaldokter')->with('success', 'Data Jadwal Dokter berhasil disimpan.');
    }

    public function edit($id)
{
    $dokter = Dokter::all();
    $poli = Poli::all(); // Ambil semua data poli
    $jadwaldokter = Jadwaldokter::findOrFail($id);
    return view('admin.editjadwaldokter', compact('jadwaldokter', 'dokter', 'poli'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'id_dokter' => 'required|integer',
        'id_poli' => 'required|integer', // Validasi untuk poli
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required|after:jam_mulai',
        'kuotasisa' => 'required|integer',
    ], [
        'jam_mulai.required' => 'Jam mulai harus diisi',
        'jam_selesai.required' => 'Jam selesai harus diisi',
        'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai'
    ]);

    $jadwaldokter = Jadwaldokter::findOrFail($id);
    $jadwaldokter->update([
        'id_dokter' => $request->id_dokter,
        'id_poli' => $request->id_poli, // Update id_poli
        'hari' => $request->hari,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
        'kuotasisa' => $request->kuotasisa,
        'kuotadipanggil' => 0,
        'kuotadiambil' => 0,
    ]);

    return redirect()->route('jadwaldokter')->with('success', 'Data Jadwal Dokter berhasil diperbarui.');
}

    public function destroy($id)
    {
        $jadwaldokter = Jadwaldokter::findOrFail($id);
        $jadwaldokter->delete();

        return redirect()->route('jadwaldokter')->with('success', 'Jadwal Dokter berhasil dihapus.');
    }
}
