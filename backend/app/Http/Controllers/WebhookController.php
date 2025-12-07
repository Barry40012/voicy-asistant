<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAudioJob;
use App\Models\Audio;
use App\Models\Log;
use App\Models\WhatsAppConnection;
use App\Services\AudioService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function __construct(
        private WhatsAppService $whatsappService,
        private AudioService $audioService
    ) {}

    /**
     * Handle WhatsApp webhook
     */
    public function handleWhatsApp(Request $request)
    {
        // Verify webhook (Meta sends challenge)
        if ($request->has('hub_mode') && $request->hub_mode === 'subscribe') {
            if ($request->hub_verify_token === config('whatsapp.verify_token')) {
                return response($request->hub_challenge, 200);
            }
            return response('Invalid token', 403);
        }

        // Log webhook
        Log::create([
            'type' => 'webhook_whatsapp',
            'payload' => $request->all(),
            'ip_address' => $request->ip(),
        ]);

        $entries = $request->input('entry', []);

        foreach ($entries as $entry) {
            $messaging = $entry['messaging'] ?? [];

            foreach ($messaging as $message) {
                $this->processMessage($message);
            }
        }

        return response('OK', 200);
    }

    /**
     * Process incoming message
     */
    private function processMessage(array $message): void
    {
        $messageData = $message['message'] ?? null;
        $sender = $message['from'] ?? null;
        $phoneNumberId = $message['phone_number_id'] ?? null;

        if (!$messageData || !$sender || !$phoneNumberId) {
            return;
        }

        // Check if message is audio
        if (isset($messageData['type']) && $messageData['type'] === 'audio') {
            $this->handleAudioMessage($messageData, $sender, $phoneNumberId);
        }
    }

    /**
     * Handle audio message
     */
    private function handleAudioMessage(array $messageData, string $sender, string $phoneNumberId): void
    {
        try {
            DB::beginTransaction();

            // Find WhatsApp connection
            $connection = WhatsAppConnection::where('phone_number_id', $phoneNumberId)
                ->where('webhook_verified', true)
                ->first();

            if (!$connection) {
                return;
            }

            // Download audio
            $mediaId = $messageData['audio']['id'] ?? null;
            if (!$mediaId) {
                return;
            }

            $audioContent = $this->whatsappService->downloadMedia(
                $mediaId,
                $connection->access_token
            );

            if (!$audioContent) {
                return;
            }

            // Upload to Supabase Storage
            $filename = uniqid() . '_' . time() . '.ogg';
            $filePath = $this->audioService->uploadAudio($audioContent, $filename);

            if (!$filePath) {
                return;
            }

            // Create audio record
            $audio = Audio::create([
                'user_id' => $connection->user_id,
                'whatsapp_message_id' => $messageData['id'] ?? uniqid(),
                'sender_phone' => $sender,
                'file_path' => $filePath,
                'size_bytes' => strlen($audioContent),
                'status' => 'uploaded',
            ]);

            // Dispatch job to process audio
            ProcessAudioJob::dispatch($audio);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error handling audio message', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle payment webhook
     */
    public function handlePayment(Request $request, string $provider)
    {
        // Log webhook
        Log::create([
            'type' => 'webhook_payment',
            'payload' => array_merge($request->all(), ['provider' => $provider]),
            'ip_address' => $request->ip(),
        ]);

        // Route to appropriate payment adapter
        $adapter = match($provider) {
            'flutterwave' => new \App\Services\PaymentAdapters\FlutterwaveAdapter(),
            default => null,
        };

        if (!$adapter) {
            return response('Provider not supported', 400);
        }

        // Use PaymentService to handle webhook
        $paymentService = app(\App\Services\PaymentService::class);
        $success = $paymentService->handleWebhook($adapter, $request->all());

        if ($success) {
            return response('OK', 200);
        }

        return response('Webhook verification failed', 400);
    }
}
