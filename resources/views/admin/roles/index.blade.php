@extends('layout.master')
@section('title', 'Role Management')
@section('css')
    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select/select2.min.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Role Management</h4>
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
                        <i class="ti ti-lock f-s-16 ms-2"></i>
                        <span class="f-s-14">Roles</span>
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if ($errors->has('general'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> {{ $errors->first('general') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-primary text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ $roles->count() }}</h3>
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
                <div class="card bg-success text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ \App\Models\Permission::count() }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Total Permissions</p>
                            </div>
                            <div>
                                <i class="ti ti-key f-s-36"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Roles Management</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRole">
                            <i class="ti ti-plus"></i> Add Role
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="overflow-auto data-table-style app-scroll">
                            <table id="rolesTable" class="display app-data-table deafult-data-tabel">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Description</th>
                                        <th>Users</th>
                                        <th>Permissions</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRole" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add Role</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any() && !old('_method'))
                            <div class="alert alert-danger mb-3">
                                <strong>Error:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if ($errors->has('general') && !old('_method'))
                            <div class="alert alert-danger mb-3">
                                <strong>Error:</strong> {{ $errors->first('general') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input id="role_name" name="name" type="text" class="form-control @if(!old('_method')) @error('name') is-invalid @enderror @endif"
                                    value="{{ old('name') }}" placeholder="Enter role name (e.g., Case Manager)" required>
                                @if(!old('_method'))
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                                <small class="form-text text-muted">
                                    The role name will be converted to lowercase with underscores (e.g., "Case Manager" becomes "case_manager")
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="role_description" class="form-label">Description</label>
                                <textarea id="role_description" name="description" class="form-control @if(!old('_method')) @error('description') is-invalid @enderror @endif" 
                                    rows="3" placeholder="Enter role description">{{ old('description') }}</textarea>
                                @if(!old('_method'))
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-light-primary">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRole" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('roles.update', ['role' => '__ID__']) }}"
                      data-action-template="{{ route('roles.update', ['role' => '__ID__']) }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Edit Role</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any() && old('_method') === 'PUT')
                            <div class="alert alert-danger mb-3">
                                <strong>Error:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if ($errors->has('general') && old('_method') === 'PUT')
                            <div class="alert alert-danger mb-3">
                                <strong>Error:</strong> {{ $errors->first('general') }}
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input id="edit_role_name" name="name" type="text" class="form-control @if(old('_method') === 'PUT') @error('name') is-invalid @enderror @endif"
                                    placeholder="Enter role name" required>
                                @if(old('_method') === 'PUT')
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                                <small class="form-text text-muted">
                                    The role name will be converted to lowercase with underscores
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_role_description" class="form-label">Description</label>
                                <textarea id="edit_role_description" name="description" class="form-control @error('description') is-invalid @enderror" 
                                    rows="3" placeholder="Enter role description"></textarea>
                                @if(old('_method') === 'PUT')
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-light-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="/roles/__id__" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete this role?<br>
                    <small class="text-secondary">Role: <span id="deleteRoleLabel">—</span></small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="badge text-light-danger fs-6" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Data Table js -->
    <script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>

    <script>
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                // Check if we're editing (has old input data with _method PUT) or adding
                const isEditing = @json(old('_method') === 'PUT' || request()->routeIs('roles.update'));
                const modalId = isEditing ? 'editRole' : 'addRole';
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                
                // If editing and there are validation errors, populate the edit form with old data
                if (isEditing) {
                    const $modal = $('#' + modalId);
                    $modal.find('input[name="name"]').val(@json(old('name', '')));
                    $modal.find('textarea[name="description"]').val(@json(old('description', '')));
                }
                
                modal.show();
            });
        @endif

        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.remove();
        }, 4000);

        // Prevent double form submissions
        function preventDoubleSubmission(form) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="ti ti-loader ti-spin"></i> Processing...';
            }
        }

        $(document).ready(function() {
            console.log('Initializing DataTable...');
            console.log('Current user:', '{{ auth()->user() ? auth()->user()->name : "Not logged in" }}');
            console.log('User role:', '{{ auth()->user() ? auth()->user()->role : "No role" }}');
            
            // Prevent double form submissions
            $('form').on('submit', function() {
                preventDoubleSubmission(this);
            });
            
            $('#rolesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{{ route("roles.data") }}',
                    type: 'GET',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('DataTable AJAX Error:', error);
                        console.error('Response:', xhr.responseText);
                        console.error('Status:', xhr.status);
                        console.error('URL:', this.url);
                    }
                },
                columns: [
                    { 
                        data: 'name', 
                        name: 'name',
                        render: function(data, type, row) {
                            return `<h6 class="f-s-15 mb-0">${data || '—'}</h6>`;
                        }
                    },
                    { 
                        data: 'description', 
                        name: 'description',
                        render: function(data, type, row) {
                            return data || '—';
                        }
                    },
                    { 
                        data: 'users_count', 
                        name: 'users_count',
                        render: function(data, type, row) {
                            return `<span class="badge bg-primary">${data || 0}</span>`;
                        }
                    },
                    { 
                        data: 'permissions_count', 
                        name: 'permissions_count',
                        render: function(data, type, row) {
                            return `<span class="badge bg-success">${data || 0}</span>`;
                        }
                    },
                    { 
                        data: 'created_at', 
                        name: 'created_at',
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString() : '—';
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
                ],
                order: [[0, 'asc']],
                pageLength: 25,
                responsive: true,
                language: {
                    emptyTable: "No roles found",
                    zeroRecords: "No matching roles found"
                },
                initComplete: function() {
                    console.log('DataTable initialized successfully');
                }
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
    </script>
@endsection
