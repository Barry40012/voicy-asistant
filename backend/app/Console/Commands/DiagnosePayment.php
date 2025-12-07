<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;

class DiagnosePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:diagnose {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostiquer les paiements et abonnements pour un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Email de l\'utilisateur');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur non trouvé avec l'email: {$email}");
            return 1;
        }

        $this->info("🔍 Diagnostic pour: {$user->name} ({$user->email})");
        $this->newLine();

        // Vérifier les paiements
        $this->info("📊 Paiements:");
        $payments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        if ($payments->isEmpty()) {
            $this->warn("  Aucun paiement trouvé");
        } else {
            $headers = ['ID', 'Montant', 'Devise', 'Statut', 'Provider', 'Date'];
            $rows = [];
            foreach ($payments as $payment) {
                $rows[] = [
                    $payment->id,
                    number_format($payment->amount, 2),
                    $payment->currency,
                    $payment->status === 'succeeded' ? '✅ Réussi' : ($payment->status === 'pending' ? '⏳ En attente' : '❌ Échoué'),
                    $payment->provider,
                    $payment->created_at->format('d/m/Y H:i'),
                ];
            }
            $this->table($headers, $rows);
        }

        $this->newLine();

        // Vérifier les abonnements
        $this->info("📋 Abonnements:");
        $subscriptions = Subscription::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->warn("  Aucun abonnement trouvé");
        } else {
            $headers = ['ID', 'Plan', 'Statut', 'Début', 'Expiration'];
            $rows = [];
            foreach ($subscriptions as $sub) {
                $status = match($sub->status) {
                    'active' => '✅ Actif',
                    'pending' => '⏳ En attente',
                    'expired' => '❌ Expiré',
                    default => $sub->status,
                };
                $rows[] = [
                    $sub->id,
                    $sub->plan->name ?? 'N/A',
                    $status,
                    $sub->started_at ? $sub->started_at->format('d/m/Y') : 'N/A',
                    $sub->expires_at ? $sub->expires_at->format('d/m/Y') : 'N/A',
                ];
            }
            $this->table($headers, $rows);
        }

        $this->newLine();

        // Vérifier les paiements réussis sans abonnement actif
        $this->info("🔍 Analyse:");
        $successfulPayments = Payment::where('user_id', $user->id)
            ->where('status', 'succeeded')
            ->get();

        $activeSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($successfulPayments->isNotEmpty() && !$activeSubscription) {
            $this->warn("⚠️  Problème détecté:");
            $this->line("  - {$successfulPayments->count()} paiement(s) réussi(s)");
            $this->line("  - Mais aucun abonnement actif");
            $this->newLine();
            $this->info("💡 Solution:");
            $this->line("  Exécute: php artisan payment:activate-subscriptions {$email}");
        } elseif ($activeSubscription) {
            $this->info("✅ Abonnement actif trouvé:");
            $this->line("  - Plan: {$activeSubscription->plan->name}");
            $this->line("  - Expire le: {$activeSubscription->expires_at->format('d/m/Y')}");
        } else {
            $this->warn("  Aucun paiement réussi trouvé");
        }

        return 0;
    }
}
