<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasienUmum extends Model
{
    use HasFactory;

    protected $table = 'pasien_umum';

    protected $fillable = [
        'nik', 'nama', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'alamat', 'nama_ibu', 'pendidikan_terakhir'
    ];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'id_pasienumum');
    }
}