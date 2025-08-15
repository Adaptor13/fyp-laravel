<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
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
    }
    public function down(): void { Schema::dropIfExists('social_worker_profiles'); }
};
