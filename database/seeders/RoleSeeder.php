<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'public_user',
            'social_worker',
            'law_enforcement',
            'gov_official',
            'healthcare',
            'admin',
        ];

       foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

    }
}
