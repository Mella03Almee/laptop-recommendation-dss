<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('universitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_universitas'); // Contoh: UIN Raden Intan Lampung
            $table->string('singkatan', 20);     // Contoh: UIN, UNILA, ITERA
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('universitas');
    }
};