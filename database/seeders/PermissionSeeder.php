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
            ['name' => 'Export Users', 'slug' => 'users.export', 'description' => 'Can export user data', 'module' => 'users', 'action' => 'export'],
            
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
            ['name' => 'Export Cases', 'slug' => 'cases.export', 'description' => 'Can export case data', 'module' => 'cases', 'action' => 'export'],
            ['name' => 'View Case History', 'slug' => 'cases.view_history', 'description' => 'Can view case history and audit trail', 'module' => 'cases', 'action' => 'view_history'],
            
            
            // Contact Query Management
            ['name' => 'View Contact Queries', 'slug' => 'contact_queries.view', 'description' => 'Can view contact queries', 'module' => 'contact_queries', 'action' => 'view'],
            ['name' => 'Create Contact Queries', 'slug' => 'contact_queries.create', 'description' => 'Can create new contact queries', 'module' => 'contact_queries', 'action' => 'create'],
            ['name' => 'Edit Contact Queries', 'slug' => 'contact_queries.edit', 'description' => 'Can edit contact queries', 'module' => 'contact_queries', 'action' => 'edit'],
            ['name' => 'Delete Contact Queries', 'slug' => 'contact_queries.delete', 'description' => 'Can delete contact queries', 'module' => 'contact_queries', 'action' => 'delete'],
            ['name' => 'Export Contact Queries', 'slug' => 'contact_queries.export', 'description' => 'Can export contact query data', 'module' => 'contact_queries', 'action' => 'export'],
            
            // Dashboard & Analytics
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'description' => 'Can view admin dashboard', 'module' => 'dashboard', 'action' => 'view'],
            ['name' => 'Export Dashboard Reports', 'slug' => 'dashboard.export', 'description' => 'Can export reports from dashboard', 'module' => 'dashboard', 'action' => 'export'],
            ['name' => 'View Analytics', 'slug' => 'analytics.view', 'description' => 'Can view analytics and statistics', 'module' => 'analytics', 'action' => 'view'],
            
            // System Management
            ['name' => 'System Settings', 'slug' => 'system.settings', 'description' => 'Can access system settings', 'module' => 'system', 'action' => 'settings'],
            ['name' => 'View Logs', 'slug' => 'system.logs', 'description' => 'Can view system logs', 'module' => 'system', 'action' => 'logs'],
            
            // Activity Logs Management
            ['name' => 'View Activity Logs', 'slug' => 'activity_logs.view', 'description' => 'Can view activity logs', 'module' => 'activity_logs', 'action' => 'view'],
            ['name' => 'Export Activity Logs', 'slug' => 'activity_logs.export', 'description' => 'Can export activity logs', 'module' => 'activity_logs', 'action' => 'export'],
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
