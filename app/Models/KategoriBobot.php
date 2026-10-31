<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBobot extends Model
{
    use HasFactory;

    // Kunci nama tabel agar tidak mencari 'kategori_bobots'
    protected $table = 'kategori_bobot';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class, 'kategori_bobot_id');
    }
}