<?php

namespace App\Console\Commands;

use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ActivateSubscriptionsFromPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:activate-subscriptions {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activer les abonnements pour les paiements réussis et envoyer les emails de reçu';

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

        $this->info("🔄 Activation des abonnements pour les paiements réussis...");
        $this->newLine();

        $activated = 0;
        $emailsSent = 0;

        foreach ($users as $user) {
            // Trouver les paiements réussis
            $successfulPayments = Payment::where('user_id', $user->id)
                ->where('status', 'succeeded')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($successfulPayments->isEmpty()) {
                continue;
            }

            $this->info("👤 Utilisateur: {$user->name} ({$user->email})");
            $this->line("  Paiements réussis: {$successfulPayments->count()}");

            foreach ($successfulPayments as $payment) {
                $metadata = $payment->metadata ?? [];
                $planId = $metadata['plan_id'] ?? null;
                $subscriptionId = $metadata['subscription_id'] ?? null;

                if (!$planId && !$subscriptionId) {
                    continue;
                }

                // Trouver ou créer l'abonnement
                $subscription = null;
                if ($subscriptionId) {
                    $subscription = Subscription::with('plan')->find($subscriptionId);
                } else {
                    // Chercher un abonnement en attente pour ce plan
                    $subscription = Subscription::with('plan')
                        ->where('user_id', $user->id)
                        ->where('plan_id', $planId)
                        ->where('status', '!=', 'active')
                        ->orderBy('created_at', 'desc')
                        ->first();
                }

                if (!$subscription) {
                    $this->warn("  ⚠️  Abonnement non trouvé pour le paiement #{$payment->id}");
                    continue;
                }

                // Activer l'abonnement s'il n'est pas déjà actif
                if ($subscription->status !== 'active') {
                    // Récupérer l'ancien abonnement actif pour calculer le temps restant
                    $oldSubscription = Subscription::where('user_id', $user->id)
                        ->where('id', '!=', $subscription->id)
                        ->where('status', 'active')
                        ->with('plan')
                        ->first();
                    
                    // Calculer le temps restant de l'ancien abonnement (en jours)
                    $remainingDays = 0;
                    if ($oldSubscription && $oldSubscription->expires_at->isFuture()) {
                        $remainingDays = max(0, now()->diffInDays($oldSubscription->expires_at, false));
                    }
                    
                    // Désactiver tous les autres abonnements actifs de l'utilisateur
                    $cancelledCount = Subscription::where('user_id', $user->id)
                        ->where('id', '!=', $subscription->id)
                        ->where('status', 'active')
                        ->update(['status' => 'cancelled']);
                    
                    if ($cancelledCount > 0) {
                        $this->line("  🔄 {$cancelledCount} ancien(s) abonnement(s) désactivé(s)");
                        if ($remainingDays > 0) {
                            $this->line("  ⏰ Temps restant : {$remainingDays} jour(s)");
                        }
                    }
                    
                    // Calculer la nouvelle date d'expiration avec le temps restant
                    $newExpiresAt = now()->addMonth();
                    if ($remainingDays > 0) {
                        // Ajouter les jours restants au nouveau plan (maximum 1 mois supplémentaire)
                        $additionalDays = min($remainingDays, 30);
                        $newExpiresAt = now()->addMonth()->addDays($additionalDays);
                    }
                    
                    // Activer le nouvel abonnement avec le temps calculé
                    $subscription->update([
                        'status' => 'active',
                        'started_at' => now(),
                        'expires_at' => $newExpiresAt,
                    ]);
                    $activated++;
                    $this->info("  ✅ Abonnement #{$subscription->id} activé (Plan: {$subscription->plan->name})");
                }

                // Envoyer l'email de reçu
                try {
                    $payment->load('user');
                    $subscription->load('plan');
                    Mail::to($user->email)->send(new PaymentReceiptMail($payment, $subscription));
                    $emailsSent++;
                    $this->info("  📧 Email de reçu envoyé à {$user->email}");
                } catch (\Exception $e) {
                    $this->error("  ❌ Erreur envoi email: " . $e->getMessage());
                    Log::error('Failed to send payment receipt email', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'payment_id' => $payment->id,
                    ]);
                }
            }

            $this->newLine();
        }

        $this->info("✅ Résumé:");
        $this->line("  - Abonnements activés: {$activated}");
        $this->line("  - Emails envoyés: {$emailsSent}");

        return 0;
    }
}
