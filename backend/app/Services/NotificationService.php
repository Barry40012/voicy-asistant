<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function create(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $icon = 'info',
        ?string $actionUrl = null,
        ?array $data = null
    ): UserNotification {
        return UserNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'action_url' => $actionUrl,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * Notify user when audio is processed
     */
    public function notifyAudioProcessed(User $user, $audio): void
    {
        $this->create(
            $user,
            'audio_processed',
            'Audio traité avec succès',
            "L'audio de {$audio->sender_phone} a été traité et analysé.",
            'success',
            route('dashboard.audios.show', $audio->id),
            [
                'audio_id' => $audio->id,
                'sender_phone' => $audio->sender_phone,
            ]
        );

        Log::info('Notification created: Audio processed', [
            'user_id' => $user->id,
            'audio_id' => $audio->id,
        ]);
    }

    /**
     * Notify user when audio processing fails
     */
    public function notifyAudioError(User $user, $audio, string $errorMessage): void
    {
        $this->create(
            $user,
            'audio_error',
            'Erreur lors du traitement',
            "Une erreur est survenue lors du traitement de l'audio : {$errorMessage}",
            'error',
            route('dashboard.audios.show', $audio->id),
            [
                'audio_id' => $audio->id,
                'error' => $errorMessage,
            ]
        );

        Log::info('Notification created: Audio error', [
            'user_id' => $user->id,
            'audio_id' => $audio->id,
            'error' => $errorMessage,
        ]);
    }

    /**
     * Notify user when subscription is expiring
     */
    public function notifySubscriptionExpiring(User $user, int $daysRemaining): void
    {
        $message = $daysRemaining === 1
            ? "Votre abonnement expire demain ! Renouvelez maintenant pour continuer à utiliser Voicy Assistant."
            : "Votre abonnement expire dans {$daysRemaining} jours. Renouvelez maintenant.";

        $this->create(
            $user,
            'subscription_expiring',
            'Abonnement expirant',
            $message,
            'warning',
            route('dashboard.subscription.index'),
            [
                'days_remaining' => $daysRemaining,
            ]
        );
    }

    /**
     * Notify user when payment is successful
     */
    public function notifyPaymentSuccess(User $user, $subscription): void
    {
        $this->create(
            $user,
            'payment_success',
            'Paiement réussi',
            "Votre abonnement {$subscription->plan->name} a été activé avec succès !",
            'success',
            route('dashboard.subscription.index'),
            [
                'subscription_id' => $subscription->id,
                'plan_name' => $subscription->plan->name,
            ]
        );

        Log::info('Notification created: Payment success', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
        ]);
    }

    /**
     * Notify user when WhatsApp is connected
     */
    public function notifyWhatsAppConnected(User $user, $connection): void
    {
        $phoneNumber = $connection->phone_number ?? 'votre compte';
        
        $this->create(
            $user,
            'whatsapp_connected',
            'WhatsApp Business connecté',
            "Votre compte WhatsApp Business ({$phoneNumber}) a été connecté avec succès ! Vous pouvez maintenant recevoir des messages vocaux.",
            'success',
            route('dashboard.whatsapp.index'),
            [
                'connection_id' => $connection->id,
                'phone_number' => $phoneNumber,
            ]
        );

        Log::info('Notification created: WhatsApp connected', [
            'user_id' => $user->id,
            'connection_id' => $connection->id,
        ]);
    }

    /**
     * Notify user when password is changed
     */
    public function notifyPasswordChanged(User $user): void
    {
        $this->create(
            $user,
            'password_changed',
            'Mot de passe modifié',
            'Votre mot de passe a été modifié avec succès. Si vous n\'êtes pas à l\'origine de cette modification, veuillez nous contacter immédiatement.',
            'info',
            route('profile.edit'),
            []
        );

        Log::info('Notification created: Password changed', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Notify user on successful login
     */
    public function notifyLogin(User $user, string $ipAddress = null): void
    {
        $this->create(
            $user,
            'login_success',
            'Connexion réussie',
            'Vous vous êtes connecté avec succès à votre compte Voicy Assistant.',
            'success',
            route('dashboard'),
            [
                'ip_address' => $ipAddress,
                'login_at' => now()->toDateTimeString(),
            ]
        );

        Log::info('Notification created: Login success', [
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
        ]);
    }
}

