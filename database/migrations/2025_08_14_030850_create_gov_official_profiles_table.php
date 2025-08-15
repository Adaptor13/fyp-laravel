<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gov_official_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            // Government official-specific fields
            $table->string('ministry', 150)->nullable();      // e.g. KPWKM, KPM
            $table->string('department', 150)->nullable();    // e.g. JKM, JPNIN
            $table->string('service_scheme', 20)->nullable(); // e.g. M, N, FA
            $table->string('grade', 10)->nullable();          // e.g. M41, N29
            $table->string('state', 50)->nullable()->index(); // Johor, Sarawak, etc.

            $table->timestamps();

            // Link profile to the users table
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('gov_official_profiles');
    }
};
