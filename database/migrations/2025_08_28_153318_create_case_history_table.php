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
        Schema::create('case_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('report_id');
            $table->uuid('user_id')->nullable(); // Actor who made the change
            $table->string('action'); // e.g., 'created', 'updated', 'status_changed', 'assigned', etc.
            $table->text('details')->nullable(); // Detailed description of the change
            $table->json('changes')->nullable(); // Store before/after values for specific fields
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Index for better performance
            $table->index(['report_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_history');
    }
};
