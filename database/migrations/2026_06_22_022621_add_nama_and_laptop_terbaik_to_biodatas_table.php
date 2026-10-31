<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biodatas', function (Blueprint $table) {
            // Menambahkan kolom nama setelah id (dibuat nullable agar data lama aman)
            $table->string('nama')->nullable()->after('id');
            
            // Menambahkan kolom untuk menampung nama laptop juara 1 setelah kolom jurusan
            $table->string('laptop_terbaik')->nullable()->after('jurusan');
        });
    }

    public function down(): void
    {
        Schema::table('biodatas', function (Blueprint $table) {
            $table->dropColumn(['nama', 'laptop_terbaik']);
        });
    }
};