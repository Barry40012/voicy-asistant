<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private string $whisperApiUrl;
    private string $llmApiUrl;
    private string $apiKey;
    
    // Langues supportées (priorité: internationales, puis locales)
    private array $supportedLanguages = [
        'fr' => 'Français',
        'en' => 'Anglais',
        // Langues locales (à activer progressivement)
        // 'sw' => 'Swahili',
        // 'wo' => 'Wolof',
        // 'ff' => 'Fulfulde',
        // 'dy' => 'Dyula',
    ];
    
    // Langues mixtes détectées
    private const MIXED_LANGUAGE_CODE = 'mixed';

    public function __construct()
    {
        // Essayer de charger depuis la DB d'abord
        $provider = \App\Models\AIProvider::getDefault();
        
        if ($provider && $provider->is_active) {
            $this->apiKey = $provider->getCredential('api_key', '');
            $this->whisperApiUrl = $provider->getConfig('whisper_api_url', config('ai.whisper_api_url', 'https://api.openai.com/v1/audio/transcriptions'));
            $this->llmApiUrl = $provider->getConfig('llm_api_url', config('ai.llm_api_url', 'https://api.openai.com/v1/chat/completions'));
            
            // Déterminer le provider depuis le nom
            if ($provider->name === 'openai') {
                config(['ai.provider' => 'openai']);
            } elseif ($provider->name === 'huggingface') {
                config(['ai.provider' => 'huggingface']);
            }
        } else {
            // Fallback vers config si pas dans DB
            $this->whisperApiUrl = config('ai.whisper_api_url', 'https://api.openai.com/v1/audio/transcriptions');
            $this->llmApiUrl = config('ai.llm_api_url', 'https://api.openai.com/v1/chat/completions');
            $this->apiKey = config('ai.api_key');
        }
    }
    
    /**
     * Get supported languages
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }
    
    /**
     * Add a local language support (for future updates)
     */
    public function addLocalLanguage(string $code, string $name): void
    {
        $this->supportedLanguages[$code] = $name;
    }

    /**
     * Transcribe audio with language detection
     * Returns array with transcript, detected language, and confidence
     */
    public function transcribeWithLanguageDetection(string $audioContent): array
    {
        try {
            $result = [
                'transcript' => null,
                'detected_language' => null,
                'language_confidence' => null,
                'detected_languages' => null,
            ];

            // Option 1: OpenAI Whisper API (recommandé - meilleure précision)
            if (config('ai.provider') === 'openai') {
                // Whisper détecte automatiquement la langue et peut gérer les langues mixtes
                $httpClient = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                ]);
                
                // Désactiver la vérification SSL uniquement en développement local
                // En production, la vérification SSL est activée pour la sécurité
                if (app()->environment('local')) {
                    $httpClient = $httpClient->withOptions(['verify' => false]);
                }
                
                $response = $httpClient->attach('file', $audioContent, 'audio.ogg')
                  ->post($this->whisperApiUrl, [
                      'model' => 'whisper-1',
                      'language' => null, // null = auto-détection (recommandé pour multilingue)
                      'response_format' => 'verbose_json', // Pour obtenir les métadonnées de langue
                      'temperature' => 0.0, // Plus précis, moins créatif
                  ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $result['transcript'] = $data['text'] ?? null;
                    
                    if (empty($result['transcript'])) {
                        Log::error('Whisper returned empty transcript', [
                            'response' => $data,
                        ]);
                        return $result; // Retourner avec transcript null
                    }
                    
                    // Détection de langue depuis Whisper
                    $detectedLang = strtolower($data['language'] ?? '');
                    $result['detected_language'] = $this->normalizeLanguageCode($detectedLang);
                    $result['language_confidence'] = 0.95; // Whisper est très précis
                    
                    // Vérifier si c'est une langue mixte (analyse du transcript)
                    $mixedLanguages = $this->detectMixedLanguages($result['transcript']);
                    if (count($mixedLanguages) > 1) {
                        $result['detected_language'] = self::MIXED_LANGUAGE_CODE;
                        $result['detected_languages'] = $mixedLanguages;
                    }
                    
                    Log::info('Transcription successful with language detection', [
                        'detected_language' => $result['detected_language'],
                        'confidence' => $result['language_confidence'],
                        'transcript_length' => strlen($result['transcript'] ?? ''),
                    ]);
                    
                    return $result;
                } else {
                    $errorData = $response->json();
                    $errorMessage = $errorData['error']['message'] ?? $response->body();
                    
                    Log::error('Whisper API request failed', [
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'json' => $errorData,
                        'error_message' => $errorMessage,
                    ]);
                    
                    // Retourner une erreur plus claire
                    throw new \Exception('Erreur Whisper API: ' . $errorMessage);
                }
            }

            // Option 2: HuggingFace Whisper
            if (config('ai.provider') === 'huggingface') {
                $httpClient = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                ]);
                
                // Désactiver la vérification SSL uniquement en développement local
                // En production, la vérification SSL est activée pour la sécurité
                if (app()->environment('local')) {
                    $httpClient = $httpClient->withOptions(['verify' => false]);
                }
                
                $response = $httpClient->post($this->whisperApiUrl, [
                    'inputs' => base64_encode($audioContent),
                    'parameters' => [
                        'return_timestamps' => true,
                        'language' => null, // Auto-détection
                    ],
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $result['transcript'] = $data['text'] ?? null;
                    
                    // Détection de langue depuis HuggingFace
                    $detectedLang = strtolower($data['language'] ?? '');
                    $result['detected_language'] = $this->normalizeLanguageCode($detectedLang);
                    $result['language_confidence'] = 0.90;
                    
                    // Vérifier langues mixtes
                    $mixedLanguages = $this->detectMixedLanguages($result['transcript']);
                    if (count($mixedLanguages) > 1) {
                        $result['detected_language'] = self::MIXED_LANGUAGE_CODE;
                        $result['detected_languages'] = $mixedLanguages;
                    }
                    
                    return $result;
                }
            }

            Log::error('Transcription failed', [
                'response' => $response->body() ?? 'No response',
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Error transcribing audio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'transcript' => null,
                'detected_language' => null,
                'language_confidence' => null,
                'detected_languages' => null,
            ];
        }
    }
    
    /**
     * Transcribe audio (backward compatibility)
     */
    public function transcribe(string $audioContent): ?string
    {
        $result = $this->transcribeWithLanguageDetection($audioContent);
        return $result['transcript'];
    }
    
    /**
     * Normalize language code (ISO 639-1)
     */
    private function normalizeLanguageCode(string $code): string
    {
        $code = strtolower($code);
        
        // Mapping des codes de langue
        $mapping = [
            'fr' => 'fr',
            'en' => 'en',
            'fr-FR' => 'fr',
            'en-US' => 'en',
            'en-GB' => 'en',
            // Ajouter d'autres mappings si nécessaire
        ];
        
        return $mapping[$code] ?? $code;
    }
    
    /**
     * Detect mixed languages in transcript
     * Analyse le texte pour détecter si plusieurs langues sont présentes
     */
    private function detectMixedLanguages(?string $transcript): array
    {
        if (empty($transcript)) {
            return [];
        }
        
        $detected = [];
        $text = strtolower($transcript);
        
        // Mots-clés français communs
        $frenchKeywords = ['le', 'la', 'les', 'un', 'une', 'des', 'est', 'sont', 'pour', 'avec', 'dans', 'sur', 'par', 'de', 'du', 'et', 'ou', 'mais', 'donc', 'alors', 'bien', 'très', 'plus', 'moins', 'merci', 'bonjour', 'bonsoir', 'salut'];
        // Mots-clés anglais communs
        $englishKeywords = ['the', 'a', 'an', 'is', 'are', 'for', 'with', 'in', 'on', 'by', 'of', 'and', 'or', 'but', 'so', 'then', 'well', 'very', 'more', 'less', 'thanks', 'hello', 'hi', 'goodbye'];
        
        $frenchCount = 0;
        $englishCount = 0;
        
        foreach ($frenchKeywords as $keyword) {
            if (strpos($text, ' ' . $keyword . ' ') !== false || strpos($text, $keyword . ' ') === 0) {
                $frenchCount++;
            }
        }
        
        foreach ($englishKeywords as $keyword) {
            if (strpos($text, ' ' . $keyword . ' ') !== false || strpos($text, $keyword . ' ') === 0) {
                $englishCount++;
            }
        }
        
        $totalWords = str_word_count($transcript);
        if ($totalWords > 0) {
            $frenchRatio = $frenchCount / max($totalWords, 1);
            $englishRatio = $englishCount / max($totalWords, 1);
            
            if ($frenchRatio > 0.1) {
                $detected[] = [
                    'lang' => 'fr',
                    'confidence' => min($frenchRatio * 5, 1.0), // Normaliser
                ];
            }
            
            if ($englishRatio > 0.1) {
                $detected[] = [
                    'lang' => 'en',
                    'confidence' => min($englishRatio * 5, 1.0),
                ];
            }
        }
        
        return $detected;
    }

    /**
     * Generate summary, actions, and reply using LLM
     * Adapte la langue de la réponse selon la langue détectée
     */
    public function analyzeTranscript(string $transcript, ?string $detectedLanguage = null): array
    {
        try {
            // Déterminer la langue de réponse
            $responseLanguage = $this->getResponseLanguage($detectedLanguage);
            
            // Adapter le prompt selon la langue
            $prompt = $this->buildAnalysisPrompt($transcript, $responseLanguage);

            $httpClient = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ]);
            
            // Désactiver la vérification SSL uniquement en développement local
            // En production, la vérification SSL est activée pour la sécurité
            if (app()->environment('local')) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }
            
            $response = $httpClient->post($this->llmApiUrl, [
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
