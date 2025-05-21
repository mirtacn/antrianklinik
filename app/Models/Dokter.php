<?php

namespace App\Models;
use App\Models\Poli;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    protected $fillable = ['foto_profil','nama_dokter', 'nama_spesialis', 'no_telepon'];

    public function polis()
    {
        return $this->belongsToMany(Poli::class, 'polis', 'dokter_id', 'poli_id');
    }

    public function jadwalDokter()
    {
        return $this->hasMany(Jadwaldokter::class, 'id_dokter');
    }
}
