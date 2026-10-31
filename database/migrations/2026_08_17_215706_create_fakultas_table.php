<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fakultas', function (Blueprint $table) {
            $table->id();
            
            // Kunci Penghubung ke Tabel Universitas (Foreign Key)
            $table->foreignId('universitas_id')->constrained('universitas')->onDelete('cascade');
            
            $table->string('nama_fakultas'); // Contoh: Fakultas Sains dan Teknologi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fakultas');
    }
};