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
];
