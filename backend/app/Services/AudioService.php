<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudioService
{
    private string $supabaseUrl;
    private string $serviceKey;
    private string $bucket;

    public function __construct()
    {
        $this->supabaseUrl = config('services.supabase.url');
        $this->serviceKey = config('services.supabase.service_key');
        $this->bucket = config('services.supabase.bucket', 'audios');
    }

    /**
     * Upload audio to Supabase Storage using REST API
     */
    public function uploadAudio(string $content, string $filename): ?string
    {
        try {
            $path = "audios/{$filename}";
            $url = "{$this->supabaseUrl}/storage/v1/object/{$this->bucket}/{$path}";

            $httpClient = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceKey}",
                'Content-Type' => 'audio/ogg',
                'x-upsert' => 'true', // Overwrite if exists
            ]);
            
            // Désactiver la vérification SSL uniquement en développement local
            // En production, la vérification SSL est activée pour la sécurité
            if (app()->environment('local')) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }
            
            $response = $httpClient->withBody($content, 'audio/ogg')
              ->put($url);

            if ($response->successful()) {
                return $path;
            }

            Log::error('Error uploading audio to Supabase', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error uploading audio to Supabase', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get signed URL for audio
     */
    public function getSignedUrl(string $path, int $expiresIn = 3600): ?string
    {
        try {
            $url = "{$this->supabaseUrl}/storage/v1/object/sign/{$this->bucket}/{$path}";
            
            $httpClient = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceKey}",
            ]);
            
            // Désactiver la vérification SSL uniquement en développement local
            if (app()->environment('local')) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }
            
            $response = $httpClient->post($url, [
                'expiresIn' => $expiresIn,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $signedPath = $data['signedURL'] ?? null;
                
                if ($signedPath) {
                    // Supabase returns a relative path, we need to prepend the URL
                    return $this->supabaseUrl . $signedPath;
                }
            }

            Log::error('Error creating signed URL', [
                'path' => $path,
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error creating signed URL', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get audio content from storage
     */
    public function getAudioContent(string $path): ?string
    {
        try {
            $url = "{$this->supabaseUrl}/storage/v1/object/{$this->bucket}/{$path}";

            $httpClient = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceKey}",
            ]);
            
            // Désactiver la vérification SSL uniquement en développement local
            if (app()->environment('local')) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }
            
            $response = $httpClient->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            Log::error('Error downloading audio from Supabase', [
                'path' => $path,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error downloading audio from Supabase', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Delete audio from storage
     */
    public function deleteAudio(string $path): bool
    {
        try {
            $url = "{$this->supabaseUrl}/storage/v1/object/{$this->bucket}/{$path}";

            $httpClient = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceKey}",
            ]);
            
            // Désactiver la vérification SSL uniquement en développement local
            if (app()->environment('local')) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }
            
            $response = $httpClient->delete($url);

            if ($response->successful()) {
                return true;
            }

            Log::error('Error deleting audio', [
                'path' => $path,
                'status' => $response->status(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting audio', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
