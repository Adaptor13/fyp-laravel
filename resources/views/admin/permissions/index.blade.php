@extends('layout.master')
@section('title', 'Manage Permissions')
@section('css')
    <style>
        .permission-module {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .permission-module h5 {
            color: #28a745;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .permission-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .permission-item:hover {
            border-color: #28a745;
            box-shadow: 0 2px 4px rgba(40,167,69,0.1);
        }
        
        .permission-name {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .permission-slug {
            font-family: monospace;
            font-size: 0.875rem;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        .permission-description {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 8px;
        }
        
        .permission-stats {
            font-size: 0.75rem;
            color: #28a745;
            font-weight: 500;
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Manage Permissions</h4>
            </div>
            <div class="col-sm-6 mt-sm-2">
                <ul class="breadcrumb breadcrumb-start float-sm-end">
                    <li class="d-flex">
                        <i class="ti ti-home f-s-16"></i>
                        <a href="{{ route('admin_index') }}" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Dashboard</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-key f-s-16 ms-2"></i>
                        <span class="f-s-14">Permissions</span>
                    </li>
                </ul>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-success text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ count($permissions->flatten()) }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Total Permissions</p>
                            </div>
                            <div>
                                <i class="ti ti-key f-s-36"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card bg-info text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ $permissions->count() }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Permission Modules</p>
                            </div>
                            <div>
                                <i class="ti ti-folder f-s-36"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card bg-primary text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ \App\Models\Role::count() }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Total Roles</p>
                            </div>
                            <div>
                                <i class="ti ti-lock f-s-36"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card bg-warning text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ \App\Models\Role::whereHas('permissions')->count() }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Roles with Permissions</p>
                            </div>
                            <div>
                                <i class="ti ti-check f-s-36"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-key me-2"></i>
                            System Permissions
                        </h5>
                       
                    </div>
                    <div class="card-body">
                        @foreach($permissions as $module => $modulePermissions)
                            <div class="permission-module">
                                <h5>
                                    <i class="ti ti-folder me-2"></i>
                                    {{ ucfirst($module) }} Management
                                    <span class="badge bg-light text-dark ms-2">{{ $modulePermissions->count() }} permissions</span>
                                </h5>
                                
                                <div class="row">
                                    @foreach($modulePermissions as $permission)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="permission-item">
                                                <div class="permission-name">
                                                    {{ $permission->name }}
                                                </div>
                                                <div class="permission-slug">
                                                    {{ $permission->slug }}
                                                </div>
                                                @if($permission->description)
                                                    <div class="permission-description">
                                                        {{ $permission->description }}
                                                    </div>
                                                @endif
                                                <div class="permission-stats mt-2">
                                                    <i class="ti ti-users me-1"></i>
                                                    {{ $permission->roles_count }} {{ Str::plural('role', $permission->roles_count) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
