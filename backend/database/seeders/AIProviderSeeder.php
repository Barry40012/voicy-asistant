<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AIProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name' => 'openai',
                'display_name' => 'OpenAI',
                'description' => 'Provider OpenAI avec Whisper pour la transcription et GPT pour l\'analyse',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'api_key' => '',
                ]),
                'config' => json_encode([
                    'whisper_api_url' => 'https://api.openai.com/v1/audio/transcriptions',
                    'llm_api_url' => 'https://api.openai.com/v1/chat/completions',
                    'llm_model' => 'gpt-3.5-turbo',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'huggingface',
                'display_name' => 'HuggingFace',
                'description' => 'Provider HuggingFace avec modèles Whisper open source',
                'is_active' => false,
                'is_default' => false,
                'environment' => 'test',
                'credentials' => json_encode([
                    'api_key' => '',
                ]),
                'config' => json_encode([
                    'whisper_api_url' => 'https://api-inference.huggingface.co/models/openai/whisper-base',
                    'model_name' => 'openai/whisper-base',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($providers as $provider) {
            \App\Models\AIProvider::updateOrCreate(
                ['name' => $provider['name']],
                $provider
            );
        }
    }
}
