<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppConnection;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    /**
     * Show WhatsApp connection page
     */
    public function index()
    {
        $connection = WhatsAppConnection::where('user_id', Auth::id())->first();

        return view('dashboard.whatsapp.index', compact('connection'));
    }

    /**
     * Store WhatsApp connection (manual method)
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone_number_id' => 'required|string',
            'whatsapp_business_account_id' => 'required|string',
            'access_token' => 'required|string',
            'phone_number' => 'nullable|string',
        ]);

        $connection = WhatsAppConnection::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'phone_number_id' => $request->phone_number_id,
                'whatsapp_business_account_id' => $request->whatsapp_business_account_id,
                'access_token' => $request->access_token,
                'phone_number' => $request->phone_number,
                'webhook_verified' => false,
            ]
        );

        // Create notification
        $notificationService = app(NotificationService::class);
        $notificationService->notifyWhatsAppConnected(Auth::user(), $connection);

        return redirect()->route('dashboard.whatsapp.index')
            ->with('success', 'Connexion WhatsApp enregistrée avec succès !');
    }

    /**
     * Initiate OAuth flow with Meta
     */
    public function connect()
    {
        $appId = config('services.meta.app_id');
        $redirectUri = route('dashboard.whatsapp.callback');
        $scopes = 'whatsapp_business_management,whatsapp_business_messaging';

        $authUrl = "https://www.facebook.com/v18.0/dialog/oauth?" . http_build_query([
            'client_id' => $appId,
            'redirect_uri' => $redirectUri,
            'scope' => $scopes,
            'response_type' => 'code',
            'state' => csrf_token(),
        ]);

        return redirect($authUrl);
    }

    /**
     * Handle OAuth callback from Meta
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('dashboard.whatsapp.index')
                ->with('error', 'Connexion annulée : ' . $request->error_description);
        }

        $code = $request->get('code');
        if (!$code) {
            return redirect()->route('dashboard.whatsapp.index')
                ->with('error', 'Code d\'autorisation manquant');
        }

        try {
            // Exchange code for access token
            $tokenResponse = Http::post('https://graph.facebook.com/v18.0/oauth/access_token', [
                'client_id' => config('services.meta.app_id'),
                'client_secret' => config('services.meta.app_secret'),
                'redirect_uri' => route('dashboard.whatsapp.callback'),
                'code' => $code,
            ]);

            if (!$tokenResponse->successful()) {
                return redirect()->route('dashboard.whatsapp.index')
                    ->with('error', 'Erreur lors de l\'obtention du token d\'accès');
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                return redirect()->route('dashboard.whatsapp.index')
                    ->with('error', 'Token d\'accès non reçu');
            }

            // Get WhatsApp Business Account
            $wabaResponse = Http::get('https://graph.facebook.com/v18.0/me/businesses', [
                'access_token' => $accessToken,
            ]);

            if (!$wabaResponse->successful()) {
                return redirect()->route('dashboard.whatsapp.index')
                    ->with('error', 'Impossible de récupérer les comptes WhatsApp Business');
            }

            $businesses = $wabaResponse->json('data', []);
            if (empty($businesses)) {
                return redirect()->route('dashboard.whatsapp.index')
                    ->with('error', 'Aucun compte WhatsApp Business trouvé. Assure-toi d\'avoir un compte WhatsApp Business configuré.');
            }

            // Get first business account (or let user choose)
            $businessId = $businesses[0]['id'] ?? null;
            
            // Get phone numbers for this business
            $phonesResponse = Http::get("https://graph.facebook.com/v18.0/{$businessId}/phone_numbers", [
                'access_token' => $accessToken,
            ]);

            $phones = $phonesResponse->json('data', []);
            $phoneNumberId = $phones[0]['id'] ?? null;

            if (!$phoneNumberId) {
                return redirect()->route('dashboard.whatsapp.index')
                    ->with('error', 'Aucun numéro de téléphone WhatsApp trouvé');
            }

            // Save connection (access_token is automatically encrypted by the model)
            $connection = WhatsAppConnection::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'phone_number_id' => $phoneNumberId,
                    'whatsapp_business_account_id' => $businessId,
                    'access_token' => $accessToken, // Will be encrypted automatically by the model
                    'phone_number' => $phones[0]['display_phone_number'] ?? null,
                    'webhook_verified' => false,
                ]
            );

            // Try to configure webhook automatically
            $this->configureWebhook($connection, $accessToken);

            // Create notification
            $notificationService = app(NotificationService::class);
            $notificationService->notifyWhatsAppConnected(Auth::user(), $connection);

            return redirect()->route('dashboard.whatsapp.index')
                ->with('success', 'WhatsApp Business connecté avec succès ! 🎉');
        } catch (\Exception $e) {
            Log::error('Error in WhatsApp OAuth callback', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('dashboard.whatsapp.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Configure webhook automatically
     */
    private function configureWebhook(WhatsAppConnection $connection, string $accessToken): void
    {
        try {
            $webhookUrl = config('app.url') . '/api/webhooks/whatsapp';
            $verifyToken = config('whatsapp.verify_token');

            // This would require app-level permissions
            // For now, we'll just mark it as needing manual setup
            // In production, you'd use the Meta Business API to configure webhooks
        } catch (\Exception $e) {
            Log::error('Error configuring webhook', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Verify webhook
     */
    public function verify()
    {
        $connection = WhatsAppConnection::where('user_id', Auth::id())->first();

        if ($connection) {
            $connection->update(['webhook_verified' => true]);
            return redirect()->route('dashboard.whatsapp.index')
                ->with('success', 'Webhook vérifié');
        }

        return back()->withErrors(['connection' => 'Aucune connexion trouvée']);
    }
}
