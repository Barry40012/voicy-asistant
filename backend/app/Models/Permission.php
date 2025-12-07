<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
    ];

    /**
     * Get users with this permission
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'admin_permissions');
    }
}
