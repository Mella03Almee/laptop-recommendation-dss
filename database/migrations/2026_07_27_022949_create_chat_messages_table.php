<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan di sini NAMA TABELNYA adalah 'chat_messages'
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('chat_session_id');
            $table->enum('sender', ['user', 'bot']);
            $table->text('pesan');
            $table->timestamps();

            // Relasi ke tabel chat_sessions yang sudah ada
            $table->foreign('chat_session_id')
                  ->references('id')
                  ->on('chat_sessions')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};