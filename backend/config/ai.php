<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    */

    'provider' => env('AI_PROVIDER', 'openai'), // openai, huggingface

    'api_key' => env('AI_API_KEY'),

    'whisper_api_url' => env('AI_WHISPER_API_URL', 'https://api.openai.com/v1/audio/transcriptions'),
    'llm_api_url' => env('AI_LLM_API_URL', 'https://api.openai.com/v1/chat/completions'),
    'llm_model' => env('AI_LLM_MODEL', 'gpt-3.5-turbo'),
    
    /*
    |--------------------------------------------------------------------------
    | Language Support
    |--------------------------------------------------------------------------
    */
    
    // Langues supportées actuellement (internationales)
    'supported_languages' => [
        'fr' => 'Français',
        'en' => 'Anglais',
    ],
    
    // Langues locales (à activer progressivement)
    'local_languages' => [
        // 'sw' => 'Swahili',
        // 'wo' => 'Wolof',
        // 'ff' => 'Fulfulde',
        // 'dy' => 'Dyula',
    ],
    
    // Précision de transcription (0.0 = très précis, 1.0 = plus créatif)
    'transcription_temperature' => env('AI_TRANSCRIPTION_TEMPERATURE', 0.0),
    
    // Format de réponse Whisper (verbose_json pour obtenir les métadonnées de langue)
    'whisper_response_format' => env('AI_WHISPER_RESPONSE_FORMAT', 'verbose_json'),
];
