<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'ai_type',
        'gemini_api_key',
        'is_active',
        'reply_to_groups',
        'contact_filter_mode',
        'contact_numbers',
        'model',
        'system_prompt',
        'max_tokens',
        'temperature',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'reply_to_groups' => 'boolean',
        'settings' => 'array',
        'max_tokens' => 'integer',
        'temperature' => 'float',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
