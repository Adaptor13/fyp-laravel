# Roles & Permissions RBAC System

This document describes the Role-Based Access Control (RBAC) system implemented for the Laravel 12 Child Welfare Reporting System.

## Overview

The RBAC system provides a comprehensive solution for managing user roles and permissions, allowing fine-grained control over what users can access and perform within the system.

## Features

### Core Features
- **Role Management**: Create, edit, delete, and view roles
- **Permission Management**: Create, edit, delete, and view permissions
- **Permission Assignment**: Assign permissions to roles through an intuitive checkbox interface
- **User-Role Association**: Users are assigned to roles (one-to-many relationship)
- **Permission Checking**: Middleware and Blade directives for permission-based access control

### User Interface
- **Modern UI**: Clean, responsive interface using Bootstrap and Tabler Icons
- **DataTables Integration**: Sortable and searchable role/permission lists
- **Checkbox Grid**: Visual permission assignment interface with module grouping
- **Breadcrumb Navigation**: Clear navigation paths
- **Success/Error Messages**: User-friendly feedback

## Database Structure

### Tables

#### `roles`
- `id` (UUID, Primary Key)
- `name` (String, Unique)
- `description` (Text, Nullable)
- `created_at`, `updated_at` (Timestamps)

#### `permissions`
- `id` (UUID, Primary Key)
- `name` (String)
- `slug` (String, Unique) - Format: `module.action`
- `description` (Text, Nullable)
- `module` (String) - e.g., 'users', 'cases', 'reports'
- `action` (String) - e.g., 'view', 'create', 'edit', 'delete'
- `created_at`, `updated_at` (Timestamps)

#### `role_permissions` (Pivot Table)
- `role_id` (UUID, Foreign Key)
- `permission_id` (UUID, Foreign Key)
- `created_at`, `updated_at` (Timestamps)

## Models

### Role Model (`app/Models/Role.php`)
```php
// Relationships
public function users()
public function permissions()

// Permission checking methods
public function hasPermission($permission)
public function hasAnyPermission($permissions)
public function hasAllPermissions($permissions)

// Accessor
public function getPrettyNameAttribute()
```

### Permission Model (`app/Models/Permission.php`)
```php
// Relationships
public function roles()

// Accessors
public function getPrettyNameAttribute()
public function getModuleActionAttribute()
```

### User Model (`app/Models/User.php`)
```php
// Permission checking methods
public function hasPermission($permission)
public function hasAnyPermission($permissions)
public function hasAllPermissions($permissions)
public function getAllPermissions()
```

## Controllers

### RoleController (`app/Http/Controllers/RoleController.php`)
- `index()` - List all roles
- `create()` - Show create form
- `store()` - Create new role
- `show()` - Display role details
- `edit()` - Show edit form
- `update()` - Update role
- `destroy()` - Delete role
- `assignPermissions()` - Show permission assignment form
- `updatePermissions()` - Update role permissions
- `getData()` - AJAX data for DataTables

### PermissionController (`app/Http/Controllers/PermissionController.php`)
- `index()` - List all permissions (grouped by module)
- `create()` - Show create form
- `store()` - Create new permission
- `show()` - Display permission details
- `edit()` - Show edit form
- `update()` - Update permission
- `destroy()` - Delete permission
- `getData()` - AJAX data for DataTables

## Middleware

### RoleMiddleware (`app/Http/Middleware/RoleMiddleware.php`)
Checks if user has the required role(s):
```php
Route::middleware('role:admin')->group(function () {
    // Admin-only routes
});

Route::middleware('role:admin,social_worker')->group(function () {
    // Routes for admin or social worker
});
```

### PermissionMiddleware (`app/Http/Middleware/PermissionMiddleware.php`)
Checks if user has the required permission(s):
```php
Route::middleware('permission:users.create')->group(function () {
    // Routes for users with 'users.create' permission
});

Route::middleware('permission:cases.view,cases.edit')->group(function () {
    // Routes for users with either 'cases.view' or 'cases.edit' permission
});
```

## Blade Directives

The system provides convenient Blade directives for permission checking in views:

### @permission
```blade
@permission('users.create')
    <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
@endpermission
```

### @anyPermission
```blade
@anyPermission(['cases.view', 'cases.edit'])
    <div class="case-actions">
        <!-- Show case actions -->
    </div>
@endanyPermission
```

### @allPermissions
```blade
@allPermissions(['reports.create', 'reports.edit'])
    <div class="full-report-access">
        <!-- Show full report access -->
    </div>
@endallPermissions
```

### @role
```blade
@role('admin')
    <div class="admin-panel">
        <!-- Admin-specific content -->
    </div>
@endrole
```

### @anyRole
```blade
@anyRole(['admin', 'gov_official'])
    <div class="management-panel">
        <!-- Management content -->
    </div>
@endanyRole
```

