<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary(); // matches users.id

            $table->string('phone', 20)->nullable();          // e.g. 0123456789 or +60123456789
            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postcode', 5)->nullable()->index();
            $table->string('state', 50)->nullable()->index();

            $table->string('avatar_path', 255)->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_profiles');
    }
};
