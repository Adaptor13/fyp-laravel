<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Create user_profiles table
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary(); // matches users.id

            $table->string('phone', 20)->nullable();          // e.g. 0123456789 or +60123456789
            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postcode', 5)->nullable()->index();
            $table->string('state', 50)->nullable()->index();

            $table->string('avatar_path', 255)->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });

        // Create law_enforcement_profiles table
        Schema::create('law_enforcement_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            $table->string('agency', 50)->nullable()->index();      // e.g. PDRM, AADK
            $table->string('badge_number', 50)->nullable()->index();
            $table->string('rank', 50)->nullable();                 // e.g. Insp., Sgt.
            $table->string('station', 150)->nullable();             // e.g. IPD Petaling Jaya
            $table->string('state', 50)->nullable()->index();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // Create social_worker_profiles table
        Schema::create('social_worker_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            $table->string('agency_name', 150)->nullable();     // e.g. JKM Daerah Petaling
            $table->string('agency_code', 50)->nullable();      // internal reference code
            $table->string('placement_state', 50)->nullable()->index();
            $table->string('placement_district', 100)->nullable();
            $table->string('staff_id', 50)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // Create public_user_profiles table
        Schema::create('public_user_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            // Public user-specific fields
            $table->string('display_name', 150)->nullable();  // Optional, can just use User->name
            $table->boolean('allow_contact')->default(false); // Whether user consents to be contacted

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });

        // Create healthcare_profiles table
        Schema::create('healthcare_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            $table->string('profession')->default('doctor');  // doctor, nurse, etc.
            $table->date('apc_expiry')->nullable();                 // Annual Practising Certificate expiry
            $table->string('facility_name', 150)->nullable();       // e.g. Hospital Kuala Lumpur
            $table->string('state', 50)->nullable()->index();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // Create admin_profiles table
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary(); // Matches users.id
            $table->string('display_name');
            $table->string('department', 255)->nullable(); // e.g. IT Department
            $table->string('position', 255)->nullable();   // e.g. System Administrator

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });

        // Create gov_official_profiles table
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
        Schema::dropIfExists('admin_profiles');
        Schema::dropIfExists('healthcare_profiles');
        Schema::dropIfExists('public_user_profiles');
        Schema::dropIfExists('social_worker_profiles');
        Schema::dropIfExists('law_enforcement_profiles');
        Schema::dropIfExists('user_profiles');
    }
};
