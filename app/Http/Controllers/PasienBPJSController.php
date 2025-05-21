<?php

namespace App\Http\Controllers;

use App\Models\PasienBpjs;
use Illuminate\Http\Request;

class PasienBpjsController extends Controller
{
    public function index(Request $request) {
        $pasien = PasienBpjs::when($request->search, function($query) use ($request) {
            $query->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('nik', 'like', '%'.$request->search.'%');
        })->paginate(10);

        return view('admin.pasienbpjs', compact('pasien'));
    }

    public function create() {
        return view('admin.tambahpasienbpjs');
    }

    public function store(Request $request) {
        $request->validate([
            'nomor_bpjs' => 'required|unique:pasien_bpjs',
            'nama' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|unique:pasien_bpjs',
            'faskes_tingkat_1' => 'required',
        ]);

        PasienBpjs::create($request->all());

        return redirect()->route('pasienbpjs')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id) {
        $pasien = PasienBpjs::findOrFail($id);
        return view('admin.editpasienbpjs', compact('pasien'));
    }

    public function update(Request $request, $id) {
        $pasien = PasienBpjs::findOrFail($id);

        $request->validate([
            'nomor_bpjs' => 'required|unique:pasien_bpjs,nomor_bpjs,'.$pasien->id,
            'nama' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|unique:pasien_bpjs,nik,'.$pasien->id,
            'faskes_tingkat_1' => 'required',
        ]);

        $pasien->update($request->all());

        return redirect()->route('pasienbpjs')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id) {
        PasienBpjs::destroy($id);
        return redirect()->route('pasienbpjs')->with('success', 'Data berhasil dihapus.');
    }
}

