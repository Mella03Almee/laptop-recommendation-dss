<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saran extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit karena nama tabelmu 'sarans'
    protected $table = 'sarans';

    // Mengizinkan semua kolom diisi secara mass assignment
    protected $guarded = ['id'];
}