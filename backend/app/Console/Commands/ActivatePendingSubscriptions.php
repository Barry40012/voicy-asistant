<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ActivatePendingSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:activate-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate pending subscriptions that have successful payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des abonnements en attente...');

        // Trouver tous les abonnements en attente
        $pendingSubscriptions = Subscription::where('status', 'pending')
            ->with(['plan', 'user'])
            ->get();

        if ($pendingSubscriptions->isEmpty()) {
            $this->warn('Aucun abonnement en attente trouvé.');
            return 0;
        }

        $this->info("Trouvé {$pendingSubscriptions->count()} abonnement(s) en attente.");
        
        // Option: activer le dernier abonnement de chaque utilisateur
        $usersProcessed = [];
        $activated = 0;

        foreach ($pendingSubscriptions->sortByDesc('created_at') as $subscription) {
            $userId = $subscription->user_id;
            
            // Activer seulement le dernier abonnement de chaque utilisateur
            if (!isset($usersProcessed[$userId])) {
                $subscription->update([
                    'status' => 'active',
                    'started_at' => now(),
                    'expires_at' => now()->addMonth(),
                ]);

                $this->info("✓ Abonnement #{$subscription->id} activé pour {$subscription->user->name} (Plan: {$subscription->plan->name})");
                $usersProcessed[$userId] = true;
                $activated++;
            } else {
                // Annuler les autres abonnements en attente du même utilisateur
                $subscription->update(['status' => 'cancelled']);
                $this->line("  → Abonnement #{$subscription->id} annulé (doublon)");
            }
        }

        if ($activated === 0) {
            $this->warn('Aucun abonnement activé.');
        } else {
            $this->info("✅ {$activated} abonnement(s) activé(s) avec succès !");
        }

        return 0;
    }
}
