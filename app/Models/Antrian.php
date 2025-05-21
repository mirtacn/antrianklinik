<?php

namespace App\Models;
use App\Models\Poli;
use App\Models\Layanan;
use App\Models\Notifikasi;
use App\Models\Jadwaldokter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrian';
    protected $fillable = ['no_antrian', 'id_layanan','email','no_telepon', 'tanggal_antrian', 'waktu_pilih', 'waktu_estimasi', 'status_antrian', 'id_pasienumum', 'id_pasienbpjs', 'id_poli', 'id_jadwal'];
    
    public function pasienUmum()
    {
        return $this->belongsTo(PasienUmum::class, 'id_pasienumum');
    }

    public function pasienBPJS()
    {
        return $this->belongsTo(PasienBPJS::class, 'id_pasienbpjs');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_antrian');
    }

    public function jadwaldokter()
    {
        return $this->belongsTo(Jadwaldokter::class, 'id_jadwal');
    }
}

