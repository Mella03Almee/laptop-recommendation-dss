<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriterias'; // Opsional, tapi aman untuk menegaskan nama tabel

    protected $fillable = [
    'kriteria_id',
    'harga',   // Ubah dari 'nama' menjadi 'harga' sesuai struktur form/DB Anda
    'nHarga',  // Ubah dari 'nilai' menjadi 'nHarga' sesuai struktur form/DB Anda
    'slug',
    'user_id',

    ];

    // Relasi balik ke Kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}