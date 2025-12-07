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
        Schema::create('whatsapp_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone_number_id');
            $table->string('whatsapp_business_account_id');
            $table->text('access_token'); // Encrypted
            $table->timestamp('token_expires_at')->nullable();
            $table->boolean('webhook_verified')->default(false);
            $table->string('phone_number')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->unique('phone_number_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_connections');
    }
};
