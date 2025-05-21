<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\PasienUmum;
use Illuminate\Http\Request;

class PasienUmumController extends Controller
{
    public function cariPasien(Request $request)
{
    $nik = $request->nik;

    $pasien = DB::table('pasien_umum')
        ->where('nik', $nik)
        ->first();
    if ($pasien) {
        return response()->json([
            'status' => 'found',
            'data' => [
                'nama' => $pasien->nama,
            ]
        ]);
    }

    return response()->json(['status' => 'not_found']);
}

    public function index(Request $request)
    {
        $query = PasienUmum::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nik', 'like', "%$search%")
                  ->orWhere('nama', 'like', "%$search%");
        }

        $pasien = $query->paginate(4);
        return view('admin.pasienumum', compact('pasien'));
    }

    public function create()
    {
        return view('admin.tambahpasienumum');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|digits:16',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'nama_ibu' => 'required',
            'pendidikan_terakhir' => 'required',
        ]);

        PasienUmum::create($request->all());

        return redirect()->route('pasienumum')->with('success', 'Data Pasien Umum berhasil disimpan.');
    }

    public function edit($id)
    {
        $pasien = PasienUmum::findOrFail($id);
        return view('admin.editpasienumum', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|unique:pasien_umum,nik,' . $id,
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'nama_ibu' => 'required',
            'pendidikan_terakhir' => 'required',
        ]);

        $pasien = PasienUmum::findOrFail($id);
        $pasien->update($request->all());

        return redirect()->route('pasienumum')->with('success', 'Data Pasien Umum berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pasien = PasienUmum::findOrFail($id);
        $pasien->delete();

        return redirect()->route('pasienumum')->with('success', 'Data Pasien Umum berhasil dihapus.');
    }
}
