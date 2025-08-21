@extends('layout.master')
@section('title', 'Public User')
@section('css')


    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/select/select2.min.css')}}">


@endsection


@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Manage Social Worker</h4>
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
                        <span class="f-s-14">Social Worker</span>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                        <h5 class="mb-0">Social Worker</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSocialWorker">Add</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="overflow-auto data-table-style app-scroll">
                            <table id="socialWorkersTable" class="display app-data-table deafult-data-tabel">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Staff ID</th>
                                        <th>Agency Name</th>
                                        <th>Agency Code</th>
                                        <th>Placement State</th>
                                        <th>Placement District</th>
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

    <div class="modal fade" id="addSocialWorker" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('users.social.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add Social Worker</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- Account Details -->
                        <h6 class="mb-3">Account Details</h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="imageUpload" name="avatar" accept=".png, .jpg, .jpeg">
                                            <label for="imageUpload"><i class="ti ti-photo-heart"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imgPreview"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input id= "name" name="name" type="text" class="form-control" value="{{ old('name') }}" placeholder="Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="email" class="form-control"  value="{{ old('email') }}" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password (8 minimum)" minlength="8" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" minlength="8" placeholder="Re-enter Password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Social Worker Profile -->
                        <h6 class="mb-3">Social Worker Profile</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="staff_id" class="form-label">Staff ID</label>
                                <input id="staff_id" name="staff_id" id="staff_id" type="text" class="form-control" value="{{ old('staff_id') }}" placeholder="Staff ID" required>
                            </div>

                            <div class="col-md-6 mb-3 floating">
                                <label for="agencyDropdown" class="form-label">Agency Name</label>
                                <select id="agencyDropdown" name="agency_name" class="form-select" required>
                                    <option value="" data-code="">Select Agency</option>
                                    <option value="Jabatan Kebajikan Masyarakat" data-code="JKM" {{ old('agency_name')==='Jabatan Kebajikan Masyarakat' ? 'selected' : '' }}>
                                    Jabatan Kebajikan Masyarakat (JKM)
                                    </option>
                                    <option value="Women’s Aid Organisation" data-code="WAO" {{ old('agency_name')==='Women’s Aid Organisation' ? 'selected' : '' }}>
                                    Women’s Aid Organisation (WAO)
                                    </option>
                                    <option value="Malaysian Social Workers Association" data-code="MSWA" {{ old('agency_name')==='Malaysian Social Workers Association' ? 'selected' : '' }}>
                                    Malaysian Social Workers Association (MSWA)
                                    </option>
                                    <option value="Other" data-code="" {{ old('agency_name')==='Other' ? 'selected' : '' }}>
                                    Other
                                    </option>
                                </select>
                            </div>
                        </div>

                        <input type="text" id="otherAgencyInput" name="agency_name_other" class="form-control mb-2" placeholder="Enter Agency Name" style="display:none;" value="{{ old('agency_name_other') }}">
  
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="agencyCodeInput" class="form-label">Agency Code</label>
                                <input  id="agencyCodeInput" name="agency_code" type="text" class="form-control" value="{{ old('agency_code') }}" placeholder="Agency Code" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="placement_state" class="form-label">Placement State</label>
                                <input id="placement_state" name="placement_state" type="text" class="form-control" value="{{ old('placement_state') }}" placeholder="State" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="placement_district" class="form-label">Placement District</label>
                            <input name="placement_district" id="placement_district" type="text" class="form-control" value="{{ old('placement_district') }}" placeholder="District" required>
                        </div>

                        <hr>

                        <!-- Contact Information (Optional) -->
                        <h6 class="mb-3">Contact Information (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input id="phone" name="phone" type="text" class="form-control" 
                                    value="{{ old('phone') }}" placeholder="e.g. 012-3456789">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address_line1" class="form-label">Address Line 1</label>
                                <input id="address_line1" name="address_line1" type="text" class="form-control" 
                                    value="{{ old('address_line1') }}" placeholder="Street, Apartment, etc.">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="address_line2" class="form-label">Address Line 2</label>
                                <input id="address_line2" name="address_line2" type="text" class="form-control" 
                                    value="{{ old('address_line2') }}" placeholder="Unit, Suite">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input id="city" name="city" type="text" class="form-control" 
                                    value="{{ old('city') }}" placeholder="City">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="postcode" class="form-label">Postcode</label>
                                <input id="postcode" name="postcode" type="text" class="form-control" 
                                    value="{{ old('postcode') }}" placeholder="Postcode">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input id="state" name="state" type="text" class="form-control" 
                                    value="{{ old('state') }}" placeholder="State">
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

    {{-- 
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title  text-white">Edit Public User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input class="form-control" name="name" id="edit_name" required placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" name="email" id="edit_email" type="email" disabled placeholder="johndoe@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input class="form-control" name="display_name" id="edit_display_name" placeholder="Johnny">
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="012-3456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Postcode</label>
                                <input class="form-control" name="postcode" id="edit_postcode" placeholder="12345">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Address Line 1</label>
                            <input class="form-control" name="address_line1" id="edit_address_line1" placeholder="123 Example Street">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Address Line 2</label>
                            <input class="form-control" name="address_line2" id="edit_address_line2" placeholder="Suite 456">
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input class="form-control" name="city" id="edit_city" placeholder="Exampleville">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <input class="form-control" name="state" id="edit_state" placeholder="Example State">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
    </div> --}}

@endsection

@section('script')

    <script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/vendor/filepond/file-encode.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/validate-size.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/validate-type.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/exif-orientation.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/image-preview.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/filepond.min.js')}}"></script>
    <script src="{{asset('assets/js/ready_to_use_form.js')}}"></script>

    <script>
        function handleAgencyChange(selectEl) {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            const otherInput = document.getElementById('otherAgencyInput');
            const codeInput  = document.getElementById('agencyCodeInput');

            if (!otherInput || !codeInput) return; // guard

            if (selectEl.value === 'Other') {
                otherInput.style.display = 'block';
                otherInput.required = true;
                codeInput.value = '';
                codeInput.readOnly = false; 
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
                codeInput.value = selectedOption.dataset.code || '';
                codeInput.readOnly = !!selectedOption.dataset.code; 
            }
            }

            document.addEventListener('DOMContentLoaded', function () {
            const sel = document.getElementById('agencyDropdown');
            if (!sel) return;

            handleAgencyChange(sel);

            sel.addEventListener('change', function (e) {
                handleAgencyChange(e.target);
            });
        });

        $('#addSocialWorker').on('show.bs.modal', function () {
            $('#phone').mask('000-0000000');
        });

        $(function () {
            $('#socialWorkersTable').DataTable({
                processing: true,
                ajax: {
                url: '{{ route('users.social.data') }}',
                type: 'GET',
                dataSrc: 'data'
                },
                columns: [
                {
                    data: 'name',
                    render: function (data, type, row) {
                    const imgSrc = row.avatar_url || "{{ asset('assets/images/icons/logo14.png') }}";
                    const safeName = data || '—';
                    const sub = row.email || '';
                    return `
                        <div class="d-flex justify-content-left align-items-center">
                        <div class="h-30 w-30 d-flex-center b-r-50 overflow-hidden me-2">
                            <img src="${imgSrc}" alt="${safeName}" class="img-fluid" style="width:30px;height:30px;object-fit:cover;">
                        </div>
                        <div>
                            <h6 class="f-s-15 mb-0">${safeName}</h6>
                            <p class="text-secondary f-s-13 mb-0">${sub}</p>
                        </div>
                        </div>
                    `;
                    }
                },
                { data: 'staff_id', render: d => d || '-' },
                { data: 'agency_name', render: d => d || '-' },
                { data: 'agency_code', render: d => d || '-' },
                { data: 'placement_state', render: d => d || '-' },
                { data: 'placement_district', render: d => d || '-' },
                {
                    data: 'profile_updated_at',
                    render: function (d, type, row) {
                    if (!d && !row.created_at) return '-';
                    const dateStr = d || row.created_at;
                    return dateStr ? new Date(dateStr).toLocaleString() : '-';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (row) {
                    const label = row.name || row.display_name || row.email || `ID ${row.id}`;
                    return `
                        <div class="dropdown">
                        <button class="bg-none border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                            <button type="button" class="dropdown-item edit-btn" data-id="${row.id}">
                                <i class="ti ti-edit text-success"></i> Edit
                            </button>
                            </li>
                            <li>
                            <a class="dropdown-item delete-btn" href="javascript:void(0)"
                                data-id="${row.id}"
                                data-label="${label}">
                                <i class="ti ti-trash text-danger"></i> Delete
                            </a>
                            </li>
                        </ul>
                        </div>
                    `;
                    }
                }
                ],
                responsive: true
            });
            });

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const m = new bootstrap.Modal(document.getElementById('addSocialWorker'));
                m.show();
            });
        @endif

        setTimeout(() => {
            const a = document.querySelector('.alert');
            if (a) a.remove();
        }, 4000);
    </script>


    {{-- <script>

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
    </script> --}}
@endsection
