<?php

namespace App\Console\Commands;

use App\Models\PaymentProvider;
use Illuminate\Console\Command;

class CheckPaymentProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:check-providers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie l\'état des providers de paiement';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Vérification des providers de paiement...');
        $this->newLine();

        $providers = PaymentProvider::all();

        if ($providers->isEmpty()) {
            $this->warn('❌ Aucun provider trouvé dans la base de données.');
            return 0;
        }

        $headers = ['ID', 'Nom', 'Actif', 'Défaut', 'Environnement', 'Clés configurées'];
        $rows = [];

        foreach ($providers as $provider) {
            $configured = 0;
            $total = count($provider->credentials ?? []);
            foreach ($provider->credentials ?? [] as $key => $value) {
                if (!empty($value)) {
                    $configured++;
                }
            }

            $rows[] = [
                $provider->id,
                $provider->display_name . ' (' . $provider->name . ')',
                $provider->is_active ? '✅ Oui' : '❌ Non',
                $provider->is_default ? '⭐ Oui' : 'Non',
                $provider->environment === 'live' ? '🟢 Production' : '🟡 Test',
                "{$configured}/{$total}",
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();

        // Vérifier le provider par défaut
        $defaultProvider = PaymentProvider::getDefault();
        
        if ($defaultProvider) {
            $this->info('✅ Provider par défaut trouvé : ' . $defaultProvider->display_name);
            
            // Vérifier les clés
            $hasCredentials = false;
            foreach ($defaultProvider->credentials ?? [] as $key => $value) {
                if (!empty($value)) {
                    $hasCredentials = true;
                    break;
                }
            }

            if ($hasCredentials) {
                $this->info('✅ Clés API configurées');
            } else {
                $this->warn('⚠️  Aucune clé API configurée pour ce provider');
                $this->line('   Va sur /admin/payment-providers pour configurer les clés');
            }
        } else {
            $this->error('❌ Aucun provider disponible');
            $this->line('   Va sur /admin/payment-providers pour activer un provider');
        }

        return 0;
    }
}
