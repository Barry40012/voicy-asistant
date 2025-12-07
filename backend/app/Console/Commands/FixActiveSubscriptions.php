<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;

class FixActiveSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:fix-active {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les abonnements actifs : garder seulement le plus récent actif pour chaque utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("❌ Utilisateur non trouvé: {$email}");
                return 1;
            }
            $users = collect([$user]);
        } else {
            $users = User::all();
        }

        $this->info("🔧 Correction des abonnements actifs...");
        $this->newLine();

        $fixed = 0;

        foreach ($users as $user) {
            // Trouver tous les abonnements actifs de l'utilisateur
            $activeSubscriptions = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->with('plan')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($activeSubscriptions->count() <= 1) {
                continue; // Pas de problème, un seul ou aucun abonnement actif
            }

            $this->info("👤 Utilisateur: {$user->name} ({$user->email})");
            $this->line("  ⚠️  {$activeSubscriptions->count()} abonnement(s) actif(s) trouvé(s)");

            // Garder le plus récent actif
            $mostRecent = $activeSubscriptions->first();
            $this->line("  ✅ Garde actif: Plan {$mostRecent->plan->name} (ID: {$mostRecent->id}, créé le {$mostRecent->created_at->format('d/m/Y H:i')})");

            // Désactiver tous les autres
            $others = $activeSubscriptions->skip(1);
            foreach ($others as $subscription) {
                $subscription->update(['status' => 'cancelled']);
                $this->line("  🔄 Désactivé: Plan {$subscription->plan->name} (ID: {$subscription->id}, créé le {$subscription->created_at->format('d/m/Y H:i')})");
            }

            $fixed++;
            $this->newLine();
        }

        if ($fixed === 0) {
            $this->info("✅ Aucun problème détecté. Tous les utilisateurs ont au maximum un abonnement actif.");
        } else {
            $this->info("✅ Correction terminée pour {$fixed} utilisateur(s) !");
        }

        return 0;
    }
}

