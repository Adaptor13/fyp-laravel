<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop tables only if they exist
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('message_reads');

        // Cache tables are part of Laravel's database cache driver.
        // Safe to drop if not using that driver.
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create minimal schemas to allow rollback without data.

        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('message_reads')) {
            Schema::create('message_reads', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id')->nullable();
                $table->string('event_type');
                $table->string('description');
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->string('session_id')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }
};


