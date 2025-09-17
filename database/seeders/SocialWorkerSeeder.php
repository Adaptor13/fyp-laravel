<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\SocialWorkerProfile;
use App\Models\UserProfile;
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
                'name' => 'Aminah binti Hassan',
                'email' => 'aminah.hassan@jkm.gov.my',
                'agency_name' => 'Jabatan Kebajikan Masyarakat Malaysia',
                'agency_code' => 'JKM-SEL-001',
                'placement_state' => 'Selangor',
                'placement_district' => 'Petaling Jaya',
                'staff_id' => 'SW-2024-001',
            ],
            [
                'name' => 'Raj Kumar a/l Muthusamy',
                'email' => 'raj.kumar@jkm.gov.my',
                'agency_name' => 'Jabatan Kebajikan Masyarakat Malaysia',
                'agency_code' => 'JKM-JHR-002',
                'placement_state' => 'Johor',
                'placement_district' => 'Johor Bahru',
                'staff_id' => 'SW-2024-002',
            ],
            [
                'name' => 'Siti Nurhaliza binti Ahmad',
                'email' => 'siti.nurhaliza@jkm.gov.my',
                'agency_name' => 'Jabatan Kebajikan Masyarakat Malaysia',
                'agency_code' => 'JKM-PNG-003',
                'placement_state' => 'Penang',
                'placement_district' => 'Georgetown',
                'staff_id' => 'SW-2024-003',
            ],
            [
                'name' => 'Lim Wei Ming',
                'email' => 'lim.weiming@jkm.gov.my',
                'agency_name' => 'Jabatan Kebajikan Masyarakat Malaysia',
                'agency_code' => 'JKM-KDH-004',
                'placement_state' => 'Kedah',
                'placement_district' => 'Alor Setar',
                'staff_id' => 'SW-2024-004',
            ],
            [
                'name' => 'Fatimah binti Abdullah',
                'email' => 'fatimah.abdullah@jkm.gov.my',
                'agency_name' => 'Jabatan Kebajikan Masyarakat Malaysia',
                'agency_code' => 'JKM-PRK-005',
                'placement_state' => 'Perak',
                'placement_district' => 'Ipoh',
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

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => '03-12345678',
                'address_line1' => 'JKM Office',
                'city' => $socialWorkerData['placement_district'],
                'state' => $socialWorkerData['placement_state'],
                'postcode' => '50000',
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
