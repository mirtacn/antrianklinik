<?php

namespace App\Models;
use App\Models\Dokter;
use App\Models\Antrian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'poli';
    protected $fillable = ['kode_poli', 'nama_poli'];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'id_poli');
    }

    public function dokters()
    {
        return $this->belongsToMany(Dokter::class, 'polis', 'poli_id', 'dokter_id');
    }
    public function jadwalDokter()
    {
        return $this->hasMany(Jadwaldokter::class, 'id_poli');
    }
}

