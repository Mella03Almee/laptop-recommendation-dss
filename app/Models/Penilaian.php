<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    // Kolom id tidak boleh diisi manual, sisanya aman
    protected $guarded = ['id'];
    
    // Eager loading otomatis untuk relasi alternatif dan user
    protected $with = ['alternatif', 'user'];
    
    protected $casts = ['updated_at' => 'datetime'];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * PERBAIKAN: Fungsi ini WAJIB dimatikan/dihapus.
     * Jika aktif, Laravel akan mencari data lewat kolom 'slug', 
     * padahal di Controller kita mencari berdasarkan ID (findOrFail($id)).
     */
    /*
    public function getRouteKeyName()
    {
        return 'slug';
    }
    */

    public function getFormattedUpdatedAt()
    {
        if ($this->updated_at === null) {
            return 'Data Kosong';
        }

        $updatedAt = $this->updated_at;

        if ($updatedAt->isToday()) {
            return 'Updated today at ' . $updatedAt->format('h:i A');
        } elseif ($updatedAt->isYesterday()) {
            return 'Updated yesterday at ' . $updatedAt->format('h:i A');
        } else {
            return 'Updated on ' . $updatedAt->format('F j, Y') . ' at ' . $updatedAt->format('h:i A');
        }
    }
}