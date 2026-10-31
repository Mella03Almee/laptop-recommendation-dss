<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';

    protected $fillable = [
        'universitas_id',
        'nama_fakultas',
    ];

    public function universitas()
    {
        return $this->belongsTo(Universitas::class, 'universitas_id');
    }

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class, 'fakultas_id');
    }
}