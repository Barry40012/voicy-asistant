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
        // Vérifier si DPO Group n'existe pas déjà
        $exists = DB::table('payment_providers')->where('name', 'dpogroup')->exists();
        
        if (!$exists) {
            DB::table('payment_providers')->insert([
                'name' => 'dpogroup',
                'display_name' => 'DPO Group',
                'description' => 'Direct Pay Online - Paiements par carte Visa/Mastercard en Afrique de l\'Ouest',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'company_token' => '',
                    'service_type' => '5525',
                    'api_key' => '',
                    'webhook_secret' => '',
                ]),
                'config' => json_encode([
                    'base_url' => 'https://secure1.sandbox.directpay.online',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('payment_providers')->where('name', 'dpogroup')->delete();
    }
};
