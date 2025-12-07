<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Définir un utilisateur comme administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur avec l'email '{$email}' non trouvé.");
            return 1;
        }
        
        $user->role = 'admin';
        $user->save();
        
        $this->info("✅ Rôle admin défini avec succès pour : {$user->name} ({$user->email})");
        $this->line("🔐 Tu peux maintenant accéder au panel admin sur : /admin");
        $this->line("⚠️  N'oublie pas de te déconnecter et reconnecter pour que les changements prennent effet.");
        
        return 0;
    }
}

