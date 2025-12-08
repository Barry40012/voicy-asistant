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
            Log::error('Error in analyzeTranscript', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'summary' => null,
                'actions' => [],
                'reply' => null,
            ];
        }
    }
    
    /**
     * Improve newsletter content using AI
     * Améliore le sujet et le contenu d'une newsletter pour la rendre plus claire, motivante et professionnelle
     */
    public function improveNewsletterContent(string $subject, string $content, ?string $language = 'fr'): array
    {
        try {
            // Vérifier que l'API key est configurée
            if (empty($this->apiKey)) {
                Log::error('Newsletter improvement: API key not configured');
                return [
                    'subject' => $subject,
                    'content' => $content,
                    'improvements' => [],
                    'error' => 'Clé API non configurée. Veuillez configurer OpenAI dans les paramètres.',
                ];
            }
            
            // Déterminer la langue
            $responseLanguage = $language === 'en' ? 'English' : 'Français';
            
            // Construire le prompt pour améliorer la newsletter
            $prompt = $this->buildNewsletterImprovementPrompt($subject, $content, $responseLanguage);

            $provider = config('ai.provider', 'openai');
            
            Log::info('Newsletter improvement: Sending request', [
                'provider' => $provider,
                'subject_length' => strlen($subject),
                'content_length' => strlen($content),
                'language' => $language,
            ]);

            $httpClient = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ]);
            
            // Désactiver la vérification SSL uniquement en développement local
            if (app()->environment('local')) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }
            
            // Adapter la requête selon le provider
            if ($provider === 'huggingface') {
                // HuggingFace utilise un format différent
                $fullPrompt = "Tu es un expert en rédaction de newsletters professionnelles et engageantes. Tu améliores les textes pour les rendre plus clairs, motivants, bien structurés et professionnels. Tu réponds UNIQUEMENT en JSON valide, sans markdown, sans code blocks.\n\n" . $prompt;
                
                $response = $httpClient->post($this->llmApiUrl, [
                    'inputs' => $fullPrompt,
                    'parameters' => [
                        'max_new_tokens' => config('ai.llm_max_tokens', 2000),
                        'temperature' => 0.3,
                        'return_full_text' => false,
                    ],
                ]);
            } else {
                // OpenAI (format standard)
                $response = $httpClient->post($this->llmApiUrl, [
                    'model' => config('ai.llm_model', 'gpt-3.5-turbo'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Tu es un expert en rédaction de newsletters professionnelles et engageantes. Tu améliores les textes pour les rendre plus clairs, motivants, bien structurés et professionnels. Tu réponds UNIQUEMENT en JSON valide, sans markdown, sans code blocks, sans texte avant ou après le JSON.",
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.3, // Plus bas pour plus de cohérence dans le format JSON
                    'max_tokens' => config('ai.llm_max_tokens', 2000),
                ]);
            }

            if ($response->successful()) {
                $responseData = $response->json();
                $provider = config('ai.provider', 'openai');
                
                // Adapter le parsing selon le provider
                if ($provider === 'huggingface') {
                    // HuggingFace retourne directement le texte généré
                    $aiContent = $responseData[0]['generated_text'] ?? $responseData['generated_text'] ?? null;
                    
                    if (!$aiContent) {
                        Log::error('Newsletter improvement: Empty content from HuggingFace', [
                            'response' => $responseData,
                        ]);
                        return [
                            'subject' => $subject,
                            'content' => $content,
                            'improvements' => [],
                            'error' => 'L\'IA n\'a pas retourné de contenu.',
                        ];
                    }
                } else {
                    // OpenAI format
                    // Vérifier la structure de la réponse
                    if (!isset($responseData['choices']) || !isset($responseData['choices'][0])) {
                        Log::error('Newsletter improvement: Invalid response structure', [
                            'response' => $responseData,
                        ]);
                        return [
                            'subject' => $subject,
                            'content' => $content,
                            'improvements' => [],
                            'error' => 'Format de réponse invalide de l\'API.',
                        ];
                    }
                    
                    $aiContent = $responseData['choices'][0]['message']['content'] ?? null;
                }
                
                if (!$aiContent) {
                    Log::error('Newsletter improvement: Empty content from AI', [
                        'response' => $responseData,
                    ]);
                    return [
                        'subject' => $subject,
                        'content' => $content,
                        'improvements' => [],
                        'error' => 'L\'IA n\'a pas retourné de contenu.',
                    ];
                }
                
                // Nettoyer le contenu (enlever markdown code blocks si présents)
                $aiContent = trim($aiContent);
                $aiContent = preg_replace('/^```json\s*/', '', $aiContent);
                $aiContent = preg_replace('/^```\s*/', '', $aiContent);
                $aiContent = preg_replace('/\s*```$/', '', $aiContent);
                $aiContent = trim($aiContent);
                
                // Essayer de parser le JSON
                $decoded = json_decode($aiContent, true);
                
                // Si le JSON n'est pas valide, essayer de parser le texte directement
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::warning('Newsletter improvement: JSON parsing failed, trying text parsing', [
                        'json_error' => json_last_error_msg(),
                        'content' => substr($aiContent, 0, 200),
                    ]);
                    // Essayer de trouver le sujet et le contenu améliorés dans le texte
                    return $this->parseImprovedContent($aiContent, $subject, $content);
                }
                
                // Vérifier que les données essentielles sont présentes
                if (empty($decoded['subject']) && empty($decoded['content'])) {
                    Log::warning('Newsletter improvement: Empty decoded data', [
                        'decoded' => $decoded,
                    ]);
                    // Essayer le parsing de texte en fallback
                    return $this->parseImprovedContent($aiContent, $subject, $content);
                }
                
                return [
                    'subject' => $decoded['subject'] ?? $subject,
                    'content' => $decoded['content'] ?? $content,
                    'improvements' => $decoded['improvements'] ?? [],
                ];
            }

            // Gérer les erreurs HTTP
            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? $response->body() ?? 'Erreur inconnue';
            
            Log::error('Newsletter improvement failed', [
                'status' => $response->status(),
                'response' => $response->body(),
                'error_data' => $errorData,
                'error_message' => $errorMessage,
            ]);

            // Messages d'erreur plus spécifiques
            $userMessage = 'Impossible d\'améliorer le contenu.';
            if (strpos($errorMessage, 'quota') !== false || strpos($errorMessage, 'billing') !== false || strpos($errorMessage, 'insufficient_quota') !== false) {
                $userMessage = 'Quota API dépassé. Vérifiez votre abonnement OpenAI ou utilisez HuggingFace comme alternative. Consultez GUIDE_QUOTA_OPENAI.md pour plus d\'informations.';
            } elseif (strpos($errorMessage, 'invalid') !== false || strpos($errorMessage, 'key') !== false || strpos($errorMessage, 'invalid_api_key') !== false) {
                $userMessage = 'Clé API invalide. Vérifiez votre configuration OpenAI dans /admin/ai-providers.';
            } elseif ($response->status() === 429) {
                $userMessage = 'Trop de requêtes. Veuillez réessayer dans quelques instants.';
            } elseif (strpos($errorMessage, 'rate_limit') !== false) {
                $userMessage = 'Limite de débit atteinte. Réessayez dans quelques secondes.';
            }

            return [
                'subject' => $subject,
                'content' => $content,
                'improvements' => [],
                'error' => $userMessage,
            ];
        } catch (\Exception $e) {
            Log::error('Error improving newsletter content', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'subject' => $subject,
                'content' => $content,
                'improvements' => [],
                'error' => 'Erreur lors de l\'amélioration: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Build prompt for newsletter improvement
     */
    private function buildNewsletterImprovementPrompt(string $subject, string $content, string $language): string
    {
        $langInstructions = $language === 'English' 
            ? 'Respond in English. Make the text clear, engaging, professional, and well-structured.'
            : 'Réponds en français. Rends le texte plus clair, motivant, professionnel et bien structuré.';
        
        return <<<PROMPT
Tu es un expert en rédaction de newsletters professionnelles. Améliore le sujet et le contenu de cette newsletter pour la rendre plus claire, motivante, bien structurée et professionnelle.

Sujet actuel: {$subject}

Contenu actuel:
{$content}

Instructions:
- Améliore le sujet pour qu'il soit accrocheur et clair
- Améliore le contenu pour qu'il soit bien structuré, motivant et professionnel
- Utilise du HTML pour formater le texte (titres, listes, paragraphes)
- Garde le même message principal mais améliore la présentation
- Rends le texte plus engageant et actionnable
- Assure-toi que le texte est bien organisé avec des sections claires

{$langInstructions}

IMPORTANT: Réponds UNIQUEMENT avec un JSON valide, sans markdown, sans code blocks, sans texte avant ou après. Format exact:
{"subject": "Sujet amélioré", "content": "Contenu amélioré en HTML", "improvements": ["Amélioration 1", "Amélioration 2"]}
PROMPT;
    }
    
    /**
     * Parse improved content from AI response if JSON parsing fails
     */
    private function parseImprovedContent(string $aiResponse, string $originalSubject, string $originalContent): array
    {
        // Essayer d'extraire le JSON du texte (peut être entouré de markdown ou autre)
        // Chercher des blocs JSON entre accolades
        if (preg_match('/\{[^{}]*"subject"[^{}]*\}/s', $aiResponse, $matches)) {
            $jsonStr = $matches[0];
            $decoded = json_decode($jsonStr, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['subject'])) {
                return [
                    'subject' => $decoded['subject'] ?? $originalSubject,
                    'content' => $decoded['content'] ?? $originalContent,
                    'improvements' => $decoded['improvements'] ?? [],
                ];
            }
        }
        
        // Essayer d'extraire le sujet et le contenu du texte
        $lines = explode("\n", $aiResponse);
        $improvedSubject = $originalSubject;
        $improvedContent = $originalContent;
        $improvements = [];
        $inContent = false;
        $contentLines = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Chercher le sujet
            if ((stripos($line, 'sujet') !== false || stripos($line, 'subject') !== false) && 
                (stripos($line, ':') !== false || stripos($line, '-') !== false)) {
                $improvedSubject = preg_replace('/.*(?:sujet|subject)[:\-]\s*/i', '', $line);
                $improvedSubject = trim($improvedSubject, ' "\'');
                continue;
            }
            
            // Chercher le contenu
            if (stripos($line, 'contenu') !== false || stripos($line, 'content') !== false) {
                $inContent = true;
                continue;
            }
            
            if ($inContent || count($contentLines) > 0) {
                $contentLines[] = $line;
            }
        }
        
        // Si on a trouvé du contenu, l'utiliser
        if (!empty($contentLines)) {
            $improvedContent = implode("\n", $contentLines);
        } else {
            // Sinon, utiliser toute la réponse comme contenu amélioré
            $improvedContent = $aiResponse;
        }
        
        // Nettoyer le contenu (enlever les balises markdown si présentes)
        $improvedContent = preg_replace('/```json\s*/', '', $improvedContent);
        $improvedContent = preg_replace('/```\s*/', '', $improvedContent);
        $improvedContent = trim($improvedContent);
        
        return [
            'subject' => $improvedSubject ?: $originalSubject,
            'content' => $improvedContent ?: $originalContent,
            'improvements' => $improvements,
        ];
    }
    
    /**
     * Get response language helper
     */
    private function getResponseLanguage(?string $detectedLanguage): string
    {
        if ($detectedLanguage === 'en') {
            return 'English';
        }
        return 'Français'; // Par défaut en français
    }
    
    /**
     * Build analysis prompt
     */
    private function buildAnalysisPrompt(string $transcript, string $responseLanguage): string
    {
        if ($responseLanguage === 'English') {
            return <<<PROMPT
Analyze this audio transcript and provide:
1. A clear summary of the main points
2. Action items or tasks mentioned
3. A professional reply suggestion

Transcript:
{$transcript}

Respond in JSON format:
{
    "summary": "Brief summary",
    "actions": ["action1", "action2"],
    "reply": "Suggested reply"
}
PROMPT;
        }
        
        return <<<PROMPT
Analyse cette transcription audio et fournis :
1. Un résumé clair des points principaux
2. Les actions ou tâches mentionnées
3. Une suggestion de réponse professionnelle

Transcription :
{$transcript}

Réponds en format JSON :
{
    "summary": "Résumé bref",
    "actions": ["action1", "action2"],
    "reply": "Réponse suggérée"
}
PROMPT;
    }
}