## Routes

### Role Management Routes
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
    Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
    Route::get('/roles/data', [RoleController::class, 'getData'])->name('roles.data');
});
```

### Permission Management Routes
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::get('/permissions/data', [PermissionController::class, 'getData'])->name('permissions.data');
});
```

## Default Permissions

The system comes with pre-configured permissions organized by modules:

### User Management
- `users.view` - View user lists
- `users.create` - Create new users
- `users.edit` - Edit existing users
- `users.delete` - Delete users

### Role Management
- `roles.view` - View role lists
- `roles.create` - Create new roles
- `roles.edit` - Edit existing roles
- `roles.delete` - Delete roles
- `roles.assign_permissions` - Assign permissions to roles

### Case Management
- `cases.view` - View case lists
- `cases.view_all` - View all cases regardless of assignment
- `cases.create` - Create new cases
- `cases.edit` - Edit existing cases
- `cases.delete` - Delete cases
- `cases.assign` - Assign cases to users
- `cases.update_status` - Update case status
- `cases.add_notes` - Add notes to cases
- `cases.export` - Export case data

### Report Management
- `reports.view` - View reports
- `reports.create` - Create new reports
- `reports.edit` - Edit existing reports
- `reports.delete` - Delete reports
- `reports.export` - Export report data

### Dashboard & Analytics
- `dashboard.view` - View admin dashboard
- `analytics.view` - View analytics and statistics

### System Management
- `system.settings` - Access system settings
- `system.logs` - View system logs

## Default Role-Permission Assignments

### Admin Role
- All permissions

### Government Official Role
- All permissions except role management and system management

### Social Worker, Law Enforcement, Healthcare Roles
- Case and report related permissions
- Dashboard and analytics access

### Public User Role
- Limited permissions: `reports.create`, `reports.view`, `dashboard.view`

## Installation & Setup

1. **Run Migrations**
   ```bash
   php artisan migrate
   ```

2. **Seed Permissions and Role Assignments**
   ```bash
   php artisan db:seed --class=PermissionSeeder
   php artisan db:seed --class=RolePermissionSeeder
   ```

3. **Register Middleware** (already done in `bootstrap/app.php`)
   ```php
   $middleware->alias([
       'role' => \App\Http\Middleware\RoleMiddleware::class,
       'permission' => \App\Http\Middleware\PermissionMiddleware::class,
   ]);
   ```

## Usage Examples

### Checking Permissions in Controllers
```php
public function store(Request $request)
{
    if (!auth()->user()->hasPermission('users.create')) {
        abort(403, 'Insufficient permissions');
    }
    
    // Create user logic
}
```

### Checking Permissions in Views
```blade
@permission('cases.edit')
    <a href="{{ route('cases.edit', $case->id) }}" class="btn btn-warning">Edit Case</a>
@endpermission

@anyPermission(['reports.export', 'cases.export'])
    <div class="export-options">
        <!-- Export options -->
    </div>
@endanyPermission
```

### Protecting Routes
```php
// Role-based protection
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Permission-based protection
Route::middleware('permission:users.create')->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});
```

## Security Considerations

1. **Always validate permissions server-side** - Don't rely solely on UI hiding
2. **Use middleware for route protection** - Combine with controller-level checks
3. **Regular permission audits** - Review and update permissions regularly
4. **Principle of least privilege** - Grant minimum necessary permissions
5. **Log permission changes** - Track who made what changes

## Customization

### Adding New Permissions
1. Create the permission in the database
2. Add it to the PermissionSeeder if needed
3. Assign it to appropriate roles
4. Use it in middleware or Blade directives

### Adding New Roles
1. Create the role in the database
2. Assign appropriate permissions
3. Update any role-based middleware or Blade directives

### Custom Permission Logic
Extend the permission checking methods in the User model for custom logic:
```php
public function hasCustomPermission($resource, $action)
{
    // Custom permission logic
    return $this->hasPermission($resource . '.' . $action);
}
```

## Troubleshooting

### Common Issues

1. **Permissions not working**
   - Check if user has a role assigned
   - Verify role has the required permissions
   - Clear application cache: `php artisan cache:clear`

2. **Middleware not working**
   - Ensure middleware is registered in `bootstrap/app.php`
   - Check route middleware application
   - Verify user authentication

3. **Blade directives not working**
   - Clear view cache: `php artisan view:clear`
   - Check AppServiceProvider registration
   - Verify user authentication

### Debug Commands
```bash
# Check user permissions
php artisan tinker
>>> auth()->user()->getAllPermissions()->pluck('slug')

# Check role permissions
>>> App\Models\Role::find('role-id')->permissions->pluck('slug')
```

## How to Use the RBAC System

This section provides a step-by-step guide on how to effectively use the RBAC system in your application.

### 🚀 Getting Started

