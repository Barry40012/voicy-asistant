<?php

namespace App\Services\PaymentAdapters;

use App\Models\PaymentProvider;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DPOGroupAdapter implements PaymentAdapterInterface
{
    private string $companyToken;
    private string $serviceType;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        // Essayer de charger depuis la DB d'abord
        $provider = PaymentProvider::where('name', 'dpogroup')
            ->where('is_active', true)
            ->first();

        if ($provider) {
            $environment = $provider->environment ?? 'test';
            $envPrefix = $environment === 'test' ? 'test_' : 'live_';
            
            // Charger les clés selon l'environnement
            $this->companyToken = $provider->getCredential("{$envPrefix}company_token") ?: $provider->getCredential('company_token', '');
            $this->serviceType = $provider->getCredential("{$envPrefix}service_type") ?: $provider->getCredential('service_type', '');
            $this->apiKey = $provider->getCredential("{$envPrefix}api_key") ?: $provider->getCredential('api_key', '');
            
            // URLs DPO Group
            $this->baseUrl = $provider->getConfig('base_url', $environment === 'test' 
                ? 'https://secure1.sandbox.directpay.online' 
                : 'https://secure.3gdirectpay.com');
        } else {
            // Fallback vers config si pas dans DB
            $this->companyToken = config('payments.dpogroup.company_token');
            $this->serviceType = config('payments.dpogroup.service_type', '5525'); // Service type par défaut
            $this->apiKey = config('payments.dpogroup.api_key');
            $this->baseUrl = config('payments.dpogroup.base_url', 'https://secure.3gdirectpay.com');
        }
        
        Log::info('DPOGroupAdapter initialized', [
            'has_company_token' => !empty($this->companyToken),
            'has_api_key' => !empty($this->apiKey),
            'service_type' => $this->serviceType,
            'base_url' => $this->baseUrl,
        ]);
    }

    public function getProviderName(): string
    {
        return 'dpogroup';
    }

    /**
     * Create payment transaction
     * DPO Group utilise un système de token pour créer les paiements
     */
    public function createPayment(User $user, float $amount, string $currency, array $metadata = []): ?array
    {
        try {
            // Générer une référence unique
            $paymentRef = 'VOICY_' . uniqid() . '_' . time();
            
            // URL de callback après paiement
            $redirectUrl = route('dashboard.subscription.callback', 'dpogroup');
            $backUrl = route('dashboard.subscription.index'); // URL si l'utilisateur annule
            
            // DPO Group nécessite un XML pour créer un token
            $xml = $this->buildCreateTokenXML([
                'paymentAmount' => $amount,
                'paymentCurrency' => strtoupper($currency),
                'companyRef' => $paymentRef,
                'redirectURL' => $redirectUrl,
                'backURL' => $backUrl,
                'customerEmail' => $user->email,
                'customerFirstName' => explode(' ', $user->name)[0] ?? $user->name,
                'customerLastName' => count(explode(' ', $user->name)) > 1 ? implode(' ', array_slice(explode(' ', $user->name), 1)) : '',
                'customerPhone' => $user->phone ?? '',
                'customerAddress' => $metadata['address'] ?? '',
                'customerCity' => $metadata['city'] ?? '',
                'customerCountry' => $metadata['country'] ?? 'GN', // Guinée par défaut
                'customerZip' => $metadata['zip'] ?? '',
                'metadata' => $metadata,
            ]);

            $response = Http::withOptions([
                'verify' => app()->environment('local') ? false : true,
            ])->withHeaders([
                'Content-Type' => 'application/xml',
            ])->withBody($xml, 'application/xml')
              ->post("{$this->baseUrl}/API/v6/");

            $responseData = $this->parseXMLResponse($response->body());

            Log::info('DPO Group payment response', [
                'status_code' => $response->status(),
                'response_data' => $responseData,
            ]);

            if ($response->successful() && isset($responseData['Result']) && $responseData['Result'] === '000') {
                // Succès - récupérer le token
                $token = $responseData['TransToken'] ?? null;
                
                if ($token) {
                    // Construire l'URL de paiement
                    $paymentUrl = "{$this->baseUrl}/payv2.php?ID={$token}";
                    
                    return [
                        'id' => $paymentRef,
                        'status' => 'pending',
                        'payment_link' => $paymentUrl,
                        'token' => $token,
                        'company_ref' => $paymentRef,
                    ];
                }
            }

            Log::error('DPO Group payment creation failed', [
                'status_code' => $response->status(),
                'response' => $response->body(),
                'parsed_response' => $responseData,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('DPO Group payment creation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Verify webhook payload
     * DPO Group envoie des notifications via webhook
     */
    public function verifyWebhook(array $payload): ?array
    {
        try {
            // DPO Group peut envoyer les données en XML ou JSON selon la configuration
            $transactionToken = $payload['TransactionToken'] ?? $payload['transactionToken'] ?? null;
            $companyRef = $payload['CompanyRef'] ?? $payload['companyRef'] ?? null;
            
            if (!$transactionToken) {
                return null;
            }

            // Vérifier le statut de la transaction
            $verificationResult = $this->verifyTransaction($transactionToken, $companyRef);
            
            if ($verificationResult && $verificationResult['status'] === 'succeeded') {
                return [
                    'id' => $companyRef ?? $transactionToken,
                    'status' => 'succeeded',
                    'amount' => $verificationResult['amount'] ?? null,
                    'currency' => $verificationResult['currency'] ?? null,
                    'metadata' => $verificationResult['metadata'] ?? [],
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('DPO Group webhook verification exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Verify transaction status
     */
    public function verifyTransaction(string $transactionToken, ?string $companyRef = null): ?array
    {
        try {
            $xml = $this->buildVerifyTokenXML($transactionToken, $companyRef);
            
            $response = Http::withOptions([
                'verify' => app()->environment('local') ? false : true,
            ])->withHeaders([
                'Content-Type' => 'application/xml',
            ])->withBody($xml, 'application/xml')
              ->post("{$this->baseUrl}/API/v6/");

            $responseData = $this->parseXMLResponse($response->body());

            if ($response->successful() && isset($responseData['Result']) && $responseData['Result'] === '000') {
                $status = $this->mapDPOStatus($responseData['TransactionStatus'] ?? '');
                
                return [
                    'id' => $companyRef ?? $transactionToken,
                    'status' => $status,
                    'amount' => $responseData['TransactionAmount'] ?? null,
                    'currency' => $responseData['TransactionCurrency'] ?? null,
                    'metadata' => [
                        'transaction_token' => $transactionToken,
                        'transaction_status' => $responseData['TransactionStatus'] ?? '',
                        'transaction_date' => $responseData['TransactionDate'] ?? null,
                    ],
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('DPO Group transaction verification exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Build XML for CreateToken API
     */
    private function buildCreateTokenXML(array $data): string
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<API3G>';
        $xml .= '<CompanyToken>' . htmlspecialchars($this->companyToken) . '</CompanyToken>';
        $xml .= '<Request>createToken</Request>';
        $xml .= '<Transaction>';
        $xml .= '<PaymentAmount>' . htmlspecialchars($data['paymentAmount']) . '</PaymentAmount>';
        $xml .= '<PaymentCurrency>' . htmlspecialchars($data['paymentCurrency']) . '</PaymentCurrency>';
        $xml .= '<CompanyRef>' . htmlspecialchars($data['companyRef']) . '</CompanyRef>';
        $xml .= '<RedirectURL>' . htmlspecialchars($data['redirectURL']) . '</RedirectURL>';
        $xml .= '<BackURL>' . htmlspecialchars($data['backURL']) . '</BackURL>';
        $xml .= '<CompanyRefUnique>0</CompanyRefUnique>';
        $xml .= '<PTL>5</PTL>'; // Payment Time Limit (minutes)
        
        // Customer details
        $xml .= '<customerFirstName>' . htmlspecialchars($data['customerFirstName']) . '</customerFirstName>';
        $xml .= '<customerLastName>' . htmlspecialchars($data['customerLastName']) . '</customerLastName>';
        $xml .= '<customerEmail>' . htmlspecialchars($data['customerEmail']) . '</customerEmail>';
        $xml .= '<customerPhone>' . htmlspecialchars($data['customerPhone']) . '</customerPhone>';
        
        if (!empty($data['customerAddress'])) {
            $xml .= '<customerAddress>' . htmlspecialchars($data['customerAddress']) . '</customerAddress>';
        }
        if (!empty($data['customerCity'])) {
            $xml .= '<customerCity>' . htmlspecialchars($data['customerCity']) . '</customerCity>';
        }
        if (!empty($data['customerCountry'])) {
            $xml .= '<customerCountry>' . htmlspecialchars($data['customerCountry']) . '</customerCountry>';
        }
        if (!empty($data['customerZip'])) {
            $xml .= '<customerZip>' . htmlspecialchars($data['customerZip']) . '</customerZip>';
        }
        
        // Service type (pour les abonnements)
        if (!empty($this->serviceType)) {
            $xml .= '<serviceType>' . htmlspecialchars($this->serviceType) . '</serviceType>';
        }
        
        // Metadata
        if (!empty($data['metadata'])) {
            $xml .= '<customerMetadata>';
            foreach ($data['metadata'] as $key => $value) {
                $xml .= '<MetaData>';
                $xml .= '<MetaName>' . htmlspecialchars($key) . '</MetaName>';
                $xml .= '<MetaValue>' . htmlspecialchars($value) . '</MetaValue>';
                $xml .= '</MetaData>';
            }
            $xml .= '</customerMetadata>';
        }
        
        $xml .= '</Transaction>';
        $xml .= '</API3G>';
        
        return $xml;
    }

    /**
     * Build XML for VerifyToken API
     */
    private function buildVerifyTokenXML(string $transactionToken, ?string $companyRef = null): string
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<API3G>';
        $xml .= '<CompanyToken>' . htmlspecialchars($this->companyToken) . '</CompanyToken>';
        $xml .= '<Request>verifyToken</Request>';
        $xml .= '<TransactionToken>' . htmlspecialchars($transactionToken) . '</TransactionToken>';
        
        if ($companyRef) {
            $xml .= '<CompanyRef>' . htmlspecialchars($companyRef) . '</CompanyRef>';
        }
        
        $xml .= '</API3G>';
        
        return $xml;
    }

    /**
     * Parse XML response from DPO Group
     */
    private function parseXMLResponse(string $xml): array
    {
        try {
            $xmlObject = simplexml_load_string($xml);
            if ($xmlObject === false) {
                return [];
            }
            
            // Convertir en array
            $json = json_encode($xmlObject);
            return json_decode($json, true) ?: [];
        } catch (\Exception $e) {
            Log::error('DPO Group XML parsing error', [
                'error' => $e->getMessage(),
                'xml' => substr($xml, 0, 500), // Premiers 500 caractères pour debug
            ]);
            return [];
        }
    }

    /**
     * Map DPO Group status to our status
     */
    private function mapDPOStatus(string $status): string
    {
        return match(strtolower($status)) {
            '3', 'paid', 'completed', 'success' => 'succeeded',
            '1', 'pending', 'processing' => 'pending',
            '2', 'failed', 'cancelled', 'declined' => 'failed',
            default => 'pending',
        };
    }
}

