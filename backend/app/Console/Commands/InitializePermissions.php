<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;

class InitializePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize all admin permissions in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initialisation des permissions...');

        $permissions = [
            [
                'key' => 'view_dashboard',
                'name' => 'Voir le Dashboard',
                'description' => 'Accès au tableau de bord administrateur',
            ],
            [
                'key' => 'manage_users',
                'name' => 'Gérer les utilisateurs',
                'description' => 'Créer, modifier et supprimer des utilisateurs',
            ],
            [
                'key' => 'manage_plans',
                'name' => 'Gérer les plans',
                'description' => 'Créer, modifier et supprimer des plans d\'abonnement',
            ],
            [
                'key' => 'manage_subscriptions',
                'name' => 'Gérer les abonnements',
                'description' => 'Voir et gérer les abonnements des utilisateurs',
            ],
            [
                'key' => 'manage_payments',
                'name' => 'Gérer les paiements',
                'description' => 'Voir et gérer les transactions de paiement',
            ],
            [
                'key' => 'manage_audios',
                'name' => 'Gérer les audios',
                'description' => 'Voir et gérer les audios des utilisateurs',
            ],
            [
                'key' => 'view_logs',
                'name' => 'Voir les logs',
                'description' => 'Accès aux logs système',
            ],
            [
                'key' => 'manage_comments',
                'name' => 'Gérer les commentaires',
                'description' => 'Modérer et gérer les commentaires',
            ],
            [
                'key' => 'manage_newsletter',
                'name' => 'Gérer la newsletter',
                'description' => 'Gérer les abonnés à la newsletter',
            ],
            [
                'key' => 'manage_contact_messages',
                'name' => 'Gérer les messages de contact',
                'description' => 'Voir et répondre aux messages de contact',
            ],
            [
                'key' => 'manage_admins',
                'name' => 'Gérer les administrateurs',
                'description' => 'Créer et gérer les administrateurs et leurs permissions',
            ],
            [
                'key' => 'manage_payment_providers',
                'name' => 'Gérer les providers de paiement',
                'description' => 'Configurer les providers de paiement',
            ],
        ];

        $created = 0;
        $updated = 0;

        foreach ($permissions as $permission) {
            $existing = Permission::where('key', $permission['key'])->first();
            
            if ($existing) {
                $existing->update($permission);
                $updated++;
                $this->line("✓ Permission mise à jour: {$permission['name']}");
            } else {
                Permission::create($permission);
                $created++;
                $this->line("✓ Permission créée: {$permission['name']}");
            }
        }

        $this->info("\n✅ Initialisation terminée!");
        $this->info("   - {$created} permissions créées");
        $this->info("   - {$updated} permissions mises à jour");
        $this->info("\n💡 Utilisez '/admin/admins' pour gérer les permissions des administrateurs.");

        return Command::SUCCESS;
    }
}
