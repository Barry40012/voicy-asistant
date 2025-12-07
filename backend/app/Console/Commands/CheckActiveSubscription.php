<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckActiveSubscription extends Command
{
    protected $signature = 'subscription:check {email}';
    protected $description = 'Vérifier l\'abonnement actif d\'un utilisateur';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur non trouvé: {$email}");
            return 1;
        }

        $this->info("👤 Utilisateur: {$user->name} ({$user->email})");
        $this->newLine();

        // Tous les abonnements
        $all = $user->subscriptions()->with('plan')->orderBy('created_at', 'desc')->get();
        $this->info("📋 Tous les abonnements ({$all->count()}):");
        foreach ($all as $s) {
            $statusIcon = $s->status === 'active' ? '✅' : ($s->status === 'cancelled' ? '❌' : '⏳');
            $this->line("  {$statusIcon} ID: {$s->id} | Plan: {$s->plan->name} | Status: {$s->status} | Créé: {$s->created_at->format('d/m/Y H:i')}");
        }

        $this->newLine();

        // Abonnement actif via activeSubscription()
        $active = $user->activeSubscription();
        if ($active) {
            $this->info("✅ Abonnement actif (via activeSubscription()):");
            $this->line("  Plan: {$active->plan->name}");
            $this->line("  ID: {$active->id}");
            $this->line("  Créé: {$active->created_at->format('d/m/Y H:i')}");
        } else {
            $this->warn("⚠️  Aucun abonnement actif trouvé");
        }

        return 0;
    }
}

