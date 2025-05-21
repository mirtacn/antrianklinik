<?php

namespace App\Http\Controllers;
use App\Models\Layanan;
use Illuminate\Http\Request;

class DatalayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }
        $layanan = $query->paginate(15);
        return view('admin.layanan', compact('layanan'));
    }

    public function add()
    {
        return view('admin.tambahlayanan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
        ]);

        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('layanan')->with('success', 'Data Layanan berhasil disimpan.');
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('admin.editlayanan', compact('layanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
        ]);

        // Update data layanan
        $layanan = Layanan::findOrFail($id);
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('layanan')->with('success', 'Data Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->delete();

        return redirect()->route('layanan')->with('success', 'Data Layanan berhasil dihapus.');
    }
}
