<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DatadokterController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokter::with('polis');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_dokter', 'like', "%$search%")
                  ->orWhere('nama_spesialis', 'like', "%$search%")
                  ->orWhere('no_telepon', 'like', "%$search%")
                  ->orWhereHas('polis', function ($q) use ($search) {
                      $q->where('nama_poli', 'like', "%$search%");
                  });
        }

        $dokter = $query->paginate(5);
        return view('admin.dokter', compact('dokter'));
    }

    public function add()
    {
        $poli = Poli::all();
        return view('admin.tambahdokter', compact('poli'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'nama_spesialis' => 'required|string|max:255',
            'id_poli' => 'required|array',
            'id_poli.*' => 'exists:poli,id',
            'no_telepon' => 'required|string|max:15',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan data dokter
        $dokter = new Dokter();
        $dokter->nama_dokter = $request->nama_dokter;
        $dokter->nama_spesialis = $request->nama_spesialis;
        $dokter->no_telepon = $request->no_telepon;

        // Handle upload foto profil
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('dokter_profiles', 'public');
            $dokter->foto_profil = $path;
        }

        $dokter->save();

        // Simpan data poli yang dipilih
        $dokter->polis()->sync($request->id_poli);

        return redirect()->route('dokter')->with('success', 'Data dokter berhasil disimpan.');
    }

    public function edit($id)
    {
        $poli = Poli::all();
        $dokter = Dokter::findOrFail($id);
        return view('admin.editdokter', compact('dokter', 'poli'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_poli' => 'required|array',
            'id_poli.*' => 'exists:poli,id',
            'nama_dokter' => 'required|string|max:255',
            'nama_spesialis' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $dokter = Dokter::findOrFail($id);

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($dokter->foto_profil) {
                Storage::disk('public')->delete($dokter->foto_profil);
            }

            $filename = $request->file('foto_profil')->store('dokter_profiles', 'public');
            $dokter->foto_profil = $filename;
        }

        $dokter->update([
            'nama_dokter' => $request->nama_dokter,
            'nama_spesialis' => $request->nama_spesialis,
            'no_telepon' => $request->no_telepon,
        ]);

        // Update data poli yang dipilih
        $dokter->polis()->sync($request->id_poli);

        return redirect()->route('dokter')->with('success', 'Data Dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);

        // Hapus foto profil jika ada
        if ($dokter->foto_profil) {
            $path = str_replace('storage/', 'public/', $dokter->foto_profil);
            Storage::delete($path);
        }

        $dokter->delete();

        return redirect()->route('dokter')->with('success', 'Data Dokter berhasil dihapus.');
    }
}