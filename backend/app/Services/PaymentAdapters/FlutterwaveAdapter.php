<?php

namespace App\Services\PaymentAdapters;

use App\Models\PaymentProvider;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveAdapter implements PaymentAdapterInterface
{
    private string $secretKey;
    private string $publicKey;
    private string $webhookSecret;
    private string $baseUrl;

    public function __construct()
    {
        // Essayer de charger depuis la DB d'abord
        $provider = PaymentProvider::where('name', 'flutterwave')
            ->where('is_active', true)
            ->first();

        if ($provider) {
            $environment = $provider->environment ?? 'test';
            $envPrefix = $environment === 'test' ? 'test_' : 'live_';
            
            // Charger les clés selon l'environnement
            $this->secretKey = $provider->getCredential("{$envPrefix}secret_key") ?: $provider->getCredential('secret_key', '');
            $this->publicKey = $provider->getCredential("{$envPrefix}public_key") ?: $provider->getCredential('public_key', '');
            $this->webhookSecret = $provider->getCredential("{$envPrefix}webhook_secret") ?: $provider->getCredential('webhook_secret', '');
            $this->baseUrl = $provider->getConfig('base_url', $environment === 'test' ? 'https://api.flutterwave.com/v3' : 'https://api.flutterwave.com/v3');
        } else {
            // Fallback vers config si pas dans DB
            $this->secretKey = config('payments.flutterwave.secret_key');
            $this->publicKey = config('payments.flutterwave.public_key');
            $this->webhookSecret = config('payments.flutterwave.webhook_secret');
            $this->baseUrl = config('payments.flutterwave.base_url', 'https://api.flutterwave.com/v3');
        }
        
        // Log pour debug
        Log::info('FlutterwaveAdapter initialized', [
            'has_secret_key' => !empty($this->secretKey),
            'has_public_key' => !empty($this->publicKey),
            'public_key_prefix' => !empty($this->publicKey) ? substr($this->publicKey, 0, 10) . '...' : 'empty',
            'base_url' => $this->baseUrl,
        ]);
    }

    public function getProviderName(): string
    {
        return 'flutterwave';
    }

    /**
     * Create payment transaction
     * Returns payment link for user to complete payment
     */
    public function createPayment(User $user, float $amount, string $currency, array $metadata = []): ?array
    {
        try {
            $txRef = 'VOICY_' . uniqid() . '_' . time();
            // Flutterwave redirige vers cette URL après le paiement
            // Le callback activera l'abonnement et redirigera automatiquement vers le dashboard
            $redirectUrl = route('dashboard.subscription.callback', 'flutterwave');

            $response = Http::withOptions([
                'verify' => false, // Désactiver la vérification SSL pour les tests (Windows/WAMP)
            ])->withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/payments", [
                'tx_ref' => $txRef,
                'amount' => $amount,
                'currency' => strtoupper($currency),
                'payment_options' => 'card,account,banktransfer,ussd,mobilemoneyghana,mobilemoneyrwanda,mobilemoneyuganda,mobilemoneyzambia,barter,mpesa,mobilemoneyfrancophone',
                'redirect_url' => $redirectUrl,
                'customer' => [
                    'email' => $user->email,
                    'name' => $user->name,
                    'phone_number' => $user->phone ?? null,
                ],
                'customizations' => [
                    'title' => 'Voicy Assistant - Abonnement',
                    'description' => 'Paiement de votre abonnement Voicy Assistant',
                    'logo' => asset('images/logo.png'), // Optional
                ],
                'meta' => $metadata,
            ]);

            $data = $response->json();
            
            // Log pour debug
            Log::info('Flutterwave payment response', [
                'status_code' => $response->status(),
                'response_data' => $data,
                'payment_link' => $data['data']['link'] ?? null,
            ]);
            
            if ($response->successful()) {
                // Flutterwave peut retourner le lien dans data.link ou data.data.link
                $paymentLink = $data['data']['link'] ?? $data['data']['data']['link'] ?? null;
                
                if (isset($data['status']) && $data['status'] === 'success' && $paymentLink) {
                    // S'assurer que la clé publique est dans l'URL du checkout
                    // Si elle n'est pas déjà présente, l'ajouter
                    $parsedUrl = parse_url($paymentLink);
                    parse_str($parsedUrl['query'] ?? '', $queryParams);
                    
                    // Si la clé publique n'est pas dans l'URL, l'ajouter
                    if (empty($queryParams['public_key']) && !empty($this->publicKey)) {
                        $separator = strpos($paymentLink, '?') !== false ? '&' : '?';
                        $paymentLink = $paymentLink . $separator . 'public_key=' . urlencode($this->publicKey);
                    }
                    
                    Log::info('Flutterwave payment link generated', [
                        'original_link' => $data['data']['link'] ?? $data['data']['data']['link'] ?? null,
                        'final_link' => $paymentLink,
                        'has_public_key' => !empty($queryParams['public_key']) || !empty($this->publicKey),
                    ]);
                    
                    return [
                        'id' => $txRef,
                        'status' => 'pending',
                        'payment_link' => $paymentLink,
                        'tx_ref' => $txRef,
                    ];
                }
            }

            Log::error('Flutterwave payment creation failed', [
                'status_code' => $response->status(),
                'response' => $response->body(),
                'json_response' => $data,
                'request_data' => [
                    'tx_ref' => $txRef,
                    'amount' => $amount,
                    'currency' => $currency,
                ],
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Flutterwave payment creation exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Verify transaction by tx_ref
     */
    public function verifyTransaction(string $txRef): ?array
    {
        try {
            $response = Http::withOptions([
                'verify' => false, // Désactiver la vérification SSL pour les tests (Windows/WAMP)
            ])->withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/transactions/{$txRef}/verify");

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success' && isset($data['data'])) {
                    $transaction = $data['data'];
                    
                    // Le statut de Flutterwave peut être dans transaction['status'] ou dans la réponse globale
                    $transactionStatus = $transaction['status'] ?? $data['status'] ?? 'pending';
                    
                    return [
                        'id' => $transaction['tx_ref'] ?? $transaction['id'] ?? null,
                        'status' => $this->mapFlutterwaveStatus($transactionStatus),
                        'amount' => $transaction['amount'] ?? null,
                        'currency' => $transaction['currency'] ?? null,
                        'metadata' => array_merge($transaction['meta'] ?? [], [
                            'status' => $transactionStatus,
                            'flw_ref' => $transaction['flw_ref'] ?? null,
                        ]),
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Flutterwave transaction verification exception', [
                'error' => $e->getMessage(),
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
            // Verify webhook signature
            $signature = request()->header('verif-hash');
            
            if ($signature && $this->webhookSecret) {
                $expectedSignature = hash_hmac('sha256', json_encode($payload), $this->webhookSecret);
                
                if (!hash_equals($expectedSignature, $signature)) {
                    Log::warning('Flutterwave webhook signature mismatch');
                    return null;
                }
            }

            $event = $payload['event'] ?? null;
            $data = $payload['data'] ?? null;

            // Flutterwave sends 'charge.completed' for successful payments
            if ($event === 'charge.completed' && $data) {
                $status = $this->mapFlutterwaveStatus($data['status'] ?? '');
                
                if ($status === 'succeeded') {
                    return [
                        'id' => $data['tx_ref'] ?? $data['id'] ?? null,
                        'status' => $status,
                        'amount' => $data['amount'] ?? null,
                        'currency' => $data['currency'] ?? null,
                        'metadata' => $data['meta'] ?? [],
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Flutterwave webhook verification exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Map Flutterwave status to our status
     */
    private function mapFlutterwaveStatus(string $status): string
    {
        return match(strtolower($status)) {
            'successful', 'success' => 'succeeded',
            'pending' => 'pending',
            'failed', 'cancelled' => 'failed',
            default => 'pending',
        };
    }
}

