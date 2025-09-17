<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\LawEnforcementProfile;
use App\Models\UserProfile;
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
                'name' => 'ASP Ahmad bin Ismail',
                'email' => 'ahmad.ismail@rmp.gov.my',
                'agency' => 'Royal Malaysia Police',
                'badge_number' => 'LE-2024-001',
                'rank' => 'Assistant Superintendent of Police',
                'station' => 'Bukit Aman',
                'state' => 'Kuala Lumpur',
            ],
            [
                'name' => 'Inspector Siti binti Rahman',
                'email' => 'siti.rahman@rmp.gov.my',
                'agency' => 'Royal Malaysia Police',
                'badge_number' => 'LE-2024-002',
                'rank' => 'Inspector',
                'station' => 'Petaling Jaya District Police Headquarters',
                'state' => 'Selangor',
            ],
            [
                'name' => 'Sergeant Raj Kumar a/l Muthusamy',
                'email' => 'raj.kumar@rmp.gov.my',
                'agency' => 'Royal Malaysia Police',
                'badge_number' => 'LE-2024-003',
                'rank' => 'Sergeant',
                'station' => 'Johor Bahru District Police Headquarters',
                'state' => 'Johor',
            ],
            [
                'name' => 'Inspector Lim Wei Ming',
                'email' => 'lim.weiming@rmp.gov.my',
                'agency' => 'Royal Malaysia Police',
                'badge_number' => 'LE-2024-004',
                'rank' => 'Inspector',
                'station' => 'Georgetown District Police Headquarters',
                'state' => 'Penang',
            ],
            [
                'name' => 'Corporal Fatimah binti Abdullah',
                'email' => 'fatimah.abdullah@rmp.gov.my',
                'agency' => 'Royal Malaysia Police',
                'badge_number' => 'LE-2024-005',
                'rank' => 'Corporal',
                'station' => 'Ipoh District Police Headquarters',
                'state' => 'Perak',
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

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => '03-12345678',
                'address_line1' => 'Police Station',
                'city' => $officerData['station'],
                'state' => $officerData['state'],
                'postcode' => '50000',
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
