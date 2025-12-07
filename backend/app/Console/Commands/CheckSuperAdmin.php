<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:check-super-admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Affiche la liste de tous les super administrateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Recherche des super administrateurs...');
        $this->newLine();

        $superAdmins = User::where('role', 'super_admin')
            ->orderBy('created_at', 'asc')
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        if ($superAdmins->isEmpty()) {
            $this->warn('❌ Aucun super administrateur trouvé.');
            $this->newLine();
            $this->info('💡 Pour créer un super admin, utilisez :');
            $this->line('   php artisan admin:make-super-admin email@example.com');
            return 0;
        }

        $this->info('✅ Super administrateurs trouvés : ' . $superAdmins->count());
        $this->newLine();

        $headers = ['ID', 'Nom', 'Email', 'Rôle', 'Créé le'];
        $rows = [];

        foreach ($superAdmins as $admin) {
            $rows[] = [
                $admin->id,
                $admin->name,
                $admin->email,
                $admin->role,
                $admin->created_at->format('d/m/Y H:i'),
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();

        // Afficher aussi les admins normaux
        $admins = User::where('role', 'admin')
            ->orderBy('created_at', 'asc')
            ->get(['id', 'name', 'email', 'role']);

        if ($admins->isNotEmpty()) {
            $this->info('📋 Administrateurs normaux : ' . $admins->count());
            $this->newLine();
            
            $adminHeaders = ['ID', 'Nom', 'Email', 'Rôle'];
            $adminRows = [];
            
            foreach ($admins as $admin) {
                $adminRows[] = [
                    $admin->id,
                    $admin->name,
                    $admin->email,
                    $admin->role,
                ];
            }
            
            $this->table($adminHeaders, $adminRows);
        }

        return 0;
    }
}
