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
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // flutterwave, stripe, orange, mtn, etc.
            $table->string('display_name'); // Flutterwave, Stripe, Orange Money, etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->enum('environment', ['test', 'live'])->default('test');
            $table->json('credentials'); // Stocke toutes les clés API de manière flexible
            $table->json('config')->nullable(); // Configuration supplémentaire (URLs, etc.)
            $table->timestamps();
            
            $table->unique('name');
            $table->index('is_active');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_providers');
    }
};
