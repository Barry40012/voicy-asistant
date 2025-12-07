<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private string $whisperApiUrl;
    private string $llmApiUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->whisperApiUrl = config('ai.whisper_api_url', 'https://api.openai.com/v1/audio/transcriptions');
        $this->llmApiUrl = config('ai.llm_api_url', 'https://api.openai.com/v1/chat/completions');
        $this->apiKey = config('ai.api_key');
    }

    /**
     * Transcribe audio using Whisper
     */
    public function transcribe(string $audioContent): ?string
    {
        try {
            // Option 1: OpenAI Whisper API
            if (config('ai.provider') === 'openai') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                ])->attach('file', $audioContent, 'audio.ogg')
                  ->post($this->whisperApiUrl, [
                      'model' => 'whisper-1',
                  ]);

                if ($response->successful()) {
                    return $response->json()['text'] ?? null;
                }
            }

            // Option 2: HuggingFace Whisper
            if (config('ai.provider') === 'huggingface') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                ])->post($this->whisperApiUrl, [
                    'inputs' => base64_encode($audioContent),
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['text'] ?? null;
                }
            }

            Log::error('Transcription failed', [
                'response' => $response->body() ?? 'No response',
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error transcribing audio', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate summary, actions, and reply using LLM
     */
    public function analyzeTranscript(string $transcript): array
    {
        try {
            $prompt = "Analyse ce message vocal et fournis:\n";
            $prompt .= "1. Un résumé en 3 lignes maximum\n";
            $prompt .= "2. Les actions à entreprendre (format JSON array)\n";
            $prompt .= "3. Une réponse professionnelle courte\n\n";
            $prompt .= "Message: {$transcript}\n\n";
            $prompt .= "Réponds au format JSON: {\"summary\": \"...\", \"actions\": [...], \"reply\": \"...\"}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post($this->llmApiUrl, [
                'model' => config('ai.llm_model', 'gpt-3.5-turbo'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'] ?? null;
                if ($content) {
                    $decoded = json_decode($content, true);
                    return [
                        'summary' => $decoded['summary'] ?? null,
                        'actions' => $decoded['actions'] ?? [],
                        'reply' => $decoded['reply'] ?? null,
                    ];
                }
            }

            Log::error('LLM analysis failed', [
                'response' => $response->body() ?? 'No response',
            ]);

            return [
                'summary' => null,
                'actions' => [],
                'reply' => null,
            ];
        } catch (\Exception $e) {
            Log::error('Error analyzing transcript', [
                'error' => $e->getMessage(),
            ]);
            return [
                'summary' => null,
                'actions' => [],
                'reply' => null,
            ];
        }
    }
}
