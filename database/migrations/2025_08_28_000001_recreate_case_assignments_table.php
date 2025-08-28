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
        // Create new table with correct structure
        Schema::create('case_assignments_new', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('report_id');
            $table->uuid('user_id');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('unassigned_at')->nullable();
            
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Allow multiple assignees but ensure unique user per report
            $table->unique(['report_id', 'user_id'], 'unique_report_user_assignment');
            
            $table->timestamps();
        });
        
        // Copy data from old table to new table
        DB::statement('
            INSERT INTO case_assignments_new (id, report_id, user_id, is_primary, assigned_at, unassigned_at, created_at, updated_at)
            SELECT 
                UUID() as id,
                report_id,
                user_id,
                is_primary,
                assigned_at,
                unassigned_at,
                created_at,
                updated_at
            FROM case_assignments
        ');
        
        // Drop old table and rename new table
        Schema::drop('case_assignments');
        Schema::rename('case_assignments_new', 'case_assignments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Create old table structure
        Schema::create('case_assignments_old', function (Blueprint $table) {
            $table->id();
            $table->uuid('report_id');
            $table->uuid('user_id');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('unassigned_at')->nullable();
            
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure a case can only have one primary assignee
            $table->unique(['report_id', 'is_primary'], 'unique_primary_assignee');
            
            $table->timestamps();
        });
        
        // Copy data back (this might lose some data due to the constraint)
        DB::statement('
            INSERT INTO case_assignments_old (report_id, user_id, is_primary, assigned_at, unassigned_at, created_at, updated_at)
            SELECT 
                report_id,
                user_id,
                is_primary,
                assigned_at,
                unassigned_at,
                created_at,
                updated_at
            FROM case_assignments
            WHERE is_primary = 1
            UNION
            SELECT 
                report_id,
                user_id,
                is_primary,
                assigned_at,
                unassigned_at,
                created_at,
                updated_at
            FROM case_assignments
            WHERE is_primary = 0
            LIMIT 1
        ');
        
        // Drop new table and rename old table
        Schema::drop('case_assignments');
        Schema::rename('case_assignments_old', 'case_assignments');
    }
};
