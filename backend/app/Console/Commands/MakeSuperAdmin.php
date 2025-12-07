<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:make-super-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Donne le rôle super_admin à un utilisateur par son email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("🔍 Recherche de l'utilisateur : {$email}");

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Utilisateur non trouvé avec l'email : {$email}");
            return 1;
        }

        $this->info("✅ Utilisateur trouvé : {$user->name} (ID: {$user->id})");
        $this->info("📋 Rôle actuel : " . ($user->role ?? 'user'));

        if ($user->role === 'super_admin') {
            $this->warn("⚠️  Cet utilisateur est déjà super administrateur.");
            return 0;
        }

        if ($this->confirm("Voulez-vous donner le rôle super_admin à {$user->name} ?", true)) {
            $oldRole = $user->role;
            $user->role = 'super_admin';
            $user->save();

            $this->info("✅ Rôle mis à jour avec succès !");
            $this->info("   Ancien rôle : " . ($oldRole ?? 'user'));
            $this->info("   Nouveau rôle : super_admin");
            $this->newLine();
            $this->info("💡 L'utilisateur doit se déconnecter et se reconnecter pour que les changements prennent effet.");
            
            return 0;
        }

        $this->info("❌ Opération annulée.");
        return 0;
    }
}
