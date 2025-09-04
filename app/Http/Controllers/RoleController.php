<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::where('name', '!=', 'admin')
            ->withCount(['users', 'permissions'])
            ->get();
        
        return view('admin.roles.index', compact('roles'));
    }



    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        // Custom validation for role name uniqueness
        $roleName = strtolower(trim(str_replace(' ', '_', $request->name)));
        
        // Additional validation for role name format
        if (empty($roleName) || strlen($roleName) < 2) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Role name must be at least 2 characters long.']);
        }
        
        // Check for invalid characters
        if (!preg_match('/^[a-z0-9_]+$/', $roleName)) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Role name can only contain lowercase letters, numbers, and underscores.']);
        }
        
        \Log::info('Attempting to create role', [
            'input_name' => $request->name,
            'processed_name' => $roleName,
            'existing_roles' => Role::pluck('name')->toArray()
        ]);
        
        // Check if role already exists (case-insensitive)
        if (Role::whereRaw('LOWER(name) = ?', [$roleName])->exists()) {
            \Log::warning('Role creation failed - duplicate name', ['role_name' => $roleName]);
            return back()
                ->withInput()
                ->withErrors(['name' => 'This role name already exists.']);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Role name is required.',
            'name.max' => 'Role name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ]);

        try {
            $role = Role::create([
                'name' => $roleName,
                'description' => $request->description,
            ]);

            \Log::info('Role created successfully', ['role_id' => $role->id, 'role_name' => $role->name]);

            return redirect()->route('roles.index')
                ->with('success', 'Role created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error while creating role', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'role_name' => $roleName
            ]);
            
            // Handle database constraint violations
            if ($e->getCode() == 23000) { // MySQL duplicate entry error
                return back()
                    ->withInput()
                    ->withErrors(['name' => 'This role name already exists.']);
            }
            
            // Handle other database errors
            return back()
                ->withInput()
                ->withErrors(['general' => 'An error occurred while creating the role. Please try again.']);
        } catch (\Exception $e) {
            \Log::error('Unexpected error while creating role', [
                'error' => $e->getMessage(),
                'role_name' => $roleName
            ]);
            
            // Handle any other unexpected errors
            return back()
                ->withInput()
                ->withErrors(['general' => 'An unexpected error occurred. Please try again.']);
        }
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        // Prevent viewing of admin role
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')
                ->with('error', 'The admin role details cannot be viewed.');
        }
        
        $role->load(['users', 'permissions']);
        
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role
     * Note: This method is no longer used since editing is done via modals
     */
    public function edit(Role $role)
    {
        // Redirect to roles index since editing is now done via modals
        return redirect()->route('roles.index')
            ->with('info', 'Role editing is now done via the modal on the roles page.');
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        // Prevent updating of admin role
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')
                ->with('error', 'The admin role cannot be updated.');
        }
        
        // Custom validation for role name uniqueness
        $roleName = strtolower(trim(str_replace(' ', '_', $request->name)));
        
        // Additional validation for role name format
        if (empty($roleName) || strlen($roleName) < 2) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Role name must be at least 2 characters long.']);
        }
        
        // Check for invalid characters
        if (!preg_match('/^[a-z0-9_]+$/', $roleName)) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Role name can only contain lowercase letters, numbers, and underscores.']);
        }
        
        // Check if role already exists (excluding current role, case-insensitive)
        if (Role::whereRaw('LOWER(name) = ?', [$roleName])->where('id', '!=', $role->id)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'This role name already exists.']);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Role name is required.',
            'name.max' => 'Role name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ]);

        try {
            $role->update([
                'name' => $roleName,
                'description' => $request->description,
            ]);

            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if ($e->getCode() == 23000) { // MySQL duplicate entry error
                return back()
                    ->withInput()
                    ->withErrors(['name' => 'This role name already exists.']);
            }
            
            // Handle other database errors
            return back()
                ->withInput()
                ->withErrors(['general' => 'An error occurred while updating the role. Please try again.']);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            return back()
                ->withInput()
                ->withErrors(['general' => 'An unexpected error occurred. Please try again.']);
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of admin role
        if ($role->name === 'admin') {
            return back()->with('error', 'The admin role cannot be deleted.');
        }
        
        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role. It has assigned users.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Show the form for assigning permissions to a role
     */
    public function assignPermissions(Role $role)
    {
        // Prevent assigning permissions to admin role
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')
                ->with('error', 'The admin role permissions cannot be modified.');
        }
        
        $permissions = Permission::orderBy('module')->orderBy('action')->get()
            ->groupBy('module');
        
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.assign-permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update permissions for a role
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $request->input('permissions', []);
        
        $role->permissions()->sync($permissionIds);

        return redirect()->route('roles.index')
            ->with('success', 'Permissions assigned successfully.');
    }

    /**
     * Get roles data for DataTables
     */
    public function getData()
    {
        try {
            $roles = Role::where('name', '!=', 'admin')
                ->withCount(['users', 'permissions'])
                ->get();

            $data = $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->pretty_name,
                    'description' => $role->description ?? 'No description',
                    'users_count' => $role->users_count,
                    'permissions_count' => $role->permissions_count,
                    'created_at' => $role->created_at->format('Y-m-d'),
                ];
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
