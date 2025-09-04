@extends('layout.master')
@section('title', 'Healthcare Professional')
@section('css')


    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select/select2.min.css') }}">


@endsection


@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Healthcare Professional Management</h4>
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
                        <span class="f-s-14">Healthcare Professional</span>
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

        <div class="col-sm-6 col-lg-3">
            <div class="card bg-primary text-white">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h3 class="header-heading mb-0">{{ $totalUsers ?? 0 }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">Total Professionals</p>
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
                            <h3 class="header-heading mb-0">{{ $doctorsCount ?? 0 }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">Doctors</p>
                        </div>
                        <div>
                            <i class="ti ti-stethoscope f-s-36"></i>
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
                            <h3 class="header-heading mb-0">{{ $nursesCount ?? 0 }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">Nurses</p>
                        </div>
                        <div>
                            <i class="ti ti-user f-s-36"></i>
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
                            <h3 class="header-heading mb-0">{{ $recentlyAdded ?? 0 }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">Recently Added (30 days)</p>
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
                        <h5 class="mb-0">Healthcare Professional</h5>
                        @permission('users.create')
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addHealthcareProfessional">Add</button>
                        @endpermission
                    </div>
                    <div class="card-body p-0">
                        <div class="overflow-auto data-table-style app-scroll">
                            <table id="healthcareTable" class="display app-data-table deafult-data-tabel">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Profession</th>
                                        <th>APC Expiry</th>
                                        <th>Facility Name</th>
                                        <th>Facility State</th>
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

    <div class="modal fade" id="addHealthcareProfessional" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('users.healthcare.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add Healthcare Professional</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
                        @endif

                        <!-- Account Details -->
                        <h6 class="mb-3">Account Details</h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="imageUploadAdd" name="avatar"
                                                accept=".png,.jpg,.jpeg" data-preview="#imgPreviewAdd">
                                            <label for="imageUploadAdd"><i class="ti ti-photo-heart"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imgPreviewAdd"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="add_name" class="form-label">Name</label>
                                <input id="add_name" name="name" type="text" class="form-control"
                                    value="{{ old('name') }}" placeholder="Full name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_email" class="form-label">Email</label>
                                <input id="add_email" name="email" type="email" class="form-control"
                                    value="{{ old('email') }}" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input id="add_password" name="password" type="password" class="form-control"
                                        placeholder="Password (min 8 chars)" minlength="8" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="#add_password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input id="add_password_confirmation" name="password_confirmation" type="password"
                                        class="form-control" minlength="8" placeholder="Re-enter password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="#add_password_confirmation">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Healthcare Profile -->
                        <h6 class="mb-3">Healthcare Profile</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_profession" class="form-label">Profession</label>
                                <select id="add_profession" name="profession" class="form-select" required>
                                    <option value="">Select profession</option>
                                    <option value="Doctor" {{ old('profession') === 'Doctor' ? 'selected' : '' }}>Doctor
                                    </option>
                                    <option value="Nurse" {{ old('profession') === 'Nurse' ? 'selected' : '' }}>Nurse
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="add_apc_expiry" class="form-label">APC Expiry</label>
                                <input id="add_apc_expiry" name="apc_expiry" type="date" class="form-control"
                                    value="{{ old('apc_expiry') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_facility_name" class="form-label">Facility Name</label>
                                <input id="add_facility_name" name="facility_name" type="text" class="form-control"
                                    value="{{ old('facility_name') }}" placeholder="e.g. Hospital Kuala Lumpur" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_hc_state" class="form-label">Facility State</label>
                                <input id="add_hc_state" name="state" type="text" class="form-control"
                                    value="{{ old('state') }}" placeholder="e.g. Selangor" required>
                            </div>
                        </div>

                        <hr>

                        <!-- Contact Information (Optional; maps to user_profiles) -->
                        <h6 class="mb-3">Contact Information (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_phone" class="form-label">Phone</label>
                                <input id="add_phone" name="phone" type="text" class="form-control"
                                    value="{{ old('phone') }}" placeholder="e.g. 012-3456789">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_address_line1" class="form-label">Address Line 1</label>
                                <input id="add_address_line1" name="address_line1" type="text" class="form-control"
                                    value="{{ old('address_line1') }}" placeholder="Street, Apartment, etc.">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_address_line2" class="form-label">Address Line 2</label>
                                <input id="add_address_line2" name="address_line2" type="text" class="form-control"
                                    value="{{ old('address_line2') }}" placeholder="Unit, Suite">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_city" class="form-label">City</label>
                                <input id="add_city" name="city" type="text" class="form-control"
                                    value="{{ old('city') }}" placeholder="City">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_postcode" class="form-label">Postcode</label>
                                <input id="add_postcode" name="postcode" type="text" class="form-control"
                                    value="{{ old('postcode') }}" placeholder="Postcode">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_state" class="form-label">Mailing State</label>
                                <input id="add_state" name="state_profile" type="text" class="form-control"
                                    value="{{ old('state_profile') }}" placeholder="State for mailing address">
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



    <div class="modal fade" id="editHealthcareProfessional" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('users.healthcare.update', '__ID__') }}"
                    data-action-template="{{ route('users.healthcare.update', '__ID__') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Edit Healthcare Professional</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
                        @endif

                        <h6 class="mb-3">Account Details</h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="imageUploadEditHC" name="avatar"
                                                accept=".png,.jpg,.jpeg" data-preview="#imgPreviewEditHC">
                                            <label for="imageUploadEditHC"><i class="ti ti-photo-heart"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imgPreviewEditHC"
                                                style="background-image:url('{{ $healthcare?->avatar_url ?? '' }}');">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_hc_name" class="form-label">Name</label>
                                <input id="edit_hc_name" name="name" type="text" class="form-control"
                                    placeholder="Full name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_hc_email" class="form-label">Email</label>
                                <input id="edit_hc_email" name="email" type="email" class="form-control" readonly>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Healthcare Profile</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_profession" class="form-label">Profession</label>
                                <select id="edit_profession" name="profession" class="form-select" required>
                                    <option value="">Select profession</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Nurse">Nurse</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_apc_expiry" class="form-label">APC Expiry</label>
                                <input id="edit_apc_expiry" name="apc_expiry" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_facility_name" class="form-label">Facility Name</label>
                                <input id="edit_facility_name" name="facility_name" type="text" class="form-control"
                                    placeholder="e.g. Hospital Kuala Lumpur" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_hc_state" class="form-label">Facility State</label>
                                <input id="edit_hc_state" name="state" type="text" class="form-control"
                                    placeholder="e.g. Selangor" required>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Contact Information (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_phone" class="form-label">Phone</label>
                                <input id="edit_phone" name="phone" type="text" class="form-control"
                                    placeholder="e.g. 012-3456789">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_address_line1" class="form-label">Address Line 1</label>
                                <input id="edit_address_line1" name="address_line1" type="text" class="form-control"
                                    placeholder="Street, Apartment, etc.">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_address_line2" class="form-label">Address Line 2</label>
                                <input id="edit_address_line2" name="address_line2" type="text" class="form-control"
                                    placeholder="Unit, Suite">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_city" class="form-label">City</label>
                                <input id="edit_city" name="city" type="text" class="form-control"
                                    placeholder="City">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_postcode" class="form-label">Postcode</label>
                                <input id="edit_postcode" name="postcode" type="text" class="form-control"
                                    placeholder="Postcode">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_state_profile" class="form-label">Mailing State</label>
                                <input id="edit_state_profile" name="state_profile" type="text" class="form-control"
                                    placeholder="State for mailing address">
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-light-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <form id="deleteForm" method="POST" action="{{ route('users.healthcare.destroy', '__id__') }}" class="d-none">
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
                    Are you sure you want to delete this healthcare professional?<br>
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
    <script src="{{ asset('assets/vendor/filepond/file-encode.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-type.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/js/ready_to_use_form.js') }}"></script>

    <script>
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const m = new bootstrap.Modal(document.getElementById('addHealthcareProfessional'));
                m.show();
            });
        @endif

        setTimeout(() => {
            const a = document.querySelector('.alert');
            if (a) a.remove();
        }, 4000);


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

        // User permissions for frontend permission checking
        const userPermissions = @json($userPermissions ?? []);
        
        // Helper function to check if user has permission
        function hasPermission(permission) {
            return userPermissions.includes(permission);
        }
        
        $(function() {
            $('#healthcareTable').DataTable({
                processing: true,
                ajax: {
                    url: '{{ route('users.healthcare.data') }}',
                    type: 'GET',
                    dataSrc: 'data'
                },
                columns: [
                    {
                        data: 'name',
                        render: function(data, type, row) {
                            const safeName = data || '—';
                            const sub = row.email || '';
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
                        data: 'profession',
                        render: d => d || '-'
                    },
                    {
                        data: 'apc_expiry',
                        render: function(d) {
                            if (!d) return '-';
                            const today = new Date();
                            const exp = new Date(d);
                            const expired = exp < new Date(today.toDateString());
                            const badge = expired ?
                                '<span class="badge bg-danger ms-2">Expired</span>' :
                                '<span class="badge bg-success ms-2">Valid</span>';
                            return `${new Date(d).toLocaleDateString()} ${badge}`;
                        }
                    },
                    {
                        data: 'facility_name',
                        render: d => d || '-'
                    },
                    {
                        data: 'hc_state',
                        render: d => d || '-'
                    },
                    {
                        data: 'profile_updated_at',
                        render: function(d, type, row) {
                            const dateStr = d || row.created_at;
                            return dateStr ? new Date(dateStr).toLocaleString() : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(row) {
                            const label = row.name || row.email || `ID ${row.id}`;
                            
                            let actionButtons = '';
                            
                            // Add Edit button if user has edit permission
                            if (hasPermission('users.edit')) {
                                actionButtons += `
                                    <li>
                                        <button type="button" class="dropdown-item edit-btn"
                                                data-id="${row.id}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editHealthcareProfessional">
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
                form.action = "{{ route('users.healthcare.destroy', '__id__') }}".replace('__id__', deleteId);
                form.submit();
            });
        });

        $('#addHealthcareProfessional, #editHealthcareProfessional').on('show.bs.modal', function() {
            $('#add_phone, #edit_phone').mask('000-0000000');
        });

        $(document).on('click', '#healthcareTable .edit-btn', function() {
            const table = $('#healthcareTable').DataTable();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            const $modal = $('#editHealthcareProfessional');
            const $form = $modal.find('form');

            const actionTemplate = $form.attr('data-action-template') || $form.attr('action');
            if (actionTemplate) {
                $form.attr('action', actionTemplate.replace('__ID__', rowData.id));
            }

            $modal.find('input[name="name"]').val(rowData.name ?? '');
            $modal.find('input[name="email"]').val(rowData.email ?? '');


            $modal.find('select[name="profession"]').val(rowData.profession ?? '').trigger('change');

            const apc = rowData.apc_expiry ? String(rowData.apc_expiry).slice(0, 10) : '';
            $modal.find('input[name="apc_expiry"]').val(apc);

            $modal.find('input[name="facility_name"]').val(rowData.facility_name ?? '');
            $modal.find('input[name="state"]').val(rowData.hc_state ?? '');

            $modal.find('input[name="phone"]').val(rowData.phone ?? '');
            $modal.find('input[name="address_line1"]').val(rowData.address_line1 ?? '');
            $modal.find('input[name="address_line2"]').val(rowData.address_line2 ?? '');
            $modal.find('input[name="city"]').val(rowData.city ?? '');
            $modal.find('input[name="postcode"]').val(rowData.postcode ?? '');
            $modal.find('input[name="state_profile"]').val(rowData.state ?? '');

            const $editPhone = $modal.find('#edit_phone, input[name="phone"]');
            $editPhone.unmask().mask('000-0000000', { placeholder: '' }).trigger('input');

            const avatarUrl = rowData.avatar_url || rowData.profile?.avatar_url || '';
            const $preview = $modal.find('#imgPreviewEditHC').length ?
                $modal.find('#imgPreviewEditHC') :
                $modal.find('#imgPreviewEdit');

            $preview.css('background-image', avatarUrl ? `url('${avatarUrl}')` : '');

            $modal.modal('show');
        });
    </script>


@endsection