#### 1. **Access the RBAC Management Interface**

1. **Login as Admin**: First, ensure you're logged in as a user with the `admin` role
   ```
   Email: admin@sinda.local
   Password: Admin@12345
   ```

2. **Navigate to Roles Management**:
   - Go to Dashboard → Click on "Roles" in the sidebar
   - Or directly visit: `http://127.0.0.1:8000/roles`

3. **Navigate to Permissions Management**:
   - Go to Dashboard → Click on "Permissions" in the sidebar
   - Or directly visit: `http://127.0.0.1:8000/permissions`

#### 2. **Understanding the Interface**

**Roles Management Page**:
- **Summary Cards**: Shows total roles and permissions count
- **Roles Table**: Displays all roles with user counts and permission counts
- **Add Role Button**: Opens modal to create new roles
- **Action Dropdown**: View, Edit, Assign Permissions, Delete options

**Permissions Management Page**:
- **Permissions Table**: Shows all permissions grouped by module
- **Add Permission Button**: Create new permissions
- **Action Dropdown**: View, Edit, Delete options

### 📋 Managing Roles

#### **Creating a New Role**

1. **Click "Add Role"** button in the Roles Management page
2. **Fill in the form**:
   - **Role Name**: Enter a descriptive name (e.g., "Case Manager")
   - **Description**: Optional description of the role's purpose
3. **Click "Create Role"** to save

**Note**: Role names are automatically converted to lowercase with underscores (e.g., "Case Manager" becomes "case_manager")

#### **Editing a Role**

1. **Find the role** in the roles table
2. **Click the dropdown menu** (three dots) → Select "Edit"
3. **Modify the fields** in the modal
4. **Click "Update Role"** to save changes

#### **Assigning Permissions to a Role**

1. **Find the role** in the roles table
2. **Click the dropdown menu** → Select "Permissions"
3. **Check/uncheck permissions** for each module:
   - **Select All**: Use the "Select All" checkbox for each module
   - **Individual Permissions**: Check specific permissions as needed
4. **Click "Save Permissions"** to apply changes

#### **Deleting a Role**

1. **Find the role** in the roles table
2. **Click the dropdown menu** → Select "Delete"
3. **Confirm deletion** in the confirmation modal

**Important**: Roles with assigned users cannot be deleted. You must first reassign or remove users from the role.

### 🔐 Managing Permissions

#### **Creating a New Permission**

1. **Click "Add Permission"** button in the Permissions Management page
2. **Fill in the form**:
   - **Permission Name**: Human-readable name (e.g., "Create Reports")
   - **Slug**: System identifier (e.g., "reports.create")
   - **Module**: Category (e.g., "reports", "users", "cases")
   - **Action**: Specific action (e.g., "create", "view", "edit", "delete")
   - **Description**: Optional description
3. **Click "Create Permission"** to save

#### **Permission Naming Convention**

Use the format: `module.action`
- **Examples**:
  - `users.create` - Create users
  - `cases.view` - View cases
  - `reports.export` - Export reports
  - `system.settings` - Access system settings

### 👥 Managing User Roles

#### **Assigning Roles to Users**

1. **Go to User Management** (Dashboard → Users)
2. **Select the user type** (Admin, Social Worker, etc.)
3. **Click "Add"** to create a new user or "Edit" for existing users
4. **Select the appropriate role** from the dropdown
5. **Save the user**

#### **Checking User Permissions**

You can check what permissions a user has:

```php
// In controllers or tinker
$user = auth()->user();
$permissions = $user->getAllPermissions();
echo $permissions->pluck('slug');
```

### 🛡️ Implementing Access Control

#### **1. Route Protection**

Protect routes using middleware:

```php
// Role-based protection
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Permission-based protection
Route::middleware('permission:users.create')->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});

// Multiple roles
Route::middleware('role:admin,social_worker')->group(function () {
    Route::get('/cases', [CaseController::class, 'index']);
});

// Multiple permissions (OR logic)
Route::middleware('permission:cases.view,cases.edit')->group(function () {
    Route::get('/cases/{id}', [CaseController::class, 'show']);
});
```

#### **2. Controller-Level Protection**

Add permission checks in your controllers:

```php
public function store(Request $request)
{
    // Check if user has permission
    if (!auth()->user()->hasPermission('users.create')) {
        abort(403, 'Insufficient permissions');
    }
    
    // Your logic here
}

public function update(Request $request, $id)
{
    // Check multiple permissions
    if (!auth()->user()->hasAnyPermission(['users.edit', 'users.admin'])) {
        abort(403, 'Insufficient permissions');
    }
    
    // Your logic here
}
```

#### **3. View-Level Protection**

Use Blade directives to show/hide content:

