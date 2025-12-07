<?php

namespace App\Services;

use App\Models\WhatsAppConnection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $graphApiUrl = 'https://graph.facebook.com/v18.0';

    /**
     * Download media from WhatsApp
     */
    public function downloadMedia(string $mediaId, string $accessToken): ?string
    {
        try {
            // Get media URL
            $response = Http::get("{$this->graphApiUrl}/{$mediaId}", [
                'access_token' => $accessToken,
            ]);

            if (!$response->successful()) {
                Log::error('Failed to get media URL', [
                    'media_id' => $mediaId,
                    'response' => $response->body(),
                ]);
                return null;
            }

            $mediaData = $response->json();
            $mediaUrl = $mediaData['url'] ?? null;

            if (!$mediaUrl) {
                return null;
            }

            // Download media
            $mediaResponse = Http::get($mediaUrl, [
                'access_token' => $accessToken,
            ]);

            if (!$mediaResponse->successful()) {
                return null;
            }

            return $mediaResponse->body();
        } catch (\Exception $e) {
            Log::error('Error downloading media', [
                'media_id' => $mediaId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Send message via WhatsApp
     */
    public function sendMessage(
        string $phoneNumberId,
        string $to,
        string $message,
        string $accessToken
    ): bool {
        try {
            $response = Http::post("{$this->graphApiUrl}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ], [
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp message', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $signature, string $payload, string $appSecret): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $appSecret);
        return hash_equals($expectedSignature, $signature);
    }
}
