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
        Schema::table('healthcare_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('healthcare_profiles', 'moh_facility_code')) {
                $table->dropColumn('moh_facility_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('healthcare_profiles', function (Blueprint $table) {
            $table->string('moh_facility_code')->nullable()->after('facility_name');
        });
    }
};
