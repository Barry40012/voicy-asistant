<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that sent the message (if authenticated)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Check if message is new
     */
    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return $this->status === 'read' || $this->read_at !== null;
    }
}
