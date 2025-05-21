<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $fillable = ['id_antrian', 'tanggal_kirim', 'waktu_kirim', 'nama_layanan', 'status_antrian'];

    public function antrian()
    {
        return $this->belongsTo(Antrian::class, 'id_antrian');
    }
}
