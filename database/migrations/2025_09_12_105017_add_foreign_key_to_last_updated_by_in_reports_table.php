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
        Schema::table('reports', function (Blueprint $table) {
            // First, ensure the column is the correct type (UUID)
            $table->uuid('last_updated_by')->nullable()->change();
            
            // Add the foreign key constraint
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['last_updated_by']);
            
            // Optionally change back to string if needed
            $table->string('last_updated_by')->nullable()->change();
        });
    }
};
