<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $providers = [
            [
                'name' => 'flutterwave',
                'display_name' => 'Flutterwave',
                'description' => 'Paiements par carte bancaire, mobile money et autres méthodes en Afrique',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'secret_key' => '',
                    'public_key' => '',
                    'webhook_secret' => '',
                ]),
                'config' => json_encode([
                    'base_url' => 'https://api.flutterwave.com/v3',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'stripe',
                'display_name' => 'Stripe',
                'description' => 'Paiements par carte bancaire internationaux',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'secret_key' => '',
                    'public_key' => '',
                    'webhook_secret' => '',
                ]),
                'config' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'orange',
                'display_name' => 'Orange Money',
                'description' => 'Paiements via Orange Money en Afrique',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'merchant_id' => '',
                    'api_key' => '',
                    'webhook_secret' => '',
                ]),
                'config' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'mtn',
                'display_name' => 'MTN Mobile Money',
                'description' => 'Paiements via MTN Mobile Money',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'subscription_key' => '',
                    'api_key' => '',
                    'webhook_secret' => '',
                ]),
                'config' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'paycard',
                'display_name' => 'Paycard',
                'description' => 'Paiements via Paycard (Guinée) - Cartes virtuelles, Orange Money, etc.',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'api_key' => '',
                    'merchant_id' => '',
                    'secret_key' => '',
                    'webhook_secret' => '',
                ]),
                'config' => json_encode([
                    'base_url' => 'https://api.paycard.gn',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payment_providers')->insert($providers);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('payment_providers')->whereIn('name', ['flutterwave', 'stripe', 'orange', 'mtn', 'paycard'])->delete();
    }
};
