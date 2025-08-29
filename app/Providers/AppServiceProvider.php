<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade directive to check if user has a specific permission
        Blade::if('permission', function ($permission) {
            return Auth::check() && Auth::user()->hasPermission($permission);
        });

        // Blade directive to check if user has any of the given permissions
        Blade::if('anyPermission', function ($permissions) {
            return Auth::check() && Auth::user()->hasAnyPermission($permissions);
        });

        // Blade directive to check if user has all of the given permissions
        Blade::if('allPermissions', function ($permissions) {
            return Auth::check() && Auth::user()->hasAllPermissions($permissions);
        });

        // Blade directive to check if user has a specific role
        Blade::if('role', function ($role) {
            return Auth::check() && Auth::user()->role && Auth::user()->role->name === $role;
        });

        // Blade directive to check if user has any of the given roles
        Blade::if('anyRole', function ($roles) {
            if (!Auth::check() || !Auth::user()->role) {
                return false;
            }
            
            $userRole = Auth::user()->role->name;
            $rolesArray = is_array($roles) ? $roles : explode(',', $roles);
            
            return in_array($userRole, $rolesArray);
        });
    }
}
