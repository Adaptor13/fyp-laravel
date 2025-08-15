<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary(); // Matches users.id

            // Admin-specific fields
            $table->string('department', 255)->nullable(); // e.g. IT Department
            $table->string('position', 255)->nullable();   // e.g. System Administrator

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('admin_profiles');
    }
};
