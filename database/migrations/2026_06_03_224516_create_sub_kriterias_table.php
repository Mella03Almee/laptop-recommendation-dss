<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sub_kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->nullable();
            $table->foreignId('user_id')->nullable();
            
            // Kolom gabungan agar seeder lama maupun form baru tidak ada yang error:
            $table->string('harga')->nullable();
            $table->integer('nHarga')->nullable();
            $table->string('nama')->nullable();
            $table->integer('nilai')->nullable();
            
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_kriterias');
    }
};