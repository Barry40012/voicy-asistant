<?php

namespace App\Jobs;

use App\Models\Audio;
use App\Services\AIService;
use App\Services\AudioService;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Audio $audio
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        AIService $aiService,
        AudioService $audioService,
        WhatsAppService $whatsappService
    ): void {
        try {
            // Update status to processing
            $this->audio->update(['status' => 'processing']);

            // Get audio content from Supabase
            $audioContent = $audioService->getAudioContent($this->audio->file_path);

            if (!$audioContent) {
                throw new \Exception('Failed to retrieve audio content');
            }

            // Transcribe audio with language detection
            $transcriptionResult = $aiService->transcribeWithLanguageDetection($audioContent);

            if (!$transcriptionResult['transcript']) {
                throw new \Exception('Transcription failed');
            }

            // Analyze transcript (summary, actions, reply) - adapte selon la langue détectée
            $analysis = $aiService->analyzeTranscript(
                $transcriptionResult['transcript'],
                $transcriptionResult['detected_language']
            );

            // Save analysis with language information
            $this->audio->analysis()->create([
                'transcript' => $transcriptionResult['transcript'],
                'detected_language' => $transcriptionResult['detected_language'],
                'language_confidence' => $transcriptionResult['language_confidence'],
                'detected_languages' => $transcriptionResult['detected_languages'],
                'summary' => $analysis['summary'],
                'actions' => $analysis['actions'],
                'generated_reply' => $analysis['reply'],
                'ia_provider' => config('ai.provider', 'openai'),
            ]);

            // Update audio status
            $this->audio->update([
                'status' => 'done',
                'processed_at' => now(),
            ]);

            // Auto-reply if enabled (check user settings)
            // TODO: Implement auto-reply logic based on user preferences

            Log::info('Audio processed successfully', [
                'audio_id' => $this->audio->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing audio', [
                'audio_id' => $this->audio->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->audio->update([
                'status' => 'error',
                'error_message' => $e->getMessage() . ' (Fichier: ' . basename($e->getFile()) . ', Ligne: ' . $e->getLine() . ')',
            ]);

            // Ne pas relancer le job si on a déjà essayé plusieurs fois
            if ($this->attempts() >= $this->tries) {
                return; // Arrêter les tentatives
            }

            throw $e; // Retry job
        }
    }
}
