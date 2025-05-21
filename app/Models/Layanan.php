<?php

namespace App\Models;
use App\Models\Antrian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';
    protected $fillable = ['nama_layanan', 'deskripsi'];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'id_layanan');
    }
}
