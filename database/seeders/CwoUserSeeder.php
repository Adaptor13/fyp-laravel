<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\GovOfficialProfile;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CwoUserSeeder extends Seeder
{
    public function run(): void
    {
        $cwoRoleId = Role::where('name', 'gov_official')->value('id');
        
        if (!$cwoRoleId) {
            $this->command->error('gov_official role not found. Please run RoleSeeder first.');
            return;
        }

        $cwoUsers = [
            [
                'name' => 'Ahmad bin Abdullah',
                'email' => 'ahmad.cwo@kpwkm.gov.my',
                'ministry' => 'KPWKM',
                'department' => 'JKM',
                'service_scheme' => 'M',
                'grade' => 'M41',
                'state' => 'Selangor',
                'phone' => '03-12345678',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Siti binti Mohamed',
                'email' => 'siti.cwo@kpwkm.gov.my',
                'ministry' => 'KPWKM',
                'department' => 'JKM',
                'service_scheme' => 'M',
                'grade' => 'M44',
                'state' => 'Johor',
                'phone' => '07-87654321',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'name' => 'Raj Kumar a/l Muthusamy',
                'email' => 'raj.cwo@kpm.gov.my',
                'ministry' => 'KPM',
                'department' => 'JPNIN',
                'service_scheme' => 'N',
                'grade' => 'N29',
                'state' => 'Penang',
                'phone' => '04-11223344',
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'name' => 'Nor Azizah binti Ismail',
                'email' => 'azizah.cwo@kpwkm.gov.my',
                'ministry' => 'KPWKM',
                'department' => 'JKM',
                'service_scheme' => 'M',
                'grade' => 'M48',
                'state' => 'Kedah',
                'phone' => '04-55667788',
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'name' => 'Lim Chong Wei',
                'email' => 'lim.cwo@kpm.gov.my',
                'ministry' => 'KPM',
                'department' => 'JPNIN',
                'service_scheme' => 'N',
                'grade' => 'N32',
                'state' => 'Perak',
                'phone' => '05-99887766',
                'created_at' => Carbon::now()->subDays(25),
            ],
        ];

        foreach ($cwoUsers as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'role_id' => $cwoRoleId,
                'email_verified_at' => now(),
                'created_at' => $userData['created_at'],
            ]);

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => $userData['phone'],
                'address_line1' => 'Government Office',
                'city' => 'Kuala Lumpur',
                'state' => $userData['state'],
                'postcode' => '50000',
            ]);

            // Create CWO profile
            GovOfficialProfile::create([
                'user_id' => $user->id,
                'ministry' => $userData['ministry'],
                'department' => $userData['department'],
                'service_scheme' => $userData['service_scheme'],
                'grade' => $userData['grade'],
                'state' => $userData['state'],
            ]);
        }

        $this->command->info('CWO users seeded successfully!');
    }
}
