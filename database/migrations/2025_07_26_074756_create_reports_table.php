<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Optional link to a registered user (null for guests)
            $table->uuid('user_id')->nullable(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('reporter_name')->nullable();
            $table->string('reporter_email')->nullable();
            $table->string('reporter_phone')->nullable();

            $table->string('victim_age')->nullable();
            $table->string('victim_gender')->nullable();
            $table->json('abuse_types')->nullable(); // For multiple selections

            $table->text('incident_description');
            $table->text('incident_location');
            $table->date('incident_date');
            $table->string('suspected_abuser')->nullable();

            $table->json('evidence')->nullable(); // JSON for multiple file uploads
            $table->boolean('confirmed_truth')->default(false);

            // Tracking columns
            $table->string('report_status')->default('Submitted');
            $table->string('last_updated_by')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamp('last_message_at')->nullable(); // Added for message tracking
            $table->string('priority_level')->default('Medium');

            $table->timestamps();
        });

        // Note: Data fix migrations for evidence and abuse_types are not needed
        // since the table is created with proper JSON columns from the start

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
