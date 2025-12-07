<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Audio;
use Illuminate\Console\Command;

class ListUsersToDelete extends Command
{
    protected $signature = 'users:list-to-delete {--keep-admins : Garder les comptes administrateurs}';
    protected $description = 'Afficher la liste des utilisateurs qui seront supprimés';

    public function handle()
    {
        $keepAdmins = $this->option('keep-admins');

        $query = User::query();
        if ($keepAdmins) {
            $query->whereNotIn('role', ['admin', 'super_admin']);
        }
        $users = $query->orderBy('created_at', 'desc')->get();

        if ($users->isEmpty()) {
            $this->info("✅ Aucun utilisateur à supprimer.");
            return 0;
        }

        $this->info("📋 Liste des utilisateurs qui seront supprimés ({$users->count()}):");
        $this->newLine();

        $headers = ['ID', 'Nom', 'Email', 'Rôle', 'Créé le', 'Abonnements', 'Paiements', 'Audios'];
        $rows = [];

        foreach ($users as $user) {
            $subscriptionsCount = $user->subscriptions()->count();
            $paymentsCount = $user->payments()->count();
            $audiosCount = $user->audios()->count();

            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->role ?? 'user',
                $user->created_at->format('d/m/Y H:i'),
                $subscriptionsCount,
                $paymentsCount,
                $audiosCount,
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("💡 Pour supprimer tous ces utilisateurs, utilisez :");
        $this->line("   php artisan users:reset-all" . ($keepAdmins ? " --keep-admins" : ""));

        return 0;
    }
}

