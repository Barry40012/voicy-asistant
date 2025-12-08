<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CheckQueueWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:check-worker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie que le worker de queue est actif et le démarre si nécessaire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Vérification du worker de queue...');

        // Vérifier si Supervisor est disponible
        if (!function_exists('shell_exec')) {
            $this->warn('⚠️  shell_exec n\'est pas disponible, impossible de vérifier Supervisor');
            return 0;
        }

        // Vérifier le statut via Supervisor
        $output = shell_exec('supervisorctl status voicy-queue-worker:* 2>&1');
        
        if (strpos($output, 'RUNNING') !== false) {
            $this->info('✅ Worker de queue actif');
            return 0;
        }

        // Essayer de démarrer le worker
        $this->warn('⚠️  Worker non actif, tentative de démarrage...');
        
        $result = shell_exec('supervisorctl start voicy-queue-worker:* 2>&1');
        
        if (strpos($result, 'started') !== false || strpos($result, 'RUNNING') !== false) {
            $this->info('✅ Worker démarré avec succès');
            return 0;
        }

        $this->error('❌ Impossible de démarrer le worker automatiquement');
        $this->info('💡 Pour démarrer manuellement: sudo supervisorctl start voicy-queue-worker:*');
        $this->info('💡 Ou en local: php artisan queue:work');
        
        return 1;
    }
}

