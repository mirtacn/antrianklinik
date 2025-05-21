<?php

namespace App\Http\Controllers;
use App\Models\Antrian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        $queueCounts = Antrian::select('id_poli', \DB::raw('count(*) as total'))
            ->whereDate('tanggal_antrian', $today)
            ->groupBy('id_poli')
            ->get()
            ->keyBy('id_poli');

        return view('admin.dashboard', compact('queueCounts'));
    }
}
