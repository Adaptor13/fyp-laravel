<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\PublicUserProfile;
use Illuminate\Support\Facades\Hash;

class PublicUserSeeder extends Seeder
{
    public function run(): void
    {
        $publicUserRole = Role::where('name', 'public_user')->first();

        if (!$publicUserRole) {
            $this->command->error('Public User role not found. Please run RoleSeeder first.');
            return;
        }

        $publicUsers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'display_name' => 'John S.',
                'allow_contact' => true,
            ],
            [
                'name' => 'Maria Rodriguez',
                'email' => 'maria.rodriguez@example.com',
                'display_name' => 'Maria R.',
                'allow_contact' => true,
            ],
            [
                'name' => 'David Johnson',
                'email' => 'david.johnson@example.com',
                'display_name' => 'David J.',
                'allow_contact' => false,
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah.williams@example.com',
                'display_name' => 'Sarah W.',
                'allow_contact' => true,
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@example.com',
                'display_name' => 'Michael B.',
                'allow_contact' => false,
            ],
            [
                'name' => 'Lisa Davis',
                'email' => 'lisa.davis@example.com',
                'display_name' => 'Lisa D.',
                'allow_contact' => true,
            ],
            [
                'name' => 'Robert Wilson',
                'email' => 'robert.wilson@example.com',
                'display_name' => 'Robert W.',
                'allow_contact' => true,
            ],
            [
                'name' => 'Jennifer Garcia',
                'email' => 'jennifer.garcia@example.com',
                'display_name' => 'Jennifer G.',
                'allow_contact' => false,
            ],
        ];

        foreach ($publicUsers as $userData) {
            // Create user
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'role_id' => $publicUserRole->id,
                'email_verified_at' => now(),
            ]);

            // Create public user profile
            PublicUserProfile::create([
                'user_id' => $user->id,
                'display_name' => $userData['display_name'],
                'allow_contact' => $userData['allow_contact'],
            ]);
        }

        $this->command->info('Public users seeded successfully!');
    }
}
