<?php

namespace App\Services;

use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymentAdapters\PaymentAdapterInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    /**
     * Process payment with provider
     */
    public function processPayment(
        PaymentAdapterInterface $adapter,
        User $user,
        float $amount,
        string $currency,
        array $metadata = []
    ): ?Payment {
        try {
            $payment = $adapter->createPayment($user, $amount, $currency, $metadata);

            if ($payment) {
                $paymentMetadata = array_merge($metadata, [
                    'payment_link' => $payment['payment_link'] ?? null,
                    'tx_ref' => $payment['tx_ref'] ?? $payment['id'],
                ]);

                return Payment::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'currency' => $currency,
                    'provider' => $adapter->getProviderName(),
                    'provider_payment_id' => $payment['id'] ?? $payment['tx_ref'] ?? null,
                    'status' => $payment['status'] ?? 'pending',
                    'metadata' => $paymentMetadata,
                ]);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error processing payment', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Handle webhook from payment provider
     */
    public function handleWebhook(PaymentAdapterInterface $adapter, array $payload): bool
    {
        try {
            $paymentData = $adapter->verifyWebhook($payload);

            if (!$paymentData) {
                return false;
            }

            $payment = Payment::with('user')
                ->where('provider_payment_id', $paymentData['id'])
                ->where('provider', $adapter->getProviderName())
                ->first();

            if (!$payment) {
                return false;
            }

            $payment->update([
                'status' => $paymentData['status'],
                'metadata' => array_merge($payment->metadata ?? [], $paymentData['metadata'] ?? []),
            ]);

            // If payment succeeded, activate subscription
            if ($paymentData['status'] === 'succeeded' && $payment->status !== 'succeeded') {
                $this->activateSubscription($payment);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error handling payment webhook', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Activate subscription after successful payment
     */
    private function activateSubscription(Payment $payment): void
    {
        $metadata = $payment->metadata ?? [];
        $planId = $metadata['plan_id'] ?? null;

        if (!$planId) {
            return;
        }

        $subscription = Subscription::with('plan')
            ->where('user_id', $payment->user_id)
            ->where('plan_id', $planId)
            ->where('status', '!=', 'active')
            ->first();

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
            Subscription::where('user_id', $payment->user_id)
                ->where('id', '!=', $subscription->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);
            
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

            // Recharger les relations pour l'email
            $payment->load('user');
            $subscription->load('plan');
            
            // Envoyer l'email de reçu
            try {
                Mail::to($payment->user->email)->send(new PaymentReceiptMail($payment, $subscription));
                Log::info('Payment receipt email sent via webhook', [
                    'user_id' => $payment->user->id,
                    'user_email' => $payment->user->email,
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'plan_name' => $subscription->plan->name,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send payment receipt email via webhook', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'user_id' => $payment->user->id ?? 'unknown',
                    'user_email' => $payment->user->email ?? 'unknown',
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id ?? 'unknown',
                ]);
            }
        }
    }
}
