<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester l\'envoi d\'email de réinitialisation de mot de passe';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🧪 Test d'envoi d'email de réinitialisation...");
        $this->info("Email: {$email}");
        $this->newLine();

        // Vérifier la configuration
        $this->info("📋 Configuration email:");
        $this->line("  MAIL_MAILER: " . config('mail.default'));
        $this->line("  MAIL_HOST: " . config('mail.mailers.smtp.host'));
        $this->line("  MAIL_PORT: " . config('mail.mailers.smtp.port'));
        $this->line("  MAIL_USERNAME: " . (config('mail.mailers.smtp.username') ? 'défini' : 'non défini'));
        $this->line("  MAIL_FROM_ADDRESS: " . config('mail.from.address'));
        $this->newLine();

        // Vérifier si l'utilisateur existe
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur non trouvé avec l'email: {$email}");
            $this->warn("💡 Crée d'abord un compte avec cet email.");
            return 1;
        }

        $this->info("✅ Utilisateur trouvé: {$user->name}");
        $this->newLine();

        // Tester l'envoi
        try {
            $this->info("📧 Envoi de l'email de réinitialisation...");
            
            $status = Password::sendResetLink(['email' => $email]);
            
            if ($status == Password::RESET_LINK_SENT) {
                $this->info("✅ Email envoyé avec succès !");
                $this->line("   Statut: " . $status);
                $this->newLine();
                $this->warn("💡 Vérifie ta boîte email (et le dossier Spam)");
                $this->warn("💡 Si tu utilises Gmail, vérifie aussi les spams");
                return 0;
            } else {
                $this->error("❌ Erreur lors de l'envoi");
                $this->line("   Statut: " . $status);
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Exception: " . $e->getMessage());
            $this->line("   Fichier: " . $e->getFile() . ":" . $e->getLine());
            $this->newLine();
            $this->warn("💡 Vérifie les logs: storage/logs/laravel.log");
            Log::error('Test email failed', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}
