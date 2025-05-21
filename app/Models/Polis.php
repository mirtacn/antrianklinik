<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dokter;

class Polis extends Model
{
    protected $table = 'polis';
    protected $fillable = ['poli_id', 'dokter_id'];

    public function dokter()
{
    return $this->belongsTo(Dokter::class, 'dokter_id');
}

public function poli()
{
    return $this->belongsTo(Poli::class, 'poli_id');
}
}