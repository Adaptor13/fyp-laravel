<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get all roles and permissions
        $roles = Role::all();
        $permissions = Permission::all()->keyBy('slug');

        // Admin role - all permissions
        $adminRole = $roles->where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync($permissions->pluck('id'));
        }

        // Government Official - most permissions except role management
        $govOfficialRole = $roles->where('name', 'gov_official')->first();
        if ($govOfficialRole) {
            $govOfficialPermissions = $permissions->filter(function ($permission) {
                return !in_array($permission->module, ['roles']) && 
                       !in_array($permission->slug, ['system.settings', 'system.logs']);
            });
            $govOfficialRole->permissions()->sync($govOfficialPermissions->pluck('id'));
        }

        // Social Worker - case and contact query related permissions
        $socialWorkerRole = $roles->where('name', 'social_worker')->first();
        if ($socialWorkerRole) {
            $socialWorkerPermissions = $permissions->filter(function ($permission) {
                return in_array($permission->module, ['cases', 'contact_queries']) ||
                       in_array($permission->slug, ['dashboard.view', 'dashboard.export', 'analytics.view']);
            });
            $socialWorkerRole->permissions()->sync($socialWorkerPermissions->pluck('id'));
        }
        

        // Law Enforcement - case and contact query related permissions
        $lawEnforcementRole = $roles->where('name', 'law_enforcement')->first();
        if ($lawEnforcementRole) {
            $lawEnforcementPermissions = $permissions->filter(function ($permission) {
                return in_array($permission->module, ['cases', 'contact_queries']) ||
                       in_array($permission->slug, ['dashboard.view', 'dashboard.export', 'analytics.view']);
            });
            $lawEnforcementRole->permissions()->sync($lawEnforcementPermissions->pluck('id'));
        }

        // Healthcare - case and contact query related permissions
        $healthcareRole = $roles->where('name', 'healthcare')->first();
        if ($healthcareRole) {
            $healthcarePermissions = $permissions->filter(function ($permission) {
                return in_array($permission->module, ['cases', 'contact_queries']) ||
                       in_array($permission->slug, ['dashboard.view', 'dashboard.export', 'analytics.view']);
            });
            $healthcareRole->permissions()->sync($healthcarePermissions->pluck('id'));
        }

        // Public User - limited permissions including contact query creation
        $publicUserRole = $roles->where('name', 'public_user')->first();
        if ($publicUserRole) {
            $publicUserPermissions = $permissions->filter(function ($permission) {
                return in_array($permission->slug, [
                    'cases.create',
                    'cases.view', // Only their own cases
                    'contact_queries.create', // Can create contact queries
                    'contact_queries.view', // Can view their own queries
                    'dashboard.view'
                ]);
            });
            $publicUserRole->permissions()->sync($publicUserPermissions->pluck('id'));
        }
    }
}
