<?php

namespace App\Http\Controllers;
use App\Models\Pasien;
use Illuminate\Http\Request;

class DatapasienController extends Controller
{
    public function index(Request $request)
    {
        $query = Pasien::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('no_ktp', 'like', "%$search%")
                  ->orWhere('no_telepon', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        $pasien = Pasien::orderBy('id', 'desc')->paginate(7);
        return view('admin.pasien', compact('pasien'));
    }
}
