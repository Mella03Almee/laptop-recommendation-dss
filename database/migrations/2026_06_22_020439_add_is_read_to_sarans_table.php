<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sarans', function (Blueprint $table) {
            // Menambahkan kolom is_read dengan default 0 (belum dibaca) setelah kolom isi_saran
            $table->tinyInteger('is_read')->default(0)->after('isi_saran');
        });
    }

    public function down(): void
    {
        Schema::table('sarans', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};