<?php

namespace App\Models;
use App\Models\Antrian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';

    protected $fillable = [
        'nama_lengkap',
        'no_ktp',
        'no_telepon',
        'email',
    ];

    // public function antrian()
    // {
    //     return $this->hasMany(Antrian::class, 'id_pasien');
    // }
}
