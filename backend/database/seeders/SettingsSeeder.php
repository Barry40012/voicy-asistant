<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'info.voicyassistant@gmail.com',
                'description' => 'Adresse email de contact affichée sur le site',
                'type' => 'email',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value' => '',
                'description' => 'Numéro de téléphone de contact',
                'type' => 'text',
                'group' => 'contact',
            ],
            
            // Mail Settings (SMTP)
            [
                'key' => 'mail_from_address',
                'value' => 'info.voicyassistant@gmail.com',
                'description' => 'Adresse email d\'envoi (expéditeur)',
                'type' => 'email',
                'group' => 'mail',
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'Voicy Assistant',
                'description' => 'Nom de l\'expéditeur des emails',
                'type' => 'text',
                'group' => 'mail',
            ],
            [
                'key' => 'mail_host',
                'value' => env('MAIL_HOST', 'smtp.gmail.com'),
                'description' => 'Serveur SMTP',
                'type' => 'text',
                'group' => 'mail',
            ],
            [
                'key' => 'mail_port',
                'value' => env('MAIL_PORT', '587'),
                'description' => 'Port SMTP',
                'type' => 'number',
                'group' => 'mail',
            ],
            [
                'key' => 'mail_username',
                'value' => env('MAIL_USERNAME', ''),
                'description' => 'Nom d\'utilisateur SMTP',
                'type' => 'email',
                'group' => 'mail',
            ],
            [
                'key' => 'mail_password',
                'value' => env('MAIL_PASSWORD', ''),
                'description' => 'Mot de passe SMTP (stocké de manière sécurisée)',
                'type' => 'password',
                'group' => 'mail',
            ],
            [
                'key' => 'mail_encryption',
                'value' => env('MAIL_ENCRYPTION', 'tls'),
                'description' => 'Type de chiffrement (tls ou ssl)',
                'type' => 'text',
                'group' => 'mail',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
