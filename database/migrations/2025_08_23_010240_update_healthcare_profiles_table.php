<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('healthcare_profiles', function (Blueprint $table) {
            // Add profession column
            $table->string('profession')
                  ->after('user_id')
                  ->default('doctor'); // default for existing records

            // Remove mmc_number column
            $table->dropColumn('mmc_number');
        });
    }

    public function down(): void
    {
        Schema::table('healthcare_profiles', function (Blueprint $table) {
            // Restore mmc_number if rollback
            $table->string('mmc_number')->nullable()->after('user_id');

            // Drop profession column
            $table->dropColumn('profession');
        });
    }
};
