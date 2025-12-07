<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Console\Command;

class AddPermissionToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add-permission {email} {permission_key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a permission to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $permissionKey = $this->argument('permission_key');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Utilisateur non trouvé avec l'email: {$email}");
            return Command::FAILURE;
        }

        $permission = Permission::where('key', $permissionKey)->first();
        
        if (!$permission) {
            $this->error("Permission non trouvée avec la clé: {$permissionKey}");
            $this->line("Permissions disponibles:");
            Permission::all()->each(function ($p) {
                $this->line("  - {$p->key} ({$p->name})");
            });
            return Command::FAILURE;
        }

        // Check if user already has this permission
        if ($user->permissions()->where('permissions.id', $permission->id)->exists()) {
            $this->warn("L'utilisateur a déjà la permission: {$permission->name}");
            return Command::SUCCESS;
        }

        // Add permission
        $user->permissions()->attach($permission->id);
        $user->clearPermissionsCache();

        $this->info("✓ Permission '{$permission->name}' ajoutée à {$user->name}");
        $this->line("Cache des permissions vidé.");

        return Command::SUCCESS;
    }
}
