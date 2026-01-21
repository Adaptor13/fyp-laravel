<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Debug Permission Issue ===\n";

// Get law enforcement users
$lawEnforcementUsers = User::whereHas('role', function($query) {
    $query->where('name', 'law_enforcement');
})->with('role.permissions')->get();

echo "Law Enforcement Users:\n";
foreach ($lawEnforcementUsers as $user) {
    echo "User: {$user->name} (ID: {$user->id})\n";
    echo "Role: " . ($user->role ? $user->role->name : 'No role') . "\n";
    
    if ($user->role) {
        echo "Role ID: {$user->role->id}\n";
        echo "Permissions count: " . $user->role->permissions->count() . "\n";
        
        // Check if users.view permission exists
        $usersViewPermission = Permission::where('slug', 'users.view')->first();
        if ($usersViewPermission) {
            echo "users.view permission exists (ID: {$usersViewPermission->id})\n";
            
            // Check if role has this permission
            $hasPermission = $user->role->permissions()->where('slug', 'users.view')->exists();
            echo "Role has users.view: " . ($hasPermission ? 'YES' : 'NO') . "\n";
            
            // Test the hasPermission method
            $userHasPermission = $user->hasPermission('users.view');
            echo "User hasPermission('users.view'): " . ($userHasPermission ? 'YES' : 'NO') . "\n";
        } else {
            echo "users.view permission does NOT exist in database\n";
        }
    }
    echo "---\n";
}

echo "\n=== All users.view permissions in database ===\n";
$usersViewPermissions = Permission::where('slug', 'users.view')->get();
foreach ($usersViewPermissions as $perm) {
    echo "Permission ID: {$perm->id}, Slug: {$perm->slug}, Name: {$perm->name}\n";
}

echo "\n=== Role-Permission relationships for users.view ===\n";
$usersViewPermission = Permission::where('slug', 'users.view')->first();
if ($usersViewPermission) {
    $rolesWithPermission = $usersViewPermission->roles;
    echo "Roles with users.view permission:\n";
    foreach ($rolesWithPermission as $role) {
        echo "- {$role->name} (ID: {$role->id})\n";
    }
}

