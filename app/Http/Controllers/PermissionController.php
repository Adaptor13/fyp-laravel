<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions
     */
    public function index()
    {
        $permissions = Permission::withCount('roles')
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module');
        
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $modules = [
            'users' => 'User Management',
            'roles' => 'Role Management',
            'cases' => 'Case Management',
            'reports' => 'Report Management',
            'contact_queries' => 'Contact Queries Management',
            'dashboard' => 'Dashboard',
            'analytics' => 'Analytics',
            'system' => 'System Management',
        ];

        $actions = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'assign' => 'Assign',
            'update_status' => 'Update Status',
            'add_notes' => 'Add Notes',
            'assign_permissions' => 'Assign Permissions',
            'settings' => 'Settings',
            'logs' => 'View Logs',
        ];

        return view('admin.permissions.create', compact('modules', 'actions'));
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|string|max:100',
            'action' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Permission name is required.',
            'module.required' => 'Module is required.',
            'action.required' => 'Action is required.',
            'name.max' => 'Permission name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ]);

        $slug = $request->module . '.' . $request->action;

        // Check if permission already exists
        if (Permission::where('slug', $slug)->exists()) {
            return back()->withErrors(['slug' => 'A permission with this module and action already exists.'])->withInput();
        }

        $permission = Permission::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'module' => $request->module,
            'action' => $request->action,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit(Permission $permission)
    {
        $modules = [
            'users' => 'User Management',
            'roles' => 'Role Management',
            'cases' => 'Case Management',
            'reports' => 'Report Management',
            'dashboard' => 'Dashboard',
            'analytics' => 'Analytics',
            'system' => 'System Management',
        ];

        $actions = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'assign' => 'Assign',
            'update_status' => 'Update Status',
            'add_notes' => 'Add Notes',
            'assign_permissions' => 'Assign Permissions',
            'settings' => 'Settings',
            'logs' => 'View Logs',
        ];

        return view('admin.permissions.edit', compact('permission', 'modules', 'actions'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|string|max:100',
            'action' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Permission name is required.',
            'module.required' => 'Module is required.',
            'action.required' => 'Action is required.',
            'name.max' => 'Permission name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ]);

        $slug = $request->module . '.' . $request->action;

        // Check if permission already exists (excluding current permission)
        if (Permission::where('slug', $slug)->where('id', '!=', $permission->id)->exists()) {
            return back()->withErrors(['slug' => 'A permission with this module and action already exists.'])->withInput();
        }

        $permission->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'module' => $request->module,
            'action' => $request->action,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete permission. It is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Get permissions data for DataTables
     */
    public function getData()
    {
        $permissions = Permission::withCount('roles')->get();

        $data = $permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'slug' => $permission->slug,
                'module' => ucfirst($permission->module),
                'action' => ucfirst(str_replace('_', ' ', $permission->action)),
                'description' => $permission->description ?? 'No description',
                'roles_count' => $permission->roles_count,
                'created_at' => $permission->created_at->format('M d, Y'),
                'actions' => view('admin.permissions.partials.actions', compact('permission'))->render(),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
