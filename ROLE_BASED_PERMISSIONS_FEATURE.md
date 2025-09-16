# Role-Based Permissions & Access Control Feature

## Backend Implementation

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();

        // Guest → send to sign in (preserve intended URL)
        if (!$user) {
            return redirect()->guest(route('sign_in'))
                ->withErrors(['auth' => 'Please sign in first.']);
        }

        // Load role and permissions
        $user->loadMissing(['role.permissions']);

        // Check if user has any of the required permissions
        $hasPermission = false;
        
        if ($user->role) {
            foreach ($permissions as $permission) {
                if ($user->hasPermission($permission)) {
                    $hasPermission = true;
                    break;
                }
            }
        }

        if (!$hasPermission) {
            // JSON/AJAX (e.g. DataTables) → 403 JSON; normal page → 403 view
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Insufficient permissions'], 403);
            }
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
```

**Figure 1: Role-Based Permissions Middleware Code Snippet**

This code implements the `PermissionMiddleware` class that intercepts HTTP requests and enforces role-based access control. The `handle()` method receives the incoming request and a closure for the next middleware in the chain. It first checks if a user is authenticated by calling `$request->user()` - if no user exists, it redirects to the sign-in page using `redirect()->guest()` while preserving the intended URL. The middleware then loads the user's role and permissions relationship using `$user->loadMissing(['role.permissions'])` to avoid N+1 query problems. It iterates through the required permissions passed as variadic parameters and calls `$user->hasPermission($permission)` to check if the user's role contains any of the required permissions. If no permissions match, it returns different error responses based on the request type: JSON responses with 403 status for AJAX requests using `$request->ajax()`, `$request->wantsJson()`, or `$request->expectsJson()` checks, or an HTTP 403 abort for regular page requests. If permissions are satisfied, it calls `$next($request)` to continue processing the request.

## Frontend Implementation

```html
<!-- Sidebar Navigation with Permission-Based Menu Items -->
<nav class="dark-sidebar">
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            <!-- Cases -->
            @permission('cases.view')
            <li>
                <a data-bs-toggle="collapse" href="#caseManagement" aria-expanded="false">
                    <i class="ti ti-briefcase"></i>
                    Cases
                </a>
                <ul class="collapse" id="caseManagement">
                    <li><a href="{{ route('cases.index') }}"><i class="ti ti-list"></i> View Cases</a></li>
                </ul>
            </li>
            @endpermission

            <!-- Users -->
            @permission('users.view')
            <li>
                <a data-bs-toggle="collapse" href="#userManagement" aria-expanded="false">
                    <i class="ti ti-users"></i>
                    Users
                </a>
                <ul class="collapse" id="userManagement">
                    <li><a href="{{ route('users.admins') }}"><i class="ti ti-shield"></i> Admins</a></li>
                    <li><a href="{{ route('users.public') }}"><i class="ti ti-user"></i> Public Users</a></li>
                    <li><a href="{{ route('users.social') }}"><i class="ti ti-id-badge"></i> Social Workers</a></li>
                    <li><a href="{{ route('users.law') }}"><i class="ti ti-target"></i> Law Enforcement</a></li>
                    <li><a href="{{ route('users.cwo') }}"><i class="ti ti-building"></i> Child Welfare Officers</a></li>
                    <li><a href="{{ route('users.health') }}"><i class="ti ti-stethoscope"></i> Healthcare Professionals</a></li>
                </ul>
            </li>
            @endpermission

            <!-- Roles & Permissions -->
            @permission('roles.view')
            <li>
                <a data-bs-toggle="collapse" href="#roleManagement" aria-expanded="false">
                    <i class="ti ti-lock"></i>
                    Roles & Permissions
                </a>
                <ul class="collapse" id="roleManagement">
                    <li><a href="{{ route('roles.index') }}"><i class="ti ti-settings"></i> Manage Roles</a></li>
                    <li><a href="{{ route('permissions.index') }}"><i class="ti ti-key"></i> View Permissions</a></li>
                </ul>
            </li>
            @endpermission
        </ul>
    </div>
