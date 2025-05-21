<?php

namespace App\Exports;

use App\Models\Antrian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AntrianExport implements FromCollection, WithHeadings
{
    protected $antrians;

    public function __construct($antrians)
    {
        $this->antrians = $antrians;
    }

    public function collection()
    {
        return $this->antrians->map(function ($antrian) {
            return [
                'No Antrian' => $antrian->no_antrian ?? 'N/A',
                'Kode Poli' => $antrian->kode_poli ?? 'N/A',
                'Nama Pasien' => $antrian->pasien->nama_lengkap ?? 'N/A',
                'Poli' => $antrian->poli->nama_poli ?? 'N/A',
                'Layanan' => $antrian->layanan->nama_layanan ?? 'N/A',
                'Tanggal' => $antrian->tanggal_antrian ?? 'N/A',
                'Jam' => $antrian->waktu_pilih ?? 'N/A',
                'Status' => $antrian->status_antrian ?? 'N/A'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No Antrian',
            'Nama Pasien',
            'Poli',
            'Layanan',
            'Tanggal',
            'Jam',
            'Status'
        ];
    }
}