<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 'openai', 'huggingface'
            $table->string('display_name'); // 'OpenAI', 'HuggingFace'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->enum('environment', ['test', 'live'])->default('test');
            $table->json('credentials')->nullable(); // API keys, tokens, etc.
            $table->json('config')->nullable(); // URLs, models, etc.
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_providers');
    }
};
