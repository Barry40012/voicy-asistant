<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Audio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-all {--force : Supprimer sans confirmation} {--keep-admins : Garder les comptes administrateurs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimer tous les comptes utilisateurs (sauf admins si option activée) pour repartir de zéro';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keepAdmins = $this->option('keep-admins');
        $force = $this->option('force');

        // Compter les utilisateurs à supprimer
        $query = User::query();
        if ($keepAdmins) {
            $query->whereNotIn('role', ['admin', 'super_admin']);
        }
        $usersToDelete = $query->get();
        $count = $usersToDelete->count();

        if ($count === 0) {
            $this->info("✅ Aucun utilisateur à supprimer.");
            return 0;
        }

        // Afficher le résumé
        $this->warn("⚠️  ATTENTION : Cette action va supprimer définitivement :");
        $this->line("   - {$count} utilisateur(s)");
        
        // Compter les données associées
        $userIds = $usersToDelete->pluck('id');
        $subscriptionsCount = Subscription::whereIn('user_id', $userIds)->count();
        $paymentsCount = Payment::whereIn('user_id', $userIds)->count();
        $audiosCount = Audio::whereIn('user_id', $userIds)->count();
        
        $this->line("   - {$subscriptionsCount} abonnement(s)");
        $this->line("   - {$paymentsCount} paiement(s)");
        $this->line("   - {$audiosCount} audio(s)");
        $this->newLine();

        if ($keepAdmins) {
            $this->info("ℹ️  Les comptes administrateurs seront conservés.");
        }

        // Demander confirmation
        if (!$force) {
            if (!$this->confirm('⚠️  Êtes-vous SÛR de vouloir continuer ? Cette action est IRRÉVERSIBLE !', false)) {
                $this->info("❌ Opération annulée.");
                return 0;
            }

            // Double confirmation
            if (!$this->confirm('⚠️  Dernière confirmation : Supprimer définitivement tous ces comptes ?', false)) {
                $this->info("❌ Opération annulée.");
                return 0;
            }
        }

        $this->newLine();
        $this->info("🗑️  Suppression en cours...");

        // Démarrer une transaction
        DB::beginTransaction();

        try {
            // Supprimer les données associées (en cascade normalement, mais on le fait explicitement)
            $this->line("  → Suppression des abonnements...");
            Subscription::whereIn('user_id', $userIds)->delete();
            
            $this->line("  → Suppression des paiements...");
            Payment::whereIn('user_id', $userIds)->delete();
            
            $this->line("  → Suppression des audios...");
            Audio::whereIn('user_id', $userIds)->delete();
            
            // Supprimer les utilisateurs
            $this->line("  → Suppression des utilisateurs...");
            $deleted = User::whereIn('id', $userIds)->delete();

            // Valider la transaction
            DB::commit();

            $this->newLine();
            $this->info("✅ Suppression terminée avec succès !");
            $this->line("   - {$deleted} utilisateur(s) supprimé(s)");
            $this->line("   - {$subscriptionsCount} abonnement(s) supprimé(s)");
            $this->line("   - {$paymentsCount} paiement(s) supprimé(s)");
            $this->line("   - {$audiosCount} audio(s) supprimé(s)");
            $this->newLine();
            $this->info("🎉 La plateforme est maintenant prête pour de nouveaux tests !");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Erreur lors de la suppression : " . $e->getMessage());
            $this->error("   La transaction a été annulée. Aucune donnée n'a été supprimée.");
            return 1;
        }

        return 0;
    }
}

