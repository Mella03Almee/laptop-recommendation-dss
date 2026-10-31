<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Menyimpan ID unik misal: "user-29724"
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable(); // Informasi browser/perangkat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};