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
        Schema::create('audio_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audio_id')->constrained('audios')->onDelete('cascade');
            $table->text('transcript')->nullable();
            $table->text('summary')->nullable();
            $table->json('actions')->nullable(); // Array of actions extracted
            $table->text('generated_reply')->nullable();
            $table->json('confidence_scores')->nullable();
            $table->string('ia_provider')->nullable(); // whisper, openai, huggingface
            $table->timestamps();

            $table->index('audio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_analyses');
    }
};
