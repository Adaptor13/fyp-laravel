<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('public_user_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();

            // Public user-specific fields
            $table->string('display_name', 150)->nullable();  // Optional, can just use User->name
            $table->boolean('allow_contact')->default(false); // Whether user consents to be contacted

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('public_user_profiles');
    }
};

