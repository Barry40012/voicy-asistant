<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Audio extends Model
{
    use HasFactory;

    protected $table = 'audios';

    protected $fillable = [
        'user_id',
        'whatsapp_message_id',
        'sender_phone',
        'file_path',
        'duration_seconds',
        'size_bytes',
        'status',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this audio
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the analysis for this audio
     */
    public function analysis(): HasOne
    {
        return $this->hasOne(AudioAnalysis::class);
    }

    /**
     * Check if audio is processed
     */
    public function isProcessed(): bool
    {
        return $this->status === 'done';
    }

    /**
     * Check if audio has error
     */
    public function hasError(): bool
    {
        return $this->status === 'error';
    }
}
