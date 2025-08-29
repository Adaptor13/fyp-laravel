@extends('layout.master')
@section('title', 'Role Details - ' . $role->pretty_name)

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Role Details: {{ $role->pretty_name }}</h4>
            </div>
            <div class="col-sm-6 mt-sm-2">
                <ul class="breadcrumb breadcrumb-start float-sm-end">
                    <li class="d-flex">
                        <i class="ti ti-home f-s-16"></i>
                        <a href="{{ route('admin_index') }}" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Dashboard</span>
                        </a>
                    </li>
                    <li class="d-flex">
                        <i class="ti ti-lock f-s-16 ms-2"></i>
                        <a href="{{ route('roles.index') }}" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Roles</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-eye f-s-16 ms-2"></i>
                        <span class="f-s-14">Details</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-info-circle me-2"></i>
                            Role Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Role Name:</strong></td>
                                <td>{{ $role->pretty_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Internal Name:</strong></td>
                                <td><code>{{ $role->name }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Description:</strong></td>
                                <td>{{ $role->description ?? 'No description provided' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $role->created_at->format('M d, Y \a\t g:i A') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $role->updated_at->format('M d, Y \a\t g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-users me-2"></i>
                            Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h3 class="text-primary mb-1">{{ $role->users->count() }}</h3>
                                    <p class="text-muted mb-0">Assigned Users</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success mb-1">{{ $role->permissions->count() }}</h3>
                                <p class="text-muted mb-0">Assigned Permissions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-settings me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">
                                <i class="ti ti-edit me-2"></i>Edit Role
                            </a>
                            <a href="{{ route('roles.assign-permissions', $role->id) }}" class="btn btn-primary">
                                <i class="ti ti-key me-2"></i>Manage Permissions
                            </a>
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-2"></i>Back to Roles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($role->permissions->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-key me-2"></i>
                                Assigned Permissions ({{ $role->permissions->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($role->permissions->groupBy('module') as $module => $modulePermissions)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">
                                                    <i class="ti ti-folder me-2"></i>
                                                    {{ ucfirst($module) }} Management
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($modulePermissions as $permission)
                                                        <li class="mb-2">
                                                            <i class="ti ti-check text-success me-2"></i>
                                                            <strong>{{ $permission->name }}</strong>
                                                            @if($permission->description)
                                                                <br>
                                                                <small class="text-muted">{{ $permission->description }}</small>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($role->users->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-users me-2"></i>
                                Users with this Role ({{ $role->users->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($role->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
