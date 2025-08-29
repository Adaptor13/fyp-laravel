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

        // Admin role - all permissions except case creation
        $adminRole = $roles->where('name', 'admin')->first();
        if ($adminRole) {
            $adminPermissions = $permissions->filter(function ($permission) {
                return $permission->slug !== 'cases.create';
            });
            $adminRole->permissions()->sync($adminPermissions->pluck('id'));
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

        // Social Worker - case and report related permissions
        $socialWorkerRole = $roles->where('name', 'social_worker')->first();
        if ($socialWorkerRole) {
            $socialWorkerPermissions = $permissions->filter(function ($permission) {
                return in_array($permission->module, ['cases', 'reports']) ||
                       in_array($permission->slug, ['dashboard.view', 'analytics.view']);
            });
            $socialWorkerRole->permissions()->sync($socialWorkerPermissions->pluck('id'));
        }
        

        // Law Enforcement - case and report related permissions
        $lawEnforcementRole = $roles->where('name', 'law_enforcement')->first();
        if ($lawEnforcementRole) {
            $lawEnforcementPermissions = $permissions->filter(function ($permission) {
                return in_array($permission->module, ['cases', 'reports']) ||
                       in_array($permission->slug, ['dashboard.view', 'analytics.view']);
            });
            $lawEnforcementRole->permissions()->sync($lawEnforcementPermissions->pluck('id'));
        }

        // Healthcare - case and report related permissions
        $healthcareRole = $roles->where('name', 'healthcare')->first();
        if ($healthcareRole) {
            $healthcarePermissions = $permissions->filter(function ($permission) {
                return in_array($permission->module, ['cases', 'reports']) ||
                       in_array($permission->slug, ['dashboard.view', 'analytics.view']);
            });
            $healthcareRole->permissions()->sync($healthcarePermissions->pluck('id'));
        }

        // Public User - limited permissions
        $publicUserRole = $roles->where('name', 'public_user')->first();
        if ($publicUserRole) {
            $publicUserPermissions = $permissions->filter(function ($permission) {
                return in_array($permission->slug, [
                    'reports.create',
                    'reports.view', // Only their own reports
                    'dashboard.view'
                ]);
            });
            $publicUserRole->permissions()->sync($publicUserPermissions->pluck('id'));
        }
    }
}