```blade
{{-- Show content only if user has permission --}}
@permission('users.create')
    <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
@endpermission

{{-- Show content if user has any of the permissions --}}
@anyPermission(['cases.view', 'cases.edit'])
    <div class="case-actions">
        <a href="{{ route('cases.edit', $case->id) }}" class="btn btn-warning">Edit</a>
    </div>
@endanyPermission

{{-- Show content if user has all permissions --}}
@allPermissions(['reports.create', 'reports.edit'])
    <div class="full-access">
        <!-- Full access content -->
    </div>
@endallPermissions

{{-- Role-based content --}}
@role('admin')
    <div class="admin-panel">
        <!-- Admin-specific content -->
    </div>
@endrole

{{-- Multiple roles --}}
@anyRole(['admin', 'gov_official'])
    <div class="management-panel">
        <!-- Management content -->
    </div>
@endanyRole
```

### 🔄 Common Workflows

#### **Workflow 1: Creating a New User Type**

1. **Create Role**: Add a new role (e.g., "Data Analyst")
2. **Create Permissions**: Add specific permissions for the role
3. **Assign Permissions**: Assign relevant permissions to the role
4. **Create Users**: Create users and assign them to the new role
5. **Test Access**: Verify the user can access only the intended features

#### **Workflow 2: Modifying Existing Permissions**

1. **Identify the Role**: Find the role that needs permission changes
2. **Review Current Permissions**: Check what permissions are currently assigned
3. **Modify Permissions**: Add or remove permissions as needed
4. **Test Changes**: Verify that users with the role have the correct access

#### **Workflow 3: Adding New Features**

1. **Create Permissions**: Add new permissions for the feature
2. **Assign to Roles**: Assign permissions to appropriate roles
3. **Protect Routes**: Add middleware to protect new routes
4. **Update Views**: Use Blade directives to show/hide UI elements
5. **Test Thoroughly**: Ensure all access controls work correctly

### 🎯 Best Practices

#### **1. Permission Design**

- **Be Specific**: Create granular permissions (e.g., `users.create` instead of just `users`)
- **Use Consistent Naming**: Follow the `module.action` convention
- **Group Related Permissions**: Use the same module for related permissions

#### **2. Role Design**

- **Keep Roles Simple**: Don't create too many roles
- **Use Descriptive Names**: Make role names clear and meaningful
- **Document Role Purposes**: Use descriptions to explain role responsibilities

#### **3. Security**

- **Principle of Least Privilege**: Grant minimum necessary permissions
- **Regular Audits**: Review permissions and roles periodically
- **Test Access Controls**: Regularly test that permissions work correctly

#### **4. Maintenance**

- **Document Changes**: Keep track of permission and role changes
- **Backup Before Changes**: Always backup before making major changes
- **Test in Development**: Test all changes in development before production

### 🐛 Troubleshooting

#### **Common Issues and Solutions**

**Issue**: User can't access a feature they should have access to
**Solution**: 
1. Check if user has a role assigned
2. Verify the role has the required permissions
3. Check if the route/controller has proper permission checks
4. Clear application cache: `php artisan cache:clear`

**Issue**: Permission checks not working in views
**Solution**:
1. Clear view cache: `php artisan view:clear`
2. Check if user is authenticated
3. Verify Blade directive syntax

**Issue**: Middleware not working
**Solution**:
1. Check if middleware is properly registered
2. Verify route middleware application
3. Check user authentication status

#### **Debug Commands**

```bash
# Check current user's permissions
php artisan tinker
>>> auth()->user()->getAllPermissions()->pluck('slug')

# Check specific role permissions
>>> App\Models\Role::where('name', 'admin')->first()->permissions->pluck('slug')

# Check if user has specific permission
>>> auth()->user()->hasPermission('users.create')

# List all roles
>>> App\Models\Role::all()->pluck('name')
```

### 📚 Advanced Usage

#### **Custom Permission Logic**

You can extend the permission system with custom logic:

```php
// In User model
public function hasCustomPermission($resource, $action, $context = null)
{
    // Custom logic based on context
    if ($context && $context->user_id === $this->id) {
        return true; // Users can always access their own resources
    }
    
    return $this->hasPermission($resource . '.' . $action);
}

// Usage
if (auth()->user()->hasCustomPermission('reports', 'edit', $report)) {
    // Allow editing
}
```

#### **Dynamic Permission Checking**

```php
// Check permissions based on request data
public function update(Request $request, $id)
{
    $resource = $request->input('resource_type');
    $action = 'edit';
    
    if (!auth()->user()->hasPermission($resource . '.' . $action)) {
        abort(403);
    }
    
    // Continue with update logic
}
```

## Support

For issues or questions about the RBAC system:
1. Check the Laravel documentation
2. Review the middleware and model implementations
3. Test with different user roles and permissions
4. Check application logs for errors
5. Use the debug commands provided above
6. Review this usage guide for common solutions
