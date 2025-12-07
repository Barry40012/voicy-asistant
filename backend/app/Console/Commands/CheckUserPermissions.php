<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Console\Command;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-permissions {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user permissions and role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Utilisateur non trouvé avec l'email: {$email}");
            return Command::FAILURE;
        }

        $this->info("=== Informations Utilisateur ===");
        $this->line("ID: {$user->id}");
        $this->line("Nom: {$user->name}");
        $this->line("Email: {$user->email}");
        $role = $user->role ?? 'null';
        $this->line("Rôle: {$role}");
        $this->line("");

        $this->info("=== Statut Administrateur ===");
        $this->line("Is Admin: " . ($user->isAdmin() ? 'OUI ✓' : 'NON ✗'));
        $this->line("Is Super Admin: " . ($user->isSuperAdmin() ? 'OUI ✓' : 'NON ✗'));
        $this->line("");

        if ($user->isAdmin()) {
            $this->info("=== Permissions ===");
            
            // Clear cache first
            $user->clearPermissionsCache();
            
            $permissions = $user->permissions;
            $permissionKeys = $user->getCachedPermissionKeys();
            
            if ($user->isSuperAdmin()) {
                $this->line("Super Admin - Accès à toutes les permissions");
                $allPermissions = Permission::all();
                $this->line("Total permissions disponibles: " . $allPermissions->count());
            } else {
                if ($permissions->isEmpty()) {
                    $this->warn("⚠️  Aucune permission assignée à cet administrateur!");
                    $this->line("Cet admin ne pourra accéder à aucune section du panel admin.");
                    $this->line("");
                    $this->line("Pour assigner des permissions:");
                    $this->line("1. Allez sur /admin/admins");
                    $this->line("2. Cliquez sur 'Modifier' à côté de cet utilisateur");
                    $this->line("3. Cochez les permissions souhaitées");
                } else {
                    $this->line("Permissions assignées ({$permissions->count()}):");
                    foreach ($permissions as $permission) {
                        $this->line("  - {$permission->name} ({$permission->key})");
                    }
                }
                
                $this->line("");
                $this->line("Permissions en cache: " . count($permissionKeys));
                $this->line("Clés: " . implode(', ', $permissionKeys));
            }
        } else {
            $this->warn("⚠️  Cet utilisateur n'est pas un administrateur!");
            $this->line("Pour le rendre admin:");
            $this->line("php artisan user:make-admin {$email}");
        }

        return Command::SUCCESS;
    }
}
