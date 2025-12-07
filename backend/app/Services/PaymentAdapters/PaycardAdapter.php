<?php

namespace App\Services\PaymentAdapters;

use App\Models\PaymentProvider;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaycardAdapter implements PaymentAdapterInterface
{
    private string $apiKey;
    private string $merchantId;
    private string $secretKey;
    private string $webhookSecret;
    private string $baseUrl;
    private string $environment;

    public function __construct()
    {
        // Essayer de charger depuis la DB d'abord
        $provider = PaymentProvider::where('name', 'paycard')
            ->where('is_active', true)
            ->first();

        if ($provider) {
            $this->environment = $provider->environment ?? 'test';
            $envPrefix = $this->environment === 'test' ? 'test_' : 'live_';
            
            // Charger les clés selon l'environnement
            $this->apiKey = $provider->getCredential("{$envPrefix}api_key") ?: $provider->getCredential('api_key', '');
            $this->merchantId = $provider->getCredential("{$envPrefix}merchant_id") ?: $provider->getCredential('merchant_id', '');
            $this->secretKey = $provider->getCredential("{$envPrefix}secret_key") ?: $provider->getCredential('secret_key', '');
            $this->webhookSecret = $provider->getCredential("{$envPrefix}webhook_secret") ?: $provider->getCredential('webhook_secret', '');
            $this->baseUrl = $provider->getConfig('base_url', $this->environment === 'test' 
                ? 'https://api.paycard.gn/sandbox' 
                : 'https://api.paycard.gn/v1');
        } else {
            // Fallback vers config si pas dans DB
            $this->environment = config('payments.paycard.environment', 'test');
            $this->apiKey = config('payments.paycard.api_key', '');
            $this->merchantId = config('payments.paycard.merchant_id', '');
            $this->secretKey = config('payments.paycard.secret_key', '');
            $this->webhookSecret = config('payments.paycard.webhook_secret', '');
            $this->baseUrl = config('payments.paycard.base_url', $this->environment === 'test' 
                ? 'https://api.paycard.gn/sandbox' 
                : 'https://api.paycard.gn/v1');
        }
        
        // Log pour debug
        Log::info('PaycardAdapter initialized', [
            'environment' => $this->environment,
            'has_api_key' => !empty($this->apiKey),
            'has_merchant_id' => !empty($this->merchantId),
            'has_secret_key' => !empty($this->secretKey),
            'base_url' => $this->baseUrl,
        ]);
    }

    public function getProviderName(): string
    {
        return 'paycard';
    }

    /**
     * Create payment
     */
    public function createPayment(User $user, float $amount, string $currency, array $metadata = []): ?array
    {
        try {
            $txRef = 'VOICY_' . uniqid() . '_' . time();
            $redirectUrl = route('dashboard.subscription.callback', ['provider' => 'paycard']);

            // Paycard API - Créer un paiement
            // Note: Cette structure est générique, à adapter selon la vraie API Paycard
            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
                'X-Merchant-Id' => $this->merchantId,
            ])->post("{$this->baseUrl}/payments", [
                'transaction_ref' => $txRef,
                'amount' => $amount,
                'currency' => strtoupper($currency),
                'customer' => [
                    'email' => $user->email,
                    'name' => $user->name,
                    'phone' => $user->phone ?? null,
                ],
                'callback_url' => $redirectUrl,
                'metadata' => $metadata,
            ]);

            $data = $response->json();

            Log::info('Paycard payment response', [
                'status_code' => $response->status(),
                'response_data' => $data,
                'transaction_ref' => $txRef,
            ]);

            if ($response->successful()) {
                $paymentLink = $data['data']['payment_url'] ?? $data['payment_url'] ?? null;

                if (isset($data['status']) && in_array($data['status'], ['success', 'pending']) && $paymentLink) {
                    return [
                        'id' => $txRef,
                        'status' => 'pending',
                        'payment_link' => $paymentLink,
                        'tx_ref' => $txRef,
                    ];
                }
            }

            Log::error('Paycard payment creation failed', [
                'status_code' => $response->status(),
                'response' => $data,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Paycard payment exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Verify webhook payload
     */
    public function verifyWebhook(array $payload): ?array
    {
        try {
            // Vérifier la signature du webhook Paycard
            $signature = $payload['signature'] ?? null;
            $expectedSignature = hash_hmac('sha256', json_encode($payload['data'] ?? []), $this->webhookSecret);

            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Paycard webhook signature mismatch');
                return null;
            }

            $transaction = $payload['data'] ?? $payload;

            // Vérifier le statut de la transaction
            $status = $transaction['status'] ?? null;
            $txRef = $transaction['transaction_ref'] ?? $transaction['reference'] ?? null;

            if (!$txRef) {
                Log::warning('Paycard webhook missing transaction reference');
                return null;
            }

            return [
                'transaction_id' => $txRef,
                'status' => $this->mapStatus($status),
                'amount' => $transaction['amount'] ?? null,
                'currency' => $transaction['currency'] ?? 'GNF',
                'provider' => 'paycard',
            ];
        } catch (\Exception $e) {
            Log::error('Paycard webhook verification exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Map Paycard status to our status
     */
    private function mapStatus(?string $status): string
    {
        return match(strtolower($status ?? '')) {
            'completed', 'success', 'paid' => 'succeeded',
            'pending', 'processing' => 'pending',
            'failed', 'cancelled', 'declined' => 'failed',
            default => 'pending',
        };
    }
}

