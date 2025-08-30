<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\SocialWorkerProfile;
use Illuminate\Support\Facades\Hash;

class SocialWorkerSeeder extends Seeder
{
    public function run(): void
    {
        $socialWorkerRole = Role::where('name', 'social_worker')->first();

        if (!$socialWorkerRole) {
            $this->command->error('Social Worker role not found. Please run RoleSeeder first.');
            return;
        }

        $socialWorkers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@socialservices.gov',
                'agency_name' => 'Department of Social Services',
                'agency_code' => 'DSS-001',
                'placement_state' => 'IL',
                'placement_district' => 'Downtown District',
                'staff_id' => 'SW-2024-001',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@socialservices.gov',
                'agency_name' => 'Department of Social Services',
                'agency_code' => 'DSS-002',
                'placement_state' => 'IL',
                'placement_district' => 'North District',
                'staff_id' => 'SW-2024-002',
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@socialservices.gov',
                'agency_name' => 'Department of Social Services',
                'agency_code' => 'DSS-003',
                'placement_state' => 'IL',
                'placement_district' => 'South District',
                'staff_id' => 'SW-2024-003',
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.thompson@socialservices.gov',
                'agency_name' => 'Department of Social Services',
                'agency_code' => 'DSS-004',
                'placement_state' => 'IL',
                'placement_district' => 'East District',
                'staff_id' => 'SW-2024-004',
            ],
            [
                'name' => 'Lisa Park',
                'email' => 'lisa.park@socialservices.gov',
                'agency_name' => 'Department of Social Services',
                'agency_code' => 'DSS-005',
                'placement_state' => 'IL',
                'placement_district' => 'West District',
                'staff_id' => 'SW-2024-005',
            ],
        ];

        foreach ($socialWorkers as $socialWorkerData) {
            // Create user
            $user = User::create([
                'name' => $socialWorkerData['name'],
                'email' => $socialWorkerData['email'],
                'password' => Hash::make('password123'),
                'role_id' => $socialWorkerRole->id,
                'email_verified_at' => now(),
            ]);

            // Create social worker profile
            SocialWorkerProfile::create([
                'user_id' => $user->id,
                'agency_name' => $socialWorkerData['agency_name'],
                'agency_code' => $socialWorkerData['agency_code'],
                'placement_state' => $socialWorkerData['placement_state'],
                'placement_district' => $socialWorkerData['placement_district'],
                'staff_id' => $socialWorkerData['staff_id'],
            ]);
        }

        $this->command->info('Social Worker users seeded successfully!');
    }
}
