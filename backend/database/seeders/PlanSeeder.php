<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'price_monthly' => 0,
                'price_currency' => 'EUR',
                'allowed_audio_per_month' => 50,
                'allowed_audio_per_minute_length' => 2,
                'description' => 'Plan gratuit avec limitations',
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'price_monthly' => 9.00,
                'price_currency' => 'EUR',
                'allowed_audio_per_month' => 500,
                'allowed_audio_per_minute_length' => 5,
                'description' => 'Parfait pour les petites entreprises',
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'price_monthly' => 29.00,
                'price_currency' => 'EUR',
                'allowed_audio_per_month' => 5000,
                'allowed_audio_per_minute_length' => 10,
                'description' => 'Pour les entreprises avec volume élevé',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
