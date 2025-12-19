<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiConversationHistory extends Model
{
    use HasFactory;

    protected $table = 'ai_conversation_history';
    
    public $timestamps = false; // We only use created_at

    protected $fillable = [
        'device_id',
        'sender',
        'role',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationship
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
