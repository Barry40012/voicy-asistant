<?php

namespace App\Services\PaymentAdapters;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class StripeAdapter implements PaymentAdapterInterface
{
    private string $apiKey;
    private string $webhookSecret;

    public function __construct()
    {
        $this->apiKey = config('payments.stripe.secret_key');
        $this->webhookSecret = config('payments.stripe.webhook_secret');
    }

    public function getProviderName(): string
    {
        return 'stripe';
    }

    public function createPayment(User $user, float $amount, string $currency, array $metadata = []): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
            ])->post('https://api.stripe.com/v1/payment_intents', [
                'amount' => (int)($amount * 100), // Convert to cents
                'currency' => strtolower($currency),
                'customer' => $user->stripe_customer_id,
                'metadata' => $metadata,
            ]);

            if ($response->successful()) {
                return [
                    'id' => $response->json()['id'],
                    'status' => $response->json()['status'],
                ];
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Stripe payment creation failed', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function verifyWebhook(array $payload): ?array
    {
        // Verify Stripe webhook signature
        $signature = request()->header('Stripe-Signature');
        // Implementation of signature verification...

        $event = $payload['type'] ?? null;
        $data = $payload['data']['object'] ?? null;

        if ($event === 'payment_intent.succeeded' && $data) {
            return [
                'id' => $data['id'],
                'status' => 'succeeded',
                'metadata' => $data['metadata'] ?? [],
            ];
        }

        return null;
    }
}
