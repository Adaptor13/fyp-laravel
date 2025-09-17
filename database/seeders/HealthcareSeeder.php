<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\HealthcareProfile;
use App\Models\UserProfile;
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
                'name' => 'Dr. Aminah binti Hassan',
                'email' => 'aminah.hassan@moh.gov.my',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Hospital Kuala Lumpur',
                'state' => 'Kuala Lumpur',
            ],
            [
                'name' => 'Dr. Raj Kumar a/l Muthusamy',
                'email' => 'raj.kumar@moh.gov.my',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Hospital Sultanah Aminah',
                'state' => 'Johor',
            ],
            [
                'name' => 'Sister Siti binti Rahman',
                'email' => 'siti.rahman@moh.gov.my',
                'profession' => 'nurse',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Hospital Seberang Jaya',
                'state' => 'Penang',
            ],
            [
                'name' => 'Dr. Lim Wei Ming',
                'email' => 'lim.weiming@moh.gov.my',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Hospital Raja Permaisuri Bainun',
                'state' => 'Perak',
            ],
            [
                'name' => 'Dr. Fatimah binti Abdullah',
                'email' => 'fatimah.abdullah@moh.gov.my',
                'profession' => 'doctor',
                'apc_expiry' => '2025-12-31',
                'facility_name' => 'Hospital Sultanah Bahiyah',
                'state' => 'Kedah',
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

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => '03-12345678',
                'address_line1' => 'Hospital',
                'city' => $professionalData['facility_name'],
                'state' => $professionalData['state'],
                'postcode' => '50000',
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
