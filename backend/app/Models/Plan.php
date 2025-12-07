<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_monthly',
        'price_currency',
        'allowed_audio_per_month',
        'allowed_audio_per_minute_length',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get subscriptions for this plan
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if plan is free
     */
    public function isFree(): bool
    {
        return $this->price_monthly == 0;
    }
}
