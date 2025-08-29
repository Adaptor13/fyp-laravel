@extends('layout.master')
@section('title', 'Assign Permissions - ' . $role->pretty_name)
@section('css')
    <style>
        .permission-module {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .permission-module h5 {
            color: #007bff;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .permission-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .permission-item:hover {
            border-color: #007bff;
            box-shadow: 0 2px 4px rgba(0,123,255,0.1);
        }
        
        .permission-checkbox {
            margin-right: 10px;
        }
        
        .permission-label {
            font-weight: 500;
            color: #495057;
        }
        
        .permission-description {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .select-all-module {
            background-color: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .select-all-module label {
            font-weight: 600;
            color: #1976d2;
            margin-bottom: 0;
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Assign Permissions to {{ $role->pretty_name }}</h4>
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
                        <i class="ti ti-key f-s-16 ms-2"></i>
                        <span class="f-s-14">Assign Permissions</span>
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

        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Validation Errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-key me-2"></i>
                            Permissions for Role: <strong>{{ $role->pretty_name }}</strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('roles.update-permissions', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        <strong>Instructions:</strong> Check the permissions you want to assign to this role. 
                                        You can use the "Select All" checkboxes for each module to quickly select all permissions in that module.
                                    </div>
                                </div>
                            </div>

                            @foreach($permissions as $module => $modulePermissions)
                                <div class="permission-module">
                                    <h5>
                                        <i class="ti ti-folder me-2"></i>
                                        {{ ucfirst($module) }} Management
                                    </h5>
                                    
                                    <div class="select-all-module">
                                        <div class="form-check">
                                            <input class="form-check-input select-all-module-checkbox" 
                                                   type="checkbox" 
                                                   id="select_all_{{ $module }}"
                                                   data-module="{{ $module }}">
                                            <label class="form-check-label" for="select_all_{{ $module }}">
                                                Select All {{ ucfirst($module) }} Permissions
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        @foreach($modulePermissions as $permission)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="permission-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox module-{{ $module }}" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}"
                                                               id="permission_{{ $permission->id }}"
                                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label permission-label" for="permission_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                        @if($permission->description)
                                                            <div class="permission-description">
                                                                {{ $permission->description }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">
                                                <i class="ti ti-info-circle me-1"></i>
                                                Total Permissions: <strong>{{ count($permissions->flatten()) }}</strong> | 
                                                Selected: <strong id="selected-count">0</strong>
                                            </span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                                <i class="ti ti-x"></i> Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ti ti-check"></i> Save Permissions
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Update selected count
            function updateSelectedCount() {
                const selectedCount = $('input[name="permissions[]"]:checked').length;
                $('#selected-count').text(selectedCount);
            }
            
            // Initialize count
            updateSelectedCount();
            
            // Update count when checkboxes change
            $('input[name="permissions[]"]').on('change', function() {
                updateSelectedCount();
            });
            
            // Select all functionality for each module
            $('.select-all-module-checkbox').on('change', function() {
                const module = $(this).data('module');
                const isChecked = $(this).is(':checked');
                
                $('.module-' + module).prop('checked', isChecked);
                updateSelectedCount();
            });
            
            // Update select all checkbox when individual permissions change
            $('.permission-checkbox').on('change', function() {
                const module = $(this).hasClass('module-users') ? 'users' :
                              $(this).hasClass('module-roles') ? 'roles' :
                              $(this).hasClass('module-cases') ? 'cases' :
                              $(this).hasClass('module-reports') ? 'reports' :
                              $(this).hasClass('module-dashboard') ? 'dashboard' :
                              $(this).hasClass('module-analytics') ? 'analytics' :
                              $(this).hasClass('module-system') ? 'system' : '';
                
                if (module) {
                    const totalInModule = $('.module-' + module).length;
                    const checkedInModule = $('.module-' + module + ':checked').length;
                    
                    if (checkedInModule === 0) {
                        $('#select_all_' + module).prop('indeterminate', false).prop('checked', false);
                    } else if (checkedInModule === totalInModule) {
                        $('#select_all_' + module).prop('indeterminate', false).prop('checked', true);
                    } else {
                        $('#select_all_' + module).prop('indeterminate', true);
                    }
                }
            });
            
            // Initialize select all checkboxes
            $('.select-all-module-checkbox').each(function() {
                const module = $(this).data('module');
                const totalInModule = $('.module-' + module).length;
                const checkedInModule = $('.module-' + module + ':checked').length;
                
                if (checkedInModule === 0) {
                    $(this).prop('indeterminate', false).prop('checked', false);
                } else if (checkedInModule === totalInModule) {
                    $(this).prop('indeterminate', false).prop('checked', true);
                } else {
                    $(this).prop('indeterminate', true);
                }
            });
        });
    </script>
@endsection
