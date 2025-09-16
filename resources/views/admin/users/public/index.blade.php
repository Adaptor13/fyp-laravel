@extends('layout.master')
@section('title', 'Public User')
@section('css')
    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">

@endsection


@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Public Users Management</h4>
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
                        <i class="ti ti-users f-s-16 ms-2"></i>
                        <a href="#" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Users</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-user f-s-16 ms-2"></i>
                        <span class="f-s-14">Public Users</span>
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

{{-- @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif --}}

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

            <div class="col-sm-6 col-lg-3">
                <div class="card bg-primary text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ $totalPublic }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Total Users</p>
                            </div>
                            <div>
                                <i class="ti ti-users f-s-36"></i>
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
                                <h3 class="header-heading mb-0">{{ $contactablePublic }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Contactable Users</p>
                            </div>
                            <div>
                                <i class="ti ti-phone f-s-36"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card bg-danger text-white">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h3 class="header-heading mb-0">{{ $nonContactablePublic }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Non-Contactable Users</p>
                            </div>
                            <div>
                                <i class="ti ti-phone-off f-s-36"></i>
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
                                <h3 class="header-heading mb-0">{{ $newPublic }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">New Users</p>
                            </div>
                            <div>
                                <i class="ti ti-user-plus f-s-36"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Public Users</h5>
                        @permission('users.create')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPublicUser"><i class="ti ti-plus"></i> Add</button>
                        @endpermission
                    </div>
                    <div class="card-body p-0">
                        <div class="overflow-auto data-table-style app-scroll">
                            <table id="publicUsersTable" class="display app-data-table deafult-data-tabel">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Display Name</th>
                                        <th>Allow Contact</th>
                                        <th>Last Updated</th>
                                        <th>Action</th>
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

    <div class="modal fade" id="addPublicUser" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('users.public.store') }}">
                    @csrf

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add Public User</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any() && !old('_method'))
                            <div class="alert alert-danger mb-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input name="name" type="name" class="form-control" value="{{ old('name') }}" placeholder="Name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="example@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input id="password" name="password" type="password" class="form-control" placeholder="Password(8 minimum)" minlength="8"
                                    required>
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="#password">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" minlength="8" placeholder="Password(8 minimum)" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-light-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">
                            Edit Public User
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_id">
                        
                        @if ($errors->any() && old('_method') === 'PUT')
                            <div class="alert alert-danger mb-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- Account Details Section -->
                        <h6 class="mb-3">
                            Account Details
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" id="edit_name" required placeholder="Enter full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="000-0000000">
                            </div>
                        </div>

                        <!-- Public User Profile Section -->
                        <hr class="my-4">
                        <h6 class="mb-3">
                            Public User Profile
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Name</label>
                                <input class="form-control" name="display_name" id="edit_display_name" placeholder="Enter display name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Allow Contact</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_allow_contact" name="allow_contact" value="1">
                                    <label class="form-check-label" for="edit_allow_contact">
                                        Allow contact about reports
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <hr class="my-4">
                        <h6 class="mb-3">
                            Contact Information (Optional)
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address Line 1</label>
                                <input class="form-control" name="address_line1" id="edit_address_line1" placeholder="Street, Apartment, etc.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address Line 2</label>
                                <input class="form-control" name="address_line2" id="edit_address_line2" placeholder="Unit, Suite">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City</label>
                                <input class="form-control" name="city" id="edit_city" placeholder="City">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Postcode</label>
                                <input class="form-control" name="postcode" id="edit_postcode" placeholder="Postcode">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State</label>
                                <select name="state" id="edit_state" class="form-select">
                                    <option value="">-- Select State --</option>
                                    <option value="Johor">Johor</option>
                                    <option value="Kedah">Kedah</option>
                                    <option value="Kelantan">Kelantan</option>
                                    <option value="Melaka">Melaka</option>
                                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                                    <option value="Pahang">Pahang</option>
                                    <option value="Perak">Perak</option>
                                    <option value="Perlis">Perlis</option>
                                    <option value="Pulau Pinang">Pulau Pinang</option>
                                    <option value="Sabah">Sabah</option>
                                    <option value="Sarawak">Sarawak</option>
                                    <option value="Selangor">Selangor</option>
                                    <option value="Terengganu">Terengganu</option>
                                    <option value="W.P. Kuala Lumpur">W.P. Kuala Lumpur</option>
                                    <option value="W.P. Labuan">W.P. Labuan</option>
                                    <option value="W.P. Putrajaya">W.P. Putrajaya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="{{ route('users.public.destroy', '__id__') }}" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete this user?<br>
                    <small class="text-secondary">User: <span id="deleteUserLabel">—</span></small>
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

    <script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>

    <script>
        // User permissions for frontend permission checking
        const userPermissions = @json($userPermissions ?? []);
        
        // Helper function to check if user has permission
        function hasPermission(permission) {
            return userPermissions.includes(permission);
        }
        
        $('#editUserModal').on('show.bs.modal', function () {
            $('#phone').mask('000-0000000');
        });

        $(function() {
            const table = $('#publicUsersTable').DataTable({
                processing: true,
                ajax: '{{ route('users.public.data') }}',
                columns: [{
                        data: 'name',
                        render: function(data, type, row) {
                            const safeName = data ? data : '—';
                            const sub = row.email ? row.email : '';
                            let avatarHtml = '';
                            
                            if (row.avatar_url) {
                                avatarHtml = `<img src="${row.avatar_url}" alt="${safeName}" class="img-fluid" style="width:30px;height:30px;object-fit:cover;">`;
                            } else {
                                avatarHtml = `<div class="d-flex align-items-center justify-content-center" style="width:30px;height:30px;${row.avatar_background_style}">
                                    <span class="text-white fw-bold" style="font-size: 12px;">${row.avatar_initials}</span>
                                </div>`;
                            }
                            
                            return `
                        <div class="d-flex justify-content-left align-items-center">
                            <div class="h-30 w-30 d-flex-center b-r-50 overflow-hidden me-2">
                                ${avatarHtml}
                            </div>
                            <div>
                                <h6 class="f-s-15 mb-0">${safeName}</h6>
                                <p class="text-secondary f-s-13 mb-0">${sub}</p>
                            </div>
                        </div>
                    `;
                        }
                    },
                    {
                        data: 'email',
                        visible: false
                    },
                    {
                        data: 'display_name',
                        render: function(data) {
                            return data && data.trim() !== '' ? data : '—';
                        }
                    },
                    {
                        data: 'allow_contact',
                        render: function(d) {
                            if (d === 1 || d === true)
                            return '<span class="badge text-bg-success">Yes</span>';
                            if (d === 0 || d === false)
                            return '<span class="badge text-bg-secondary">No</span>';
                            return '<span class="badge text-bg-light">N/A</span>';
                        }
                    },
                    {
                        data: 'profile_updated_at',
                        render: function(d) {
                            return d ? new Date(d).toLocaleString() : '—';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(row) {
                            const label = row.name || row.display_name || row.email ||
                                `ID ${row.id}`;
                            
                            let actionButtons = '';
                            
                            // Add Edit button if user has edit permission
                            if (hasPermission('users.edit')) {
                                actionButtons += `
                                    <li>
                                        <button type="button" class="dropdown-item edit-btn">
                                            <i class="ti ti-edit text-success"></i> Edit
                                        </button>
                                    </li>
                                `;
                            }
                            
                            // Add Delete button if user has delete permission
                            if (hasPermission('users.delete')) {
                                actionButtons += `
                                    <li>
                                        <a class="dropdown-item delete-btn" href="javascript:void(0)"
                                           data-id="${row.id}"
                                           data-label="${label}">
                                            <i class="ti ti-trash text-danger"></i> Delete
                                        </a>
                                    </li>
                                `;
                            }
                            
                            // If no action buttons are available, show a message
                            if (!actionButtons.trim()) {
                                return '<span class="text-muted">No actions available</span>';
                            }
                            
                            return `
                                <div class="dropdown">
                                    <button class="bg-none border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        ${actionButtons}
                                    </ul>
                                </div>
                            `;
                        }
                    }
                ],
                order: [
                    [4, 'desc']
                ],
                responsive: true
            });

            let deleteId = null;
            const deleteModalEl = document.getElementById('deleteModal');
            const deleteModal = new bootstrap.Modal(deleteModalEl);
            const deleteUserLabelEl = document.getElementById('deleteUserLabel');

            // Open modal
            $(document).on('click', '.delete-btn', function() {
                deleteId = $(this).data('id');
                const label = $(this).data('label') || `ID ${deleteId}`;
                deleteUserLabelEl.textContent = label;
                deleteModal.show();
            });

            $('#confirmDeleteBtn').on('click', function() {
                if (!deleteId) return;
                const form = document.getElementById('deleteForm');
                form.action = "{{ route('users.public.destroy', '__id__') }}".replace('__id__', deleteId);
                form.submit();
            });
        });


        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                // Check if we're editing (has old input data) or adding
                const isEditing = @json(old('_method') === 'PUT' || request()->routeIs('users.public.update'));
                const modalId = isEditing ? 'editPublicUser' : 'addPublicUser';
                const m = new bootstrap.Modal(document.getElementById(modalId));
                
                // If editing and there are validation errors, populate the edit form with old data
                if (isEditing) {
                    const $modal = $('#' + modalId);
                    $modal.find('input[name="name"]').val(@json(old('name', '')));
                    $modal.find('input[name="phone"]').val(@json(old('phone', '')));
                    $modal.find('input[name="display_name"]').val(@json(old('display_name', '')));
                    $modal.find('input[name="allow_contact"]').prop('checked', @json(old('allow_contact', false)));
                    $modal.find('input[name="address_line1"]').val(@json(old('address_line1', '')));
                    $modal.find('input[name="address_line2"]').val(@json(old('address_line2', '')));
                    $modal.find('input[name="city"]').val(@json(old('city', '')));
                    $modal.find('input[name="postcode"]').val(@json(old('postcode', '')));
                    $modal.find('input[name="state"]').val(@json(old('state', '')));
                }
                
                m.show();
            });
        @endif

        setTimeout(() => {
            const a = document.querySelector('.alert');
            if (a) a.remove();
        }, 4000);

        $('#publicUsersTable').on('click', '.edit-btn', function() {
            const rowData = $('#publicUsersTable').DataTable().row($(this).closest('tr')).data();

            // Set basic information
            $('#editUserForm input[name="id"]').val(rowData.id);
            $('#editUserForm input[name="name"]').val(rowData.name);
            $('#editUserForm input[name="phone"]').val(rowData.phone ?? '');
            
            
            // Set public user profile
            $('#editUserForm input[name="display_name"]').val(rowData.display_name ?? '');
            $('#edit_allow_contact').prop('checked', rowData.allow_contact == 1 || rowData.allow_contact === true);
            
            // Set address information
            $('#editUserForm [name="address_line1"]').val(rowData.address_line1 ?? '');
            $('#editUserForm [name="address_line2"]').val(rowData.address_line2 ?? '');
            $('#editUserForm [name="city"]').val(rowData.city ?? '');
            $('#editUserForm [name="state"]').val(rowData.state ?? '');
            $('#editUserForm [name="postcode"]').val(rowData.postcode ?? '');
            

            const updateUrl = `/users/public-users/${rowData.id}`;
            $('#editUserForm').attr('action', updateUrl);

            $('#editUserModal').modal('show');
        });

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-target'));
                const icon = this.querySelector('i');

                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.remove('ti-eye');
                    icon.classList.add('ti-eye-off');
                } else {
                    target.type = 'password';
                    icon.classList.remove('ti-eye-off');
                    icon.classList.add('ti-eye');
                }
            });
        });

    </script>
@endsection
