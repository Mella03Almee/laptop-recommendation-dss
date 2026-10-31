<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurusan', function (Blueprint $table) {
            $table->unsignedBigInteger('harga_min_saran')
                ->nullable()
                ->after('kategori_bobot_id');

            $table->unsignedBigInteger('harga_max_saran')
                ->nullable()
                ->after('harga_min_saran');

            $table->string('tingkat_kebutuhan', 50)
                ->nullable()
                ->after('harga_max_saran');

            $table->text('software_utama')
                ->nullable()
                ->after('tingkat_kebutuhan');

            $table->text('profil_perangkat')
                ->nullable()
                ->after('software_utama');
        });
    }

    public function down(): void
    {
        Schema::table('jurusan', function (Blueprint $table) {
            $table->dropColumn([
                'harga_min_saran',
                'harga_max_saran',
                'tingkat_kebutuhan',
                'software_utama',
                'profil_perangkat',
            ]);
        });
    }
};