</nav>
```

**Figure 2: Role-Based Permissions UI Component**

This code implements conditional menu rendering using Laravel Blade directives to enforce role-based access control in the user interface. The `@permission('cases.view')` directive checks if the current authenticated user has the 'cases.view' permission before rendering the Cases menu section. If the permission check passes, it renders the menu item with Bootstrap collapse functionality using `data-bs-toggle="collapse"` and `href="#caseManagement"` attributes. The nested `<ul class="collapse" id="caseManagement">` creates a collapsible submenu that contains the "View Cases" link. Similarly, the `@permission('users.view')` directive conditionally renders the Users menu with multiple submenu items for different user types (Admins, Public Users, Social Workers, etc.), each with appropriate icons using the `ti ti-*` classes. The `@permission('roles.view')` directive controls access to the Roles & Permissions section. The `@endpermission` directive closes each permission block. This approach ensures that menu items are only rendered in the DOM if the user has the necessary permissions, preventing unauthorized users from seeing or accessing restricted functionality.

## JavaScript Implementation

```javascript
// User permissions for frontend permission checking
const userPermissions = @json($userPermissions ?? []);

// Helper function to check if user has permission
function hasPermission(permission) {
    return userPermissions.includes(permission);
}

$(function() {
    $('#rolesTable').DataTable({
        processing: true,
        ajax: {
            url: '{{ route('roles.data') }}',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            {
                data: 'name',
                render: function(data, type, row) {
                    const label = data || `ID ${row.id}`;
                    return `<span class="fw-bold">${label}</span>`;
                }
            },
            {
                data: 'description',
                render: function(data, type, row) {
                    return data || '<span class="text-muted">No description</span>';
                }
            },
            {
                data: 'users_count',
                render: function(data, type, row) {
                    return `<span class="badge bg-info">${data} users</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    const label = row.name || `ID ${row.id}`;
                    return `
                        <div class="dropdown">
                            <button class="bg-none border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/roles/${row.id}">
                                        <i class="ti ti-eye text-info"></i> View
                                    </a>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item edit-btn"
                                            data-id="${row.id}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editRole">
                                        <i class="ti ti-edit text-success"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/roles/${row.id}/permissions">
                                        <i class="ti ti-key text-primary"></i> Permissions
                                    </a>
                                </li>
                                ${row.users_count == 0 ? `
                                <li>
                                    <a class="dropdown-item delete-btn" href="javascript:void(0)" 
                                       data-id="${row.id}" data-label="${label}">
                                        <i class="ti ti-trash text-danger"></i> Delete
                                    </a>
                                </li>
                                ` : `
                                <li>
                                    <span class="dropdown-item text-muted" style="cursor: not-allowed;">
                                        <i class="ti ti-trash text-muted"></i> Delete (has users)
                                    </span>
                                </li>
                                `}
                            </ul>
                        </div>
                    `;
                }
            }
        ]
    });

    // Delete functionality
    let deleteId = null;
    const deleteModalEl = document.getElementById('deleteModal');
    const deleteModal = new bootstrap.Modal(deleteModalEl);
    const deleteRoleLabelEl = document.getElementById('deleteRoleLabel');

    $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        const label = $(this).data('label') || `ID ${deleteId}`;
        deleteRoleLabelEl.textContent = label;
        deleteModal.show();
    });

    $('#confirmDeleteBtn').on('click', function() {
        if (!deleteId) return;
        const form = document.getElementById('deleteForm');
        form.action = `/roles/${deleteId}`;
        form.submit();
    });

    // Edit functionality
    $(document).on('click', '#rolesTable .edit-btn', function () {
        const table = $('#rolesTable').DataTable();
        let $tr = $(this).closest('tr');
        if ($tr.hasClass('child')) {
            $tr = $tr.prev('.parent');
        }

        const rowData = table.row($tr).data();
        if (!rowData) return;

        // Modal + form
        const $modal = $('#editRole');                
        const $form = $modal.find('form');

        // Replace __ID__ in action template
        const actionTemplate = $form.attr('data-action-template') || $form.attr('action');
        if (actionTemplate) {
            $form.attr('action', actionTemplate.replace('__ID__', rowData.id));
        }

        // Fill form fields
        $modal.find('input[name="name"]').val(rowData.name ? rowData.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : '');
        $modal.find('textarea[name="description"]').val(rowData.description === 'No description' ? '' : rowData.description);

        $modal.modal('show');
    });
});
```

**Figure 3: Role-Based Permissions JavaScript Implementation**

This code implements client-side permission checking and role management functionality. The `userPermissions` constant stores the user's permissions array from the server, and the `hasPermission(permission)` function checks if a permission exists using `includes()`. The DataTable configuration loads role data via AJAX and renders columns with custom functions. The actions column uses conditional rendering to show active delete buttons only for roles with no users (`row.users_count == 0`). Event handlers manage delete confirmation modals and edit form population using `$(document).on('click')` for dynamically generated elements.
