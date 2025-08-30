<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\HealthcareProfile;
use Illuminate\Support\Facades\Hash;

class HealthcareSeeder extends Seeder
{
    public function run(): void
    {
        $healthcareRole = Role::where('name', 'healthcare')->first();

        if (!$healthcareRole) {
            $this->command->error('Healthcare role not found. Please run RoleSeeder first.');
            return;
        }

        $healthcareProfessionals = [
            [
                'name' => 'Dr. Amanda Foster',
                'email' => 'amanda.foster@healthcare.gov',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'City General Hospital',
                'state' => 'IL',
            ],
            [
                'name' => 'Dr. Kevin Patel',
                'email' => 'kevin.patel@healthcare.gov',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Mental Health Center',
                'state' => 'IL',
            ],
            [
                'name' => 'Nurse Sarah Williams',
                'email' => 'sarah.williams@healthcare.gov',
                'profession' => 'nurse',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Emergency Medical Center',
                'state' => 'IL',
            ],
            [
                'name' => 'Dr. Michael Chang',
                'email' => 'michael.chang@healthcare.gov',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Forensic Medical Institute',
                'state' => 'IL',
            ],
            [
                'name' => 'Dr. Lisa Anderson',
                'email' => 'lisa.anderson@healthcare.gov',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Community Health Clinic',
                'state' => 'IL',
            ],
        ];

        foreach ($healthcareProfessionals as $professionalData) {
            // Create user
            $user = User::create([
                'name' => $professionalData['name'],
                'email' => $professionalData['email'],
                'password' => Hash::make('password123'),
                'role_id' => $healthcareRole->id,
                'email_verified_at' => now(),
            ]);

            // Create healthcare profile
            HealthcareProfile::create([
                'user_id' => $user->id,
                'profession' => $professionalData['profession'],
                'apc_expiry' => $professionalData['apc_expiry'],
                'facility_name' => $professionalData['facility_name'],
                'state' => $professionalData['state'],
            ]);
        }

        $this->command->info('Healthcare users seeded successfully!');
    }
}
