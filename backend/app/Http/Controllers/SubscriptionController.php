<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use App\Models\Plan;
use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PaymentService;
use App\Services\PaymentAdapters\FlutterwaveAdapter;
use App\Services\PaymentAdapters\StripeAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Show subscription page
     */
    public function index()
    {
        $plans = Plan::where('is_active', true)->get();
        $currentSubscription = Auth::user()->activeSubscription();
        
        // Vérifier s'il y a des paiements en attente et les vérifier automatiquement
        $pendingPayments = Payment::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('provider', 'flutterwave')
            ->orderBy('created_at', 'desc')
            ->get();

        // Vérifier automatiquement les paiements en attente au chargement de la page
        if ($pendingPayments->isNotEmpty()) {
            $this->autoCheckPendingPayments($pendingPayments);
            
            // Recharger les données après vérification
            $currentSubscription = Auth::user()->activeSubscription();
            $pendingPayments = Payment::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->where('provider', 'flutterwave')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('dashboard.subscription.index', compact('plans', 'currentSubscription', 'pendingPayments'));
    }

    /**
     * Vérifier automatiquement les paiements en attente (méthode privée)
     */
    private function autoCheckPendingPayments($pendingPayments)
    {
        $adapter = new FlutterwaveAdapter();

        foreach ($pendingPayments as $payment) {
            $txRef = $payment->provider_payment_id;
            
            if (!$txRef) {
                continue;
            }

            try {
                // Vérifier la transaction avec Flutterwave
                $transaction = $adapter->verifyTransaction($txRef);
                
                $transactionStatus = strtolower($transaction['status'] ?? '');
                $successStatuses = ['successful', 'success', 'succeeded', 'completed', 'paid'];
                
                if ($transaction && in_array($transactionStatus, $successStatuses)) {
                    // Paiement réussi - activer automatiquement
                    $this->activatePaymentFromCheck($payment, $transaction);
                }
            } catch (\Exception $e) {
                Log::debug('Auto-check payment failed (silent)', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Activer un paiement et son abonnement (méthode privée pour auto-check)
     */
    private function activatePaymentFromCheck(Payment $payment, array $transaction = [])
    {
        // Mettre à jour le statut du paiement
        $payment->update([
            'status' => 'succeeded',
            'metadata' => array_merge($payment->metadata ?? [], [
                'verified_at' => now()->toDateTimeString(),
                'transaction_status' => $transaction['status'] ?? 'succeeded',
                'verified_by' => 'auto_check',
            ]),
        ]);

        // Activer l'abonnement
        $metadata = $payment->metadata ?? [];
        $subscriptionId = $metadata['subscription_id'] ?? null;

        if ($subscriptionId) {
            $subscription = Subscription::with('plan')->find($subscriptionId);

            if ($subscription && $subscription->status !== 'active') {
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

                Log::info('Subscription auto-activated from page load', [
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'plan' => $subscription->plan->name,
                ]);

                // Envoyer l'email de reçu
                try {
                    $payment->load('user');
                    $subscription->load('plan');
                    Mail::to($payment->user->email)->send(new PaymentReceiptMail($payment, $subscription));
                } catch (\Exception $e) {
                    Log::error('Failed to send payment receipt email', [
                        'error' => $e->getMessage(),
                        'payment_id' => $payment->id,
                    ]);
                }
            }
        }
    }

    /**
     * Vérifier et activer automatiquement les paiements en attente
     */
    public function checkPendingPayments()
    {
        $pendingPayments = Payment::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('provider', 'flutterwave')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($pendingPayments->isEmpty()) {
            return back()->with('info', 'Aucun paiement en attente à vérifier.');
        }

        $adapter = new FlutterwaveAdapter();
        $activated = 0;
        $activatedSubscriptions = [];

        foreach ($pendingPayments as $payment) {
            $txRef = $payment->provider_payment_id;
            
            if (!$txRef) {
                continue;
            }

            try {
                // Vérifier la transaction avec Flutterwave
                $transaction = $adapter->verifyTransaction($txRef);
                
                Log::info('Payment verification result', [
                    'payment_id' => $payment->id,
                    'tx_ref' => $txRef,
                    'transaction' => $transaction,
                    'transaction_status' => $transaction['status'] ?? 'null',
                ]);
                
                // Vérifier le statut de la transaction
                $transactionStatus = strtolower($transaction['status'] ?? '');
                $successStatuses = ['successful', 'success', 'succeeded', 'completed', 'paid'];
                
                // Vérifier aussi dans les métadonnées du paiement
                $metadata = $payment->metadata ?? [];
                $callbackStatus = strtolower($metadata['callback_status'] ?? '');
                
                if (($transaction && in_array($transactionStatus, $successStatuses)) 
                    || in_array($callbackStatus, $successStatuses)) {
                    // Paiement réussi !
                    $payment->update([
                        'status' => 'succeeded',
                        'metadata' => array_merge($metadata, [
                            'verified_at' => now()->toDateTimeString(),
                            'transaction_status' => $transaction['status'] ?? $callbackStatus,
                            'verified_by' => 'manual_check',
                        ]),
                    ]);
                    
                    // Activer l'abonnement
                    $subscriptionId = $metadata['subscription_id'] ?? null;
                    
                    if ($subscriptionId) {
                        $subscription = Subscription::with('plan')->find($subscriptionId);
                        
                        if ($subscription) {
                            // Récupérer l'ancien abonnement actif pour calculer le temps restant
                            $oldSubscription = Subscription::where('user_id', Auth::id())
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
                            Subscription::where('user_id', Auth::id())
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
                            
                            $activated++;
                            $activatedSubscriptions[] = $subscription->plan->name;
                            
                            Log::info('Subscription activated from pending payment check', [
                                'payment_id' => $payment->id,
                                'subscription_id' => $subscription->id,
                                'plan' => $subscription->plan->name,
                            ]);
                            
                            // Envoyer l'email de reçu
                            try {
                                $payment->load('user');
                                $subscription->load('plan');
                                Mail::to($payment->user->email)->send(new PaymentReceiptMail($payment, $subscription));
                            } catch (\Exception $e) {
                                Log::error('Failed to send payment receipt email', [
                                    'error' => $e->getMessage(),
                                    'payment_id' => $payment->id,
                                ]);
                            }
                        } else {
                            Log::warning('Subscription not found for payment', [
                                'payment_id' => $payment->id,
                                'subscription_id' => $subscriptionId,
                            ]);
                        }
                    } else {
                        Log::warning('No subscription_id in payment metadata', [
                            'payment_id' => $payment->id,
                            'metadata' => $metadata,
                        ]);
                    }
                } else {
                    Log::info('Payment still pending', [
                        'payment_id' => $payment->id,
                        'transaction_status' => $transactionStatus,
                        'callback_status' => $callbackStatus,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error checking pending payment', [
                    'payment_id' => $payment->id,
                    'tx_ref' => $txRef,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        // Si c'est une requête AJAX, retourner JSON
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => $activated > 0,
                'activated' => $activated,
                'subscriptions' => $activatedSubscriptions,
                'message' => $activated > 0 
                    ? "🎉 Félicitations ! {$activated} abonnement(s) activé(s) : " . implode(', ', $activatedSubscriptions)
                    : "Aucun paiement n'a pu être activé. Les paiements sont peut-être toujours en cours de traitement."
            ]);
        }

        if ($activated > 0) {
            $planNames = implode(', ', $activatedSubscriptions);
            return back()->with('success', "🎉 Félicitations ! {$activated} abonnement(s) activé(s) : {$planNames}. Un email de reçu a été envoyé.");
        } else {
            // Ne pas afficher de message d'erreur, la vérification continue en arrière-plan
            return back();
        }
    }

    /**
     * Activer manuellement un paiement (si la vérification automatique échoue)
     */
    public function activatePayment(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur
        if ($payment->user_id !== Auth::id()) {
            return back()->withErrors(['payment' => 'Ce paiement ne vous appartient pas.']);
        }

        // Vérifier que le paiement est en attente
        if ($payment->status !== 'pending') {
            return back()->with('info', 'Ce paiement a déjà été traité.');
        }

        // Mettre à jour le statut du paiement
        $payment->update([
            'status' => 'succeeded',
            'metadata' => array_merge($payment->metadata ?? [], [
                'activated_manually_at' => now()->toDateTimeString(),
                'activated_by' => Auth::id(),
            ]),
        ]);

        // Activer l'abonnement
        $metadata = $payment->metadata ?? [];
        $subscriptionId = $metadata['subscription_id'] ?? null;

        if ($subscriptionId) {
            $subscription = Subscription::with('plan')->find($subscriptionId);

            if ($subscription) {
                // Récupérer l'ancien abonnement actif pour calculer le temps restant
                $oldSubscription = Subscription::where('user_id', Auth::id())
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
                Subscription::where('user_id', Auth::id())
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

                Log::info('Payment and subscription activated manually', [
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'user_id' => Auth::id(),
                ]);

                // Envoyer l'email de reçu
                try {
                    $payment->load('user');
                    $subscription->load('plan');
                    Mail::to($payment->user->email)->send(new PaymentReceiptMail($payment, $subscription));
                } catch (\Exception $e) {
                    Log::error('Failed to send payment receipt email', [
                        'error' => $e->getMessage(),
                        'payment_id' => $payment->id,
                    ]);
                }

                return back()->with('success', "🎉 Félicitations ! Votre abonnement {$subscription->plan->name} est maintenant actif ! Un email de reçu a été envoyé.");
            }
        }

        return back()->withErrors(['payment' => 'Impossible d\'activer l\'abonnement. Abonnement introuvable.']);
    }

    /**
     * Subscribe to a plan
     */
    public function subscribe(Request $request, Plan $plan)
    {
        // Vérifier si le plan est gratuit
        if ($plan->isFree()) {
            // Désactiver l'abonnement actuel s'il existe
            Subscription::where('user_id', Auth::id())
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // Créer et activer directement l'abonnement gratuit
            $subscription = Subscription::create([
                'user_id' => Auth::id(),
                'plan_id' => $plan->id,
                'started_at' => now(),
                'expires_at' => now()->addMonth(),
                'status' => 'active',
            ]);

            Log::info('Free subscription activated', [
                'user_id' => Auth::id(),
                'plan_id' => $plan->id,
                'subscription_id' => $subscription->id,
            ]);

            return redirect()->route('dashboard')
                ->with('success', "🎉 Félicitations ! Votre abonnement {$plan->name} est maintenant actif !");
        }

        // Pour les plans payants, continuer avec le flux de paiement
        // Create subscription record
        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
            'status' => 'pending',
        ]);

        // Get default payment provider from DB
        $defaultProvider = PaymentProvider::getDefault();
        
        if (!$defaultProvider) {
            return back()->withErrors(['payment' => 'Aucun provider de paiement configuré. Veuillez contacter l\'administrateur.']);
        }

        // Vérifier que le provider est actif
        if (!$defaultProvider->is_active) {
            Log::warning('Inactive payment provider used', [
                'provider_id' => $defaultProvider->id,
                'provider_name' => $defaultProvider->name,
            ]);
            // On continue quand même si c'est le provider par défaut
        }

        // Vérifier que le provider a au moins une clé configurée
        $hasCredentials = false;
        $configuredKeys = [];
        foreach ($defaultProvider->credentials ?? [] as $key => $value) {
            if (!empty($value)) {
                $hasCredentials = true;
                $configuredKeys[] = $key;
            }
        }

        if (!$hasCredentials) {
            return back()->withErrors(['payment' => 'Le provider de paiement "' . $defaultProvider->display_name . '" n\'a pas de clés API configurées. Veuillez aller dans Admin > Paiements pour configurer les clés.']);
        }

        Log::info('Using payment provider', [
            'provider_id' => $defaultProvider->id,
            'provider_name' => $defaultProvider->name,
            'is_active' => $defaultProvider->is_active,
            'is_default' => $defaultProvider->is_default,
            'configured_keys' => $configuredKeys,
        ]);

        // Create adapter based on provider name
        $adapter = $this->createAdapter($defaultProvider->name);
        
        if (!$adapter) {
            return back()->withErrors(['payment' => 'Provider de paiement non supporté.']);
        }

        // Use NGN for test mode (Flutterwave test mode supports NGN better)
        // Convert EUR to NGN for testing (1 EUR ≈ 1500 NGN)
        $currency = $plan->price_currency ?? 'NGN';
        $amount = $plan->price_monthly;
        
        // Convert EUR to NGN if needed for testing
        if ($currency === 'EUR' && config('app.env') === 'local') {
            $currency = 'NGN';
            $amount = $amount * 1500; // Approximate conversion for testing
        }
        
        $payment = $this->paymentService->processPayment(
            $adapter,
            Auth::user(),
            $amount,
            $currency,
            ['plan_id' => $plan->id, 'subscription_id' => $subscription->id]
        );

        if ($payment && isset($payment->metadata['payment_link'])) {
            // Redirect to Flutterwave payment page
            return redirect($payment->metadata['payment_link']);
        }

        return back()->withErrors(['payment' => 'Erreur lors de la création du paiement. Veuillez réessayer.']);
    }

    /**
     * Handle Flutterwave payment callback
     */
    public function callback(Request $request, string $provider)
    {
        if ($provider !== 'flutterwave') {
            return redirect()->route('dashboard.subscription.index')
                ->withErrors(['payment' => 'Provider non supporté']);
        }

        $txRef = $request->query('tx_ref');
        $status = $request->query('status');
        $transactionId = $request->query('transaction_id');

        Log::info('Flutterwave callback received', [
            'tx_ref' => $txRef,
            'status' => $status,
            'transaction_id' => $transactionId,
            'all_params' => $request->all(),
        ]);

        if (!$txRef) {
            return redirect()->route('dashboard.subscription.index')
                ->withErrors(['payment' => 'Référence de transaction manquante']);
        }

        // Find payment by tx_ref
        $payment = Payment::with('user')
            ->where('provider', 'flutterwave')
            ->where('provider_payment_id', $txRef)
            ->first();

        if (!$payment) {
            Log::warning('Payment not found', ['tx_ref' => $txRef]);
            return redirect()->route('dashboard.subscription.index')
                ->withErrors(['payment' => 'Paiement introuvable']);
        }

        // Try to verify transaction with Flutterwave API (but don't fail if it doesn't work)
        $adapter = new FlutterwaveAdapter();
        $transaction = null;
        try {
            $transaction = $adapter->verifyTransaction($txRef);
        } catch (\Exception $e) {
            Log::warning('Transaction verification failed, but continuing with URL status', [
                'error' => $e->getMessage(),
                'tx_ref' => $txRef,
            ]);
        }

        // Vérifier le statut depuis plusieurs sources pour être plus permissif
        $failedStatuses = ['failed', 'cancelled', 'error', 'declined', 'rejected'];
        $successStatuses = ['successful', 'success', 'completed', 'succeeded', 'paid'];
        
        $urlStatus = strtolower($status ?? '');
        $apiStatus = $transaction ? strtolower($transaction['status'] ?? '') : '';
        
        // Si le statut de l'URL n'est pas un échec explicite, considérer comme succès (plus permissif)
        $isUrlSuccessful = $status && !in_array($urlStatus, $failedStatuses);
        
        // Si le statut de l'API est dans la liste des succès
        $isApiSuccessful = $apiStatus && in_array($apiStatus, $successStatuses);
        
        // Si le paiement est déjà marqué comme réussi
        $isAlreadySucceeded = $payment->status === 'succeeded';
        
        $isSuccessful = $isUrlSuccessful || $isApiSuccessful || $isAlreadySucceeded;

        Log::info('Flutterwave callback processing', [
            'tx_ref' => $txRef,
            'status_from_url' => $status,
            'url_status_lower' => $urlStatus,
            'payment_current_status' => $payment->status,
            'is_url_successful' => $isUrlSuccessful,
            'api_status' => $apiStatus,
            'is_api_successful' => $isApiSuccessful,
            'transaction_verified' => $transaction ? true : false,
            'is_successful' => $isSuccessful,
            'payment_id' => $payment->id,
        ]);

        if ($isSuccessful) {
            // Update payment status
            $payment->update([
                'status' => 'succeeded',
                'metadata' => array_merge($payment->metadata ?? [], $transaction['metadata'] ?? [], [
                    'callback_status' => $status,
                    'transaction_id' => $transactionId,
                    'verified_at' => now()->toDateTimeString(),
                ]),
            ]);

            // Activate subscription
            $metadata = $payment->metadata ?? [];
            $planId = $metadata['plan_id'] ?? null;
            $subscriptionId = $metadata['subscription_id'] ?? null;

            Log::info('Activating subscription', [
                'subscription_id' => $subscriptionId,
                'plan_id' => $planId,
                'metadata' => $metadata,
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
            ]);

            if ($subscriptionId) {
                $subscription = Subscription::with('plan')->find($subscriptionId);
                if ($subscription) {
                    // Récupérer l'ancien abonnement actif pour calculer le temps restant
                    $oldSubscription = Subscription::where('user_id', $subscription->user_id)
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
                    $cancelledCount = Subscription::where('user_id', $subscription->user_id)
                        ->where('id', '!=', $subscription->id)
                        ->where('status', 'active')
                        ->update(['status' => 'cancelled']);
                    
                    Log::info('Cancelled old subscriptions', [
                        'cancelled_count' => $cancelledCount,
                        'user_id' => $subscription->user_id,
                        'old_subscription_id' => $oldSubscription->id ?? null,
                        'remaining_days' => $remainingDays,
                    ]);
                    
                    // Calculer la nouvelle date d'expiration
                    // Si l'ancien abonnement avait du temps restant, on l'ajoute au nouveau
                    $newExpiresAt = now()->addMonth();
                    if ($remainingDays > 0) {
                        // Ajouter les jours restants au nouveau plan (maximum 1 mois supplémentaire)
                        $additionalDays = min($remainingDays, 30); // Limiter à 30 jours max
                        $newExpiresAt = now()->addMonth()->addDays($additionalDays);
                        
                        Log::info('Added remaining days to new subscription', [
                            'remaining_days' => $remainingDays,
                            'additional_days' => $additionalDays,
                            'old_expires_at' => $oldSubscription->expires_at ?? null,
                            'new_expires_at' => $newExpiresAt,
                        ]);
                    }
                    
                    // Activer le nouvel abonnement avec le temps calculé
                    $subscription->update([
                        'status' => 'active',
                        'started_at' => now(),
                        'expires_at' => $newExpiresAt,
                    ]);
                    
                    Log::info('Subscription activated', [
                        'subscription_id' => $subscription->id,
                        'plan_id' => $subscription->plan_id,
                        'status' => $subscription->status,
                        'user_id' => $subscription->user_id,
                    ]);
                    
                    // Recharger les relations pour l'email
                    $payment->load('user');
                    $subscription->load('plan');
                    
                    // Envoyer l'email de reçu
                    try {
                        Mail::to($payment->user->email)->send(new PaymentReceiptMail($payment, $subscription));
                        Log::info('Payment receipt email sent successfully', [
                            'user_id' => $payment->user->id,
                            'user_email' => $payment->user->email,
                            'payment_id' => $payment->id,
                            'subscription_id' => $subscription->id,
                            'plan_name' => $subscription->plan->name,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment receipt email', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'user_id' => $payment->user->id ?? 'unknown',
                            'user_email' => $payment->user->email ?? 'unknown',
                            'payment_id' => $payment->id,
                            'subscription_id' => $subscription->id ?? 'unknown',
                        ]);
                    }
                    
                    // Rediriger automatiquement vers le dashboard avec message de succès
                    $plan = $subscription->plan;
                    $successMessage = "🎉 Félicitations ! Votre abonnement {$plan->name} est maintenant actif ! Un reçu a été envoyé à votre adresse email.";
                    
                    // Rediriger vers le dashboard (la page se mettra à jour automatiquement)
                    return redirect()->route('dashboard')
                        ->with('success', $successMessage)
                        ->with('subscription_activated', true);
                } else {
                    Log::error('Subscription not found', ['subscription_id' => $subscriptionId]);
                }
            } else {
                Log::error('Subscription ID missing in metadata', ['metadata' => $metadata]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Paiement réussi ! Votre abonnement est maintenant actif.');
        }

        // Payment failed or cancelled
        Log::warning('Payment failed or cancelled', [
            'tx_ref' => $txRef,
            'status' => $status,
            'transaction' => $transaction,
        ]);
        
        $payment->update(['status' => 'failed']);

        return redirect()->route('dashboard.subscription.index')
            ->withErrors(['payment' => 'Le paiement a échoué ou a été annulé. Veuillez réessayer.']);
    }

    /**
     * Create payment adapter based on provider name
     */
    private function createAdapter(string $providerName)
    {
        return match($providerName) {
            'flutterwave' => new FlutterwaveAdapter(),
            'stripe' => new StripeAdapter(),
            // Ajouter d'autres providers ici
            default => null,
        };
    }
}
