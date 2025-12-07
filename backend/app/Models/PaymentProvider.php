<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'is_default',
        'environment',
        'credentials',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'credentials' => 'array',
        'config' => 'array',
    ];

    /**
     * Get the default active provider
     */
    public static function getDefault(): ?self
    {
        // D'abord chercher un provider marqué comme défaut (actif ou non)
        $default = static::where('is_default', true)->first();
        
        if ($default) {
            return $default;
        }
        
        // Sinon, chercher n'importe quel provider actif
        $active = static::where('is_active', true)->first();
        
        if ($active) {
            return $active;
        }
        
        // En dernier recours, retourner le premier provider disponible
        return static::first();
    }

    /**
     * Get active providers
     */
    public static function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)->get();
    }

    /**
     * Get a credential value
     */
    public function getCredential(string $key, $default = null)
    {
        return $this->credentials[$key] ?? $default;
    }

    /**
     * Set a credential value
     */
    public function setCredential(string $key, $value): void
    {
        $credentials = $this->credentials ?? [];
        $credentials[$key] = $value;
        $this->credentials = $credentials;
    }

    /**
     * Get a config value
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Set default provider (unset others)
     */
    public function setAsDefault(): void
    {
        static::where('id', '!=', $this->id)->update(['is_default' => false]);
        $this->is_default = true;
        $this->save();
    }
}
