<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            [
                'key' => 'manage_users',
                'name' => 'Gérer les utilisateurs',
                'description' => 'Permet de voir, modifier et gérer les utilisateurs de la plateforme',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'manage_plans',
                'name' => 'Gérer les plans',
                'description' => 'Permet de créer, modifier et supprimer les plans d\'abonnement',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'manage_subscriptions',
                'name' => 'Gérer les abonnements',
                'description' => 'Permet de voir et gérer les abonnements des utilisateurs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'manage_payments',
                'name' => 'Gérer les paiements',
                'description' => 'Permet de voir l\'historique des paiements',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'manage_audios',
                'name' => 'Gérer les audios',
                'description' => 'Permet de voir et gérer tous les audios de la plateforme',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'manage_logs',
                'name' => 'Gérer les logs',
                'description' => 'Permet d\'accéder aux logs système',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'manage_settings',
                'name' => 'Gérer les paramètres',
                'description' => 'Permet de modifier les paramètres de la plateforme (logo, footer, etc.)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'manage_admins',
                'name' => 'Gérer les administrateurs',
                'description' => 'Permet de créer et gérer les autres administrateurs (réservé aux super admins)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('permissions')->insert($permissions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->whereIn('key', [
            'manage_users',
            'manage_plans',
            'manage_subscriptions',
            'manage_payments',
            'manage_audios',
            'manage_logs',
            'manage_settings',
            'manage_admins',
        ])->delete();
    }
};
