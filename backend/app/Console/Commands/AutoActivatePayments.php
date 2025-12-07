<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PaymentAdapters\FlutterwaveAdapter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoActivatePayments extends Command
{
    protected $signature = 'payments:auto-activate {email?}';
    protected $description = 'Vérifier automatiquement les paiements en attente et activer les abonnements';

    public function handle()
    {
        $email = $this->argument('email');
        
        $query = Payment::where('status', 'pending')
            ->where('provider', 'flutterwave')
            ->with('user')
            ->orderBy('created_at', 'desc');
            
        if ($email) {
            $user = \App\Models\User::where('email', $email)->first();
            if (!$user) {
                $this->error("❌ Utilisateur non trouvé: {$email}");
                return 1;
            }
            $query->where('user_id', $user->id);
        }
        
        $pendingPayments = $query->get();
        
        if ($pendingPayments->isEmpty()) {
            $this->info("✅ Aucun paiement en attente trouvé.");
            return 0;
        }
        
        $this->info("🔍 Vérification de {$pendingPayments->count()} paiement(s) en attente...");
        $this->newLine();
        
        $adapter = new FlutterwaveAdapter();
        $activated = 0;
        $failed = 0;
        
        foreach ($pendingPayments as $payment) {
            $txRef = $payment->provider_payment_id;
            
            if (!$txRef) {
                $this->warn("  ⚠️  Paiement #{$payment->id} : Pas de tx_ref");
                continue;
            }
            
            $this->line("  🔄 Vérification du paiement #{$payment->id} (tx_ref: {$txRef})...");
            
            try {
                // Vérifier la transaction avec Flutterwave
                $transaction = $adapter->verifyTransaction($txRef);
                
                if ($transaction && in_array(strtolower($transaction['status'] ?? ''), ['successful', 'success', 'succeeded', 'completed'])) {
                    // Paiement réussi !
                    $this->info("  ✅ Paiement réussi détecté !");
                    
                    // Mettre à jour le statut du paiement
                    $payment->update([
                        'status' => 'succeeded',
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'verified_at' => now()->toDateTimeString(),
                            'transaction_status' => $transaction['status'],
                        ]),
                    ]);
                    
                    // Activer l'abonnement
                    $metadata = $payment->metadata ?? [];
                    $subscriptionId = $metadata['subscription_id'] ?? null;
                    $planId = $metadata['plan_id'] ?? null;
                    
                    if ($subscriptionId) {
                        $subscription = Subscription::with('plan')->find($subscriptionId);
                        
                        if ($subscription) {
                            // Récupérer l'ancien abonnement actif pour calculer le temps restant
                            $oldSubscription = Subscription::where('user_id', $payment->user_id)
                                ->where('id', '!=', $subscription->id)
                                ->where('status', 'active')
                                ->with('plan')
                                ->first();
                            
                            // Calculer le temps restant de l'ancien abonnement (en jours)
                            $remainingDays = 0;
                            if ($oldSubscription && $oldSubscription->expires_at->isFuture()) {
                                $remainingDays = max(0, now()->diffInDays($oldSubscription->expires_at, false));
                            }
                            
                            // Désactiver tous les autres abonnements actifs
                            $cancelledCount = Subscription::where('user_id', $payment->user_id)
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
                            
                            $this->info("  ✅ Abonnement #{$subscription->id} activé (Plan: {$subscription->plan->name})");
                            $activated++;
                            
                            // Envoyer l'email de reçu
                            try {
                                $payment->load('user');
                                $subscription->load('plan');
                                \Illuminate\Support\Facades\Mail::to($payment->user->email)
                                    ->send(new \App\Mail\PaymentReceiptMail($payment, $subscription));
                                $this->line("  📧 Email de reçu envoyé");
                            } catch (\Exception $e) {
                                $this->warn("  ⚠️  Erreur envoi email: " . $e->getMessage());
                            }
                        } else {
                            $this->warn("  ⚠️  Abonnement #{$subscriptionId} non trouvé");
                        }
                    } else {
                        $this->warn("  ⚠️  Pas de subscription_id dans les métadonnées");
                    }
                } else {
                    $this->line("  ⏳ Paiement toujours en attente (statut: " . ($transaction['status'] ?? 'unknown') . ")");
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Erreur: " . $e->getMessage());
                $failed++;
                Log::error('Auto activate payment error', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
            
            $this->newLine();
        }
        
        $this->info("✅ Résumé:");
        $this->line("  - Paiements vérifiés: {$pendingPayments->count()}");
        $this->line("  - Abonnements activés: {$activated}");
        if ($failed > 0) {
            $this->line("  - Erreurs: {$failed}");
        }
        
        return 0;
    }
}

