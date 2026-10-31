<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alternatifs', function (Blueprint $table) {
            // Menambahkan kolom spesifikasi baru (nullable agar aman jika ada data kosong)
            $table->string('harga')->nullable()->after('gambar');
            $table->string('cpu')->nullable()->after('harga');
            $table->string('gpu')->nullable()->after('cpu');
            $table->string('ram')->nullable()->after('gpu');
            $table->string('storage')->nullable()->after('ram');
            $table->string('layar')->nullable()->after('storage');
            $table->string('berat')->nullable()->after('layar');
            $table->string('kamera')->nullable()->after('berat');
            $table->string('baterai')->nullable()->after('kamera');
        });
    }

    public function down(): void
    {
        Schema::table('alternatifs', function (Blueprint $table) {
            $table->dropColumn(['harga', 'cpu', 'gpu', 'ram', 'storage', 'layar', 'berat', 'kamera', 'baterai']);
        });
    }
};