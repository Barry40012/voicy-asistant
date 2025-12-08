<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use App\Models\Permission;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'stripe_customer_id',
        'provider_metadata',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'provider_metadata' => 'array',
    ];

    /**
     * Get subscriptions for this user
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription (most recent one)
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get WhatsApp connections for this user
     */
    public function whatsappConnections()
    {
        return $this->hasMany(WhatsAppConnection::class);
    }

    /**
     * Get audios for this user
     */
    public function audios()
    {
        return $this->hasMany(Audio::class);
    }

    /**
     * Get payments for this user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get permissions for this user
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_permissions');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Get cached permissions keys for this user
     */
    public function getCachedPermissionKeys(): array
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return Permission::pluck('key')->toArray();
        }

        return cache()->remember(
            "user_permissions_{$this->id}",
            now()->addMinutes(30),
            function () {
                return $this->permissions()->pluck('key')->toArray();
            }
        );
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permissionKey): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check if user has the permission (using cache)
        $permissions = $this->getCachedPermissionKeys();
        return in_array($permissionKey, $permissions);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissionKeys): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check if user has any of the permissions (using cache)
        $permissions = $this->getCachedPermissionKeys();
        return !empty(array_intersect($permissionKeys, $permissions));
    }

    /**
     * Clear permissions cache for this user
     */
    public function clearPermissionsCache(): void
    {
        cache()->forget("user_permissions_{$this->id}");
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Get user notifications
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\UserNotification::class);
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
