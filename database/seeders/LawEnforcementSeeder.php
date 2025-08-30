<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\LawEnforcementProfile;
use Illuminate\Support\Facades\Hash;

class LawEnforcementSeeder extends Seeder
{
    public function run(): void
    {
        $lawEnforcementRole = Role::where('name', 'law_enforcement')->first();

        if (!$lawEnforcementRole) {
            $this->command->error('Law Enforcement role not found. Please run RoleSeeder first.');
            return;
        }

        $lawEnforcementOfficers = [
            [
                'name' => 'Detective James Wilson',
                'email' => 'james.wilson@police.gov',
                'agency' => 'Police Department',
                'badge_number' => 'LE-2024-001',
                'rank' => 'Detective',
                'station' => 'Central Police Station',
                'state' => 'IL',
            ],
            [
                'name' => 'Officer Maria Garcia',
                'email' => 'maria.garcia@police.gov',
                'agency' => 'Police Department',
                'badge_number' => 'LE-2024-002',
                'rank' => 'Senior Officer',
                'station' => 'North Police Station',
                'state' => 'IL',
            ],
            [
                'name' => 'Sergeant Robert Davis',
                'email' => 'robert.davis@police.gov',
                'agency' => 'Police Department',
                'badge_number' => 'LE-2024-003',
                'rank' => 'Sergeant',
                'station' => 'South Police Station',
                'state' => 'IL',
            ],
            [
                'name' => 'Detective Jennifer Lee',
                'email' => 'jennifer.lee@police.gov',
                'agency' => 'Police Department',
                'badge_number' => 'LE-2024-004',
                'rank' => 'Detective',
                'station' => 'East Police Station',
                'state' => 'IL',
            ],
            [
                'name' => 'Officer Carlos Martinez',
                'email' => 'carlos.martinez@police.gov',
                'agency' => 'Police Department',
                'badge_number' => 'LE-2024-005',
                'rank' => 'Officer',
                'station' => 'West Police Station',
                'state' => 'IL',
            ],
        ];

        foreach ($lawEnforcementOfficers as $officerData) {
            // Create user
            $user = User::create([
                'name' => $officerData['name'],
                'email' => $officerData['email'],
                'password' => Hash::make('password123'),
                'role_id' => $lawEnforcementRole->id,
                'email_verified_at' => now(),
            ]);

            // Create law enforcement profile
            LawEnforcementProfile::create([
                'user_id' => $user->id,
                'agency' => $officerData['agency'],
                'badge_number' => $officerData['badge_number'],
                'rank' => $officerData['rank'],
                'station' => $officerData['station'],
                'state' => $officerData['state'],
            ]);
        }

        $this->command->info('Law Enforcement users seeded successfully!');
    }
}
