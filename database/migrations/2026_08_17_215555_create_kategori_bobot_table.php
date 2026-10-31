<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_bobot', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Contoh: Komputasi & IT, Ekonomi & Bisnis, dll.
            
            // Kriteria Cost (Total = 10)
            $table->float('c1_harga')->default(5.0);
            $table->float('c6_ukuran_layar')->default(2.0);
            $table->float('c8_berat')->default(3.0);
            
            // Kriteria Benefit (Total = 10)
            $table->float('c2_cpu');
            $table->float('c3_ram');
            $table->float('c4_storage');
            $table->float('c5_gpu');
            $table->float('c7_baterai');
            $table->float('c9_kamera');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_bobot');
    }
};