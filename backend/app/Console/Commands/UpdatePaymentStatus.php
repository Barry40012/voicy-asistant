<?php

namespace App\Console\Commands;

use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpdatePaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:mark-successful {payment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marquer un paiement comme réussi, activer l\'abonnement et envoyer l\'email de reçu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paymentId = $this->argument('payment_id');
        
        // Vérifier que l'ID est valide
        if (!is_numeric($paymentId)) {
            $this->error("❌ Erreur : L'ID du paiement doit être un nombre");
            $this->line("");
            $this->info("💡 Utilisation correcte :");
            $this->line("   php artisan payment:mark-successful 10");
            $this->line("");
            $this->info("💡 Pour voir les IDs disponibles, utilise :");
            $this->line("   php artisan payment:diagnose email@example.com");
            return 1;
        }
        
        $payment = Payment::with('user')->find($paymentId);
        
        if (!$payment) {
            $this->error("❌ Paiement #{$paymentId} non trouvé");
            $this->line("");
            $this->info("💡 Pour voir les paiements disponibles, utilise :");
            $this->line("   php artisan payment:diagnose email@example.com");
            return 1;
        }

        $this->info("💳 Paiement trouvé:");
        $this->line("  - ID: {$payment->id}");
        $this->line("  - Montant: {$payment->amount} {$payment->currency}");
        $this->line("  - Statut actuel: {$payment->status}");
        $this->line("  - Utilisateur: {$payment->user->name} ({$payment->user->email})");
        $this->newLine();

        if ($payment->status === 'succeeded') {
            $this->warn("⚠️  Ce paiement est déjà marqué comme réussi");
            if (!$this->confirm('Voulez-vous quand même réactiver l\'abonnement et renvoyer l\'email ?', false)) {
                return 0;
            }
        }

        // Mettre à jour le statut du paiement
        $payment->update(['status' => 'succeeded']);
        $this->info("✅ Paiement marqué comme réussi");

        // Activer l'abonnement
        $metadata = $payment->metadata ?? [];
        $planId = $metadata['plan_id'] ?? null;
        $subscriptionId = $metadata['subscription_id'] ?? null;

        $subscription = null;
        if ($subscriptionId) {
            $subscription = Subscription::with('plan')->find($subscriptionId);
        } elseif ($planId) {
            $subscription = Subscription::with('plan')
                ->where('user_id', $payment->user_id)
                ->where('plan_id', $planId)
                ->where('status', '!=', 'active')
                ->orderBy('created_at', 'desc')
                ->first();
        }

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
            
            // Désactiver tous les autres abonnements actifs de l'utilisateur
            $cancelledCount = Subscription::where('user_id', $payment->user_id)
                ->where('id', '!=', $subscription->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);
            
            if ($cancelledCount > 0) {
                $this->info("🔄 {$cancelledCount} ancien(s) abonnement(s) désactivé(s)");
                if ($remainingDays > 0) {
                    $this->info("  ⏰ Temps restant de l'ancien abonnement : {$remainingDays} jour(s)");
                }
            }
            
            // Calculer la nouvelle date d'expiration avec le temps restant
            $newExpiresAt = now()->addMonth();
            if ($remainingDays > 0) {
                // Ajouter les jours restants au nouveau plan (maximum 1 mois supplémentaire)
                $additionalDays = min($remainingDays, 30);
                $newExpiresAt = now()->addMonth()->addDays($additionalDays);
                $this->info("  ✅ {$additionalDays} jour(s) ajouté(s) au nouvel abonnement");
            }
            
            // Activer le nouvel abonnement avec le temps calculé
            $subscription->update([
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => $newExpiresAt,
            ]);
            $this->info("✅ Abonnement #{$subscription->id} activé (Plan: {$subscription->plan->name})");
        } else {
            $this->warn("⚠️  Aucun abonnement trouvé pour ce paiement");
        }

        // Envoyer l'email de reçu
        if ($subscription) {
            try {
                $payment->load('user');
                $subscription->load('plan');
                Mail::to($payment->user->email)->send(new PaymentReceiptMail($payment, $subscription));
                $this->info("📧 Email de reçu envoyé à {$payment->user->email}");
            } catch (\Exception $e) {
                $this->error("❌ Erreur envoi email: " . $e->getMessage());
                Log::error('Failed to send payment receipt email', [
                    'error' => $e->getMessage(),
                    'payment_id' => $payment->id,
                ]);
            }
        }

        $this->newLine();
        $this->info("✅ Terminé ! L'utilisateur devrait maintenant voir le badge sur le dashboard.");

        return 0;
    }
}
