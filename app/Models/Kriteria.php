<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    // Pastikan fillable atau guarded sudah sesuai dengan project kamu
    protected $guarded = ['id']; 

    /**
     * Relasi HasMany: Satu Kriteria memiliki BANYAK Sub Kriteria
     */
    public function subKriteria()
    {
        // Menghubungkan ke model SubKriteria
        // Parameter kedua disesuaikan dengan nama foreign key di tabel sub kriteria kamu, biasanya 'kriteria_id'
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }
    /**
     * Mengformat tanggal updated_at menjadi format Indonesia yang rapi.
     */
    public function getFormattedUpdatedAt()
    {
        return $this->updated_at ? $this->updated_at->format('d-m-Y H:i') : '-';
    }
}