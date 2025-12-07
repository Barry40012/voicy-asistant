<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'is_active',
        'subscribed_at',
        'unsubscribed_at',
        'ip_address',
        'source',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Subscribe an email
     */
    public static function subscribe(string $email, ?string $name = null, ?string $source = null): self
    {
        return static::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
                'ip_address' => request()->ip(),
                'source' => $source,
            ]
        );
    }

    /**
     * Unsubscribe an email
     */
    public function unsubscribe(): void
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }
}
