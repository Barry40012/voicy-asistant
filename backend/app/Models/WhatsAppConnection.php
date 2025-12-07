<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class WhatsAppConnection extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_connections';

    protected $fillable = [
        'user_id',
        'phone_number_id',
        'whatsapp_business_account_id',
        'access_token',
        'token_expires_at',
        'webhook_verified',
        'phone_number',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'webhook_verified' => 'boolean',
    ];

    /**
     * Get the user that owns this connection
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get encrypted access token
     */
    public function getAccessTokenAttribute($value): ?string
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Set encrypted access token
     */
    public function setAccessTokenAttribute($value): void
    {
        $this->attributes['access_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Check if token is expired
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }
        return $this->token_expires_at->isPast();
    }
}
