<?php

namespace App\Http\Controllers;
use App\Models\Poli;
use Illuminate\Http\Request;

class DatapoliController extends Controller
{
    public function index(Request $request)
    {
        $query = Poli::query();

        if($request->has('search')) {
            $search = $request->search;
            $query->where('kode_poli', 'like', "%$search%")
                  ->orWhere('nama_poli', 'like', "%$search%");
        }

        $poli = $query->paginate(15);
        return view('admin.poli', compact('poli'));
    }

    public function add()
    {
        return view('admin.tambahpoli');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_poli' => 'required|string|size:1|regex:/^[A-Z]$/',
            'nama_poli' => 'required|string|max:255',
        ]);

        Poli::create([
            'kode_poli' => $request->kode_poli,
            'nama_poli' => $request->nama_poli,
        ]);

        return redirect()->route('poli')->with('success', 'Data Poli berhasil disimpan.');
    }

    public function edit($id)
    {
        $poli = Poli::findOrFail($id);
        return view('admin.editpoli', compact('poli'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_poli' => 'required|string|size:1|regex:/^[A-Z]$/',
            'nama_poli' => 'required|string|max:255',
        ]);

        $poli = Poli::findOrFail($id);
        $poli->update([
            'kode_poli' => $request->kode_poli,
            'nama_poli' => $request->nama_poli,
        ]);
        return redirect()->route('poli')->with('success', 'Data Poli berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $poli = Poli::findOrFail($id);
        $poli->delete();

        return redirect()->route('poli')->with('success', 'Data Poli berhasil dihapus.');
    }
}
