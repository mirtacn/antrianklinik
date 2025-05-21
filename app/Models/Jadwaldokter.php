<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwaldokter extends Model
{
    protected $table = 'jadwaldokter';
    protected $fillable = ['id_dokter', 'hari', 'jam_mulai', 'jam_selesai', 'id_poli', 'kuotasisa', 'kuotadiambil','kuotadipanggil'];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}