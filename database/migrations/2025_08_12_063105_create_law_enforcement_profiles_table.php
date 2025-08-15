<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
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
    }
    public function down(): void { Schema::dropIfExists('law_enforcement_profiles'); }
};
