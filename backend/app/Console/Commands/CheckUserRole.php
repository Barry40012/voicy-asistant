<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserRole extends Command
{
    protected $signature = 'user:check-role {email}';
    protected $description = 'Vérifier le rôle d\'un utilisateur';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Utilisateur non trouvé: {$email}");
            return 1;
        }
        
        $this->info("=== Informations Utilisateur ===");
        $this->line("ID: {$user->id}");
        $this->line("Nom: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Rôle: " . ($user->role ?? 'NULL'));
        $this->line("Type: " . gettype($user->role));
        $this->line("Is Admin: " . ($user->isAdmin() ? 'OUI' : 'NON'));
        
        return 0;
    }
}

