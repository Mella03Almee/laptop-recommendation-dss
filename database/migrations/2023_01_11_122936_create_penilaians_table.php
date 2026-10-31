<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('alternatif_id')
                ->constrained('alternatifs')
                ->onDelete('cascade');
            $table->string('slug')->unique();
            
            // Skor Angka Mentah (Skala 1-5) diperluas sampai C10x
            $table->integer('C1x');
            $table->integer('C2x');
            $table->integer('C3x');
            $table->integer('C4x');
            $table->integer('C5x');
            $table->integer('C6x');
            $table->integer('C7x');
            $table->integer('C8x');
            $table->integer('C9x');
            $table->integer('C10x');
            
            // Nilai bobot pangkat/normalisasi diperluas sampai C10_val
            $table->double('C1_val')->nullable();
            $table->double('C2_val')->nullable();
            $table->double('C3_val')->nullable();
            $table->double('C4_val')->nullable();
            $table->double('C5_val')->nullable();
            $table->double('C6_val')->nullable();
            $table->double('C7_val')->nullable();
            $table->double('C8_val')->nullable();
            $table->double('C9_val')->nullable();
            $table->double('C10_val')->nullable();
            
            // Kolom utama hasil akhir rumus WP
            $table->double('nilai_s')->nullable();
            $table->double('nilai_v')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penilaians');
    }
};
