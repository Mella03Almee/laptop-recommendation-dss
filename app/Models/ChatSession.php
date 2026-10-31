<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $table = 'chat_sessions';
    protected $primaryKey = 'id';
    public $incrementing = false; // Karena ID berupa string (contoh: user-29724)
    protected $keyType = 'string';

    protected $fillable = ['id', 'ip_address', 'user_agent'];

    // Relasi: Satu sesi memiliki banyak pesan
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_session_id', 'id');
    }
}