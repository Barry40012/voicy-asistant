<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to drop and recreate the check constraint
        // First, drop the existing constraint
        DB::statement("ALTER TABLE subscriptions DROP CONSTRAINT IF EXISTS subscriptions_status_check");
        
        // Add 'pending' to the allowed status values
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT subscriptions_status_check 
            CHECK (status IN ('active', 'cancelled', 'past_due', 'expired', 'pending'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'pending' from allowed status values
        DB::statement("ALTER TABLE subscriptions DROP CONSTRAINT IF EXISTS subscriptions_status_check");
        
        // Restore original constraint without 'pending'
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT subscriptions_status_check 
            CHECK (status IN ('active', 'cancelled', 'past_due', 'expired'))");
    }
};
