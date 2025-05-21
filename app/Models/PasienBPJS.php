<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasienBPJS extends Model
{
    use HasFactory;

    protected $table = 'pasien_bpjs';

    protected $fillable = [
        'nomor_bpjs', 'nama', 'alamat', 'tanggal_lahir', 'nik', 'faskes_tingkat_1'
    ];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'id_pasienbpjs');
    }
}
