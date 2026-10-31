<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai dengan yang ada di database
    protected $table = 'biodatas';

    // Daftarkan semua kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama',
        'kampus',
        'fakultas',
        'jurusan',
        'laptop_terbaik',
    ];
}