<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('healthcare_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            $table->string('mmc_number', 30)->nullable()->index();  // Malaysian Medical Council reg no.
            $table->date('apc_expiry')->nullable();                 // Annual Practising Certificate expiry
            $table->string('facility_name', 150)->nullable();       // e.g. Hospital Kuala Lumpur
            $table->string('moh_facility_code', 50)->nullable();    // KKM facility code if available
            $table->string('state', 50)->nullable()->index();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('healthcare_profiles'); }
};
