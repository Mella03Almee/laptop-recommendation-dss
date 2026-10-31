<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Tabel Fakultas
            $table->foreignId('fakultas_id')->constrained('fakultas')->onDelete('cascade');
            
            // Relasi ke Tabel Kategori Bobot (WP)
            $table->foreignId('kategori_bobot_id')->constrained('kategori_bobot')->onDelete('cascade');
            
            $table->string('nama_jurusan'); // Contoh: Sistem Informasi, Akuntansi, dll.
            $table->string('jenjang', 10)->nullable(); // Contoh: S1, D3, S2
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurusan');
    }
};