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
            // Gunakan Schema, bukan Route
            Schema::create('biodatas', function (Blueprint $table) {
                $table->id();
                $table->string('kampus');
                $table->string('fakultas');
                $table->string('jurusan');
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
        Schema::dropIfExists('biodatas');
    }
};
