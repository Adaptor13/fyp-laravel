<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'description' => 'Can view user lists', 'module' => 'users', 'action' => 'view'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'description' => 'Can create new users', 'module' => 'users', 'action' => 'create'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'description' => 'Can edit existing users', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'description' => 'Can delete users', 'module' => 'users', 'action' => 'delete'],
            
            // Role Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'description' => 'Can view role lists', 'module' => 'roles', 'action' => 'view'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'description' => 'Can create new roles', 'module' => 'roles', 'action' => 'create'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'description' => 'Can edit existing roles', 'module' => 'roles', 'action' => 'edit'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'description' => 'Can delete roles', 'module' => 'roles', 'action' => 'delete'],
            ['name' => 'Assign Permissions', 'slug' => 'roles.assign_permissions', 'description' => 'Can assign permissions to roles', 'module' => 'roles', 'action' => 'assign_permissions'],
            
            // Case Management
            ['name' => 'View Cases', 'slug' => 'cases.view', 'description' => 'Can view case lists', 'module' => 'cases', 'action' => 'view'],
            ['name' => 'Create Cases', 'slug' => 'cases.create', 'description' => 'Can create new cases', 'module' => 'cases', 'action' => 'create'],
            ['name' => 'Edit Cases', 'slug' => 'cases.edit', 'description' => 'Can edit existing cases', 'module' => 'cases', 'action' => 'edit'],
            ['name' => 'Delete Cases', 'slug' => 'cases.delete', 'description' => 'Can delete cases', 'module' => 'cases', 'action' => 'delete'],
            
            // Report Management
            ['name' => 'View Reports', 'slug' => 'reports.view', 'description' => 'Can view reports', 'module' => 'reports', 'action' => 'view'],
            ['name' => 'Create Reports', 'slug' => 'reports.create', 'description' => 'Can create new reports', 'module' => 'reports', 'action' => 'create'],
            ['name' => 'Edit Reports', 'slug' => 'reports.edit', 'description' => 'Can edit existing reports', 'module' => 'reports', 'action' => 'edit'],
            ['name' => 'Delete Reports', 'slug' => 'reports.delete', 'description' => 'Can delete reports', 'module' => 'reports', 'action' => 'delete'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'description' => 'Can export report data', 'module' => 'reports', 'action' => 'export'],
            
            // Dashboard & Analytics
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'description' => 'Can view admin dashboard', 'module' => 'dashboard', 'action' => 'view'],
            ['name' => 'View Analytics', 'slug' => 'analytics.view', 'description' => 'Can view analytics and statistics', 'module' => 'analytics', 'action' => 'view'],
            
            // System Management
            ['name' => 'System Settings', 'slug' => 'system.settings', 'description' => 'Can access system settings', 'module' => 'system', 'action' => 'settings'],
            ['name' => 'View Logs', 'slug' => 'system.logs', 'description' => 'Can view system logs', 'module' => 'system', 'action' => 'logs'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $permission['name'],
                    'description' => $permission['description'],
                    'module' => $permission['module'],
                    'action' => $permission['action'],
                ]
            );
        }
    }
}
