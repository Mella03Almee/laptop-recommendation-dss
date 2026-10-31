<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = [
        'fakultas_id',
        'kategori_bobot_id',
        'nama_jurusan',
        'jenjang',
        'harga_min_saran',
        'harga_max_saran',
        'tingkat_kebutuhan',
        'software_utama',
        'profil_perangkat',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function kategoriBobot()
    {
        return $this->belongsTo(KategoriBobot::class, 'kategori_bobot_id');
    }
}