<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('kodeKriteria');
            $table->string('namaKriteria');
            $table->string('slug')->nullable();
            $table->integer('bobot')->default(1);
            $table->string('jenis');
            $table->double('w_normalisasi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kriterias');
    }
};