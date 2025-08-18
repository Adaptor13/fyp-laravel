<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Ensure the 'admin' role exists
        // If your Role model also auto-generates UUIDs, you can drop the 'id' here.
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['id' => (string) Str::uuid()]
        );

        // 2) Create or fetch the admin user
        // Your User model will auto-UUID the 'id' and auto-hash 'password'.
        $user = User::firstOrCreate(
            ['email' => 'admin@sinda.local'],
            [
                'name'              => 'System Admin',
                'password'          => 'Admin@12345',   // will be hashed by your casts()
                'role_id'           => $adminRole->id,
                'email_verified_at' => now(),
                'remember_token'    => Str::random(60),
            ]
        );

        // 3) If the user existed but with a different role, fix it
        if ($user->role_id !== $adminRole->id) {
            $user->role_id = $adminRole->id;
            $user->save();
        }

        // 4) Optional: create an admin profile if you have that table/model
        // Adjust fields to match your 'admin_profiles' schema or comment out if not needed.
        if (class_exists(\App\Models\AdminProfile::class)) {
            \App\Models\AdminProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['display_name' => 'System Admin']
            );
        }
    }
}
