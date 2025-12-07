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
        Schema::table('audio_analyses', function (Blueprint $table) {
            $table->string('detected_language', 10)->nullable()->after('transcript'); // Ex: 'fr', 'en', 'mixed'
            $table->decimal('language_confidence', 5, 2)->nullable()->after('detected_language'); // 0.00 à 1.00
            $table->json('detected_languages')->nullable()->after('language_confidence'); // Pour langues mixtes: [{'lang': 'fr', 'confidence': 0.7}, {'lang': 'en', 'confidence': 0.3}]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audio_analyses', function (Blueprint $table) {
            $table->dropColumn(['detected_language', 'language_confidence', 'detected_languages']);
        });
    }
};
