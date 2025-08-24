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
                            <input type="file"
                                id="imageUploadAdd"
                                name="avatar"
                                accept=".png,.jpg,.jpeg"
                                data-preview="#imgPreviewAdd">
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
                                <input id= "add_name" name="name" type="text" class="form-control" value="{{ old('name') }}" placeholder="Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_email" class="form-label">Email</label>
                                <input id="add_email" name="email" type="email" class="form-control"  value="{{ old('email') }}" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input id="add_password" name="password" type="password" class="form-control" placeholder="Password (8 minimum)" minlength="8" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#add_password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input id="add_password_confirmation" name="password_confirmation" type="password" class="form-control" minlength="8" placeholder="Re-enter Password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#add_password_confirmationww">
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
                                <label for="add_staff_id" class="form-label">Staff ID</label>
                                <input name="staff_id" id="add_staff_id" type="text" class="form-control" value="{{ old('staff_id') }}" placeholder="Staff ID" required>
                            </div>

                            <div class="col-md-6 mb-3 floating">
                                <label for="add_agencyDropdown" class="form-label">Agency Name</label>
                                <select id="add_agencyDropdown" name="agency_name" class="form-select" required>
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

                        <input type="text" id="add_otherAgencyInput" name="agency_name_other" class="form-control mb-2" placeholder="Enter Agency Name" style="display:none;" value="{{ old('agency_name_other') }}">
  
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="add_agencyCodeInput" class="form-label">Agency Code</label>
                                <input  id="add_agencyCodeInput" name="agency_code" type="text" class="form-control" value="{{ old('agency_code') }}" placeholder="Agency Code" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="add_placement_state" class="form-label">Placement State</label>
                                <input id="add_placement_state" name="placement_state" type="text" class="form-control" value="{{ old('placement_state') }}" placeholder="State" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="add_placement_district" class="form-label">Placement District</label>
                            <input name="placement_district" id="add_placement_district" type="text" class="form-control" value="{{ old('placement_district') }}" placeholder="District" required>
                        </div>

                        <hr>

                        <!-- Contact Information (Optional) -->
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
                                <label for="add_state" class="form-label">State</label>
                                <input id="add_state" name="state" type="text" class="form-control" 
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

    {{-- <div class="modal fade" id="editSocialWorker" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('users.social.update', '__ID__') }}" data-action-template="{{ route('users.social.update', '__ID__') }}"
                    enctype="multipart/form-data">
                   
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Edit Social Worker</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <h6 class="mb-3">Account Details</h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file"
                                                id="imageUploadEdit"
                                                name="avatar"
                                                accept=".png,.jpg,.jpeg"
                                                data-preview="#imgPreviewEdit">
                                            <label for="imageUploadEdit"><i class="ti ti-photo-heart"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imgPreviewEdit"
                                                style="background-image:url('{{ $socialWorker->avatar_url ?? '' }}');"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_name" class="form-label">Name</label>
                                <input id="edit_name" name="name" type="text" class="form-control"
                                    value="{{ old('name') }}" placeholder="Name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input id="edit_email" name="email" type="email" class="form-control"
                                    value="{{ old('email') }}" readonly>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Social Worker Profile</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_staff_id" class="form-label">Staff ID</label>
                                <input id="edit_staff_id" name="staff_id" type="text" class="form-control"
                                    value="{{ old('staff_id') }}"
                                    placeholder="Staff ID" required>
                            </div>

                            <div class="col-md-6 mb-3 floating">
                                <label for="edit_agencyDropdown" class="form-label">Agency Name</label>
                                @php
                                    $agencyName = old('agency_name', '');
                                @endphp
                                <select id="edit_agencyDropdown" name="agency_name" class="form-select" required>
                                    <option value="" data-code="">Select Agency</option>

                                    <option value="Jabatan Kebajikan Masyarakat" data-code="JKM"
                                        {{ $agencyName==='Jabatan Kebajikan Masyarakat' ? 'selected' : '' }}>
                                        Jabatan Kebajikan Masyarakat (JKM)
                                    </option>

                                    <option value="Women’s Aid Organisation" data-code="WAO"
                                        {{ $agencyName==='Women’s Aid Organisation' ? 'selected' : '' }}>
                                        Women’s Aid Organisation (WAO)
                                    </option>

                                    <option value="Malaysian Social Workers Association" data-code="MSWA"
                                        {{ $agencyName==='Malaysian Social Workers Association' ? 'selected' : '' }}>
                                        Malaysian Social Workers Association (MSWA)
                                    </option>

                                    <option value="Other" data-code=""
                                        {{ $agencyName==='Other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                            </div>
                        </div>

                        @php
                            $agencyOther = old('agency_name_other', '');
                            $isOther = $agencyName === 'Other';
                        @endphp

                        <input type="text" id="edit_otherAgencyInput" name="agency_name_other" class="form-control mb-2"
                            placeholder="Enter Agency Name"
                            style="{{ $isOther ? '' : 'display:none;' }}"
                            value="{{ $agencyOther }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_agencyCodeInput" class="form-label">Agency Code</label>
                                <input id="edit_agencyCodeInput" name="agency_code" type="text" class="form-control"
                                    value="{{ old('agency_code') }}"
                                    placeholder="Agency Code" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_placement_state" class="form-label">Placement State</label>
                                <input id="edit_placement_state" name="placement_state" type="text" class="form-control"
                                    value="{{ old('placement_state') }}"
                                    placeholder="State" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="edit_placement_district" class="form-label">Placement District</label>
                            <input id="edit_placement_district" name="placement_district" type="text" class="form-control"
                                value="{{ old('placement_district') }}"
                                placeholder="District" required>
                        </div>

                        <hr>

                        <h6 class="mb-3">Contact Information (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_phone" class="form-label">Phone</label>
                                <input id="edit_phone" name="phone" type="text" class="form-control"
                                    value="{{ old('phone') }}"
                                    placeholder="e.g. 012-3456789">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_address_line1" class="form-label">Address Line 1</label>
                                <input id="edit_address_line1" name="address_line1" type="text" class="form-control"
                                    value="{{ old('address_line1') }}"
                                    placeholder="Street, Apartment, etc.">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_address_line2" class="form-label">Address Line 2</label>
                                <input id="edit_address_line2" name="address_line2" type="text" class="form-control"
                                    value="{{ old('address_line2') }}"
                                    placeholder="Unit, Suite">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_city" class="form-label">City</label>
                                <input id="edit_city" name="city" type="text" class="form-control"
                                    value="{{ old('city') }}"
                                    placeholder="City">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_postcode" class="form-label">Postcode</label>
                                <input id="edit_postcode" name="postcode" type="text" class="form-control"
                                    value="{{ old('postcode') }}"
                                    placeholder="Postcode">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_state" class="form-label">State</label>
                                <input id="edit_state" name="state" type="text" class="form-control"
                                    value="{{ old('state') }}"
                                    placeholder="State">
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
    </div> --}}

    <div class="modal fade" id="editSocialWorker" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('users.social.update', '__ID__') }}" 
                    data-action-template="{{ route('users.social.update', '__ID__') }}"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Edit Social Worker</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <h6 class="mb-3">Account Details</h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file"
                                                id="imageUploadEdit"
                                                name="avatar"
                                                accept=".png,.jpg,.jpeg"
                                                data-preview="#imgPreviewEdit">
                                            <label for="imageUploadEdit"><i class="ti ti-photo-heart"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imgPreviewEdit"
                                                style="background-image:url('{{ $socialWorker->avatar_url ?? '' }}');"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_name" class="form-label">Name</label>
                                <input id="edit_name" name="name" type="text" class="form-control"
                                    placeholder="Name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input id="edit_email" name="email" type="email" class="form-control" readonly>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Social Worker Profile</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_staff_id" class="form-label">Staff ID</label>
                                <input id="edit_staff_id" name="staff_id" type="text" class="form-control"
                                    placeholder="Staff ID" required>
                            </div>

                            <div class="col-md-6 mb-3 floating">
                                <label for="edit_agencyDropdown" class="form-label">Agency Name</label>
                                <select id="edit_agencyDropdown" name="agency_name" class="form-select" required>
                                    <option value="" data-code="">Select Agency</option>
                                    <option value="Jabatan Kebajikan Masyarakat" data-code="JKM">Jabatan Kebajikan Masyarakat (JKM)</option>
                                    <option value="Women’s Aid Organisation" data-code="WAO">Women’s Aid Organisation (WAO)</option>
                                    <option value="Malaysian Social Workers Association" data-code="MSWA">Malaysian Social Workers Association (MSWA)</option>
                                    <option value="Other" data-code="">Other</option>
                                </select>
                            </div>
                        </div>

                        <input type="text" id="edit_otherAgencyInput" name="agency_name_other" 
                            class="form-control mb-2"
                            placeholder="Enter Agency Name"
                            style="display:none;">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_agencyCodeInput" class="form-label">Agency Code</label>
                                <input id="edit_agencyCodeInput" name="agency_code" type="text" class="form-control"
                                    placeholder="Agency Code" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_placement_state" class="form-label">Placement State</label>
                                <input id="edit_placement_state" name="placement_state" type="text" class="form-control"
                                    placeholder="State" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="edit_placement_district" class="form-label">Placement District</label>
                            <input id="edit_placement_district" name="placement_district" type="text" class="form-control"
                                placeholder="District" required>
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
                                <label for="edit_state" class="form-label">State</label>
                                <input id="edit_state" name="state" type="text" class="form-control"
                                    placeholder="State">
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



    <form id="deleteForm" method="POST" action="{{ route('users.social.destroy', '__id__') }}" class="d-none">
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
    <script src="{{asset('assets/vendor/filepond/file-encode.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/validate-size.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/validate-type.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/exif-orientation.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/image-preview.min.js')}}"></script>
    <script src="{{asset('assets/vendor/filepond/filepond.min.js')}}"></script>
    <script src="{{asset('assets/js/ready_to_use_form.js')}}"></script>

    <script>
        function updateAgencyFields(modalEl) {
            if (!modalEl) return;

            const sel        = modalEl.querySelector('#add_agencyDropdown');
            const otherInput = modalEl.querySelector('#add_otherAgencyInput');
            const codeInput  = modalEl.querySelector('#add_agencyCodeInput');
            if (!sel || !otherInput || !codeInput) return;

            // Handle "incoming" prefill values (edit mode / validation errors)
            const incoming = (sel.getAttribute('data-incoming') || '').trim();
            const incomingOther = (sel.getAttribute('data-incoming-other') || '').trim();

            if (sel.selectedIndex === -1 && incoming) {
                const norm = s => s.toLowerCase();
                let matched = '';

                for (const opt of sel.options) {
                    if (!opt.value || opt.value === 'Other') continue;
                    if (norm(opt.value) === norm(incoming)) {
                        matched = opt.value;
                        break;
                    }
                }

                if (matched) {
                    sel.value = matched;
                } else {
                    sel.value = 'Other';
                    otherInput.style.display = 'block';
                    otherInput.required = true;
                    otherInput.value = incomingOther || incoming;
                }
            }

            const selectedOption = sel.options[sel.selectedIndex];

            if (sel.value === 'Other') {
                otherInput.style.display = 'block';
                otherInput.required = true;

                // Clear and unlock agency code
                codeInput.value = '';
                codeInput.readOnly = false;
                return;
            }

            otherInput.style.display = 'none';
            otherInput.required = false;

            const code = (selectedOption?.dataset?.code || '').trim();
            if (code) {
                codeInput.value = code;
                codeInput.readOnly = true;
            } else {
                codeInput.value = '';
                codeInput.readOnly = false;
            }
        }

        function initAgencyHandlers(modalSelector) {
            const modalEl = document.querySelector(modalSelector);
            if (!modalEl) return;

            const sel = modalEl.querySelector('#add_agencyDropdown');
            if (!sel) return;

            sel.addEventListener('change', function () {
                updateAgencyFields(modalEl);
            });

            modalEl.addEventListener('shown.bs.modal', function () {
                updateAgencyFields(modalEl);
            });

            updateAgencyFields(modalEl);
        }

        document.addEventListener('DOMContentLoaded', function () {
            initAgencyHandlers('#addSocialWorker');
        });

        $('#addSocialWorker, #editSocialWorker').on('show.bs.modal', function () {
            $('#add_phone, #edit_phone').mask('000-0000000');
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
                           <button type="button" class="dropdown-item edit-btn" 
                                    data-id="${row.id}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editSocialWorker">
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
                form.action = "{{ route('users.social.destroy', '__id__') }}".replace('__id__', deleteId);
                form.submit();
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

    <script>

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

    <script>
    (function () {
        function bindAgencyHandlers($modal) {
            const $agency = $modal.find('#edit_agencyDropdown');
            const $other  = $modal.find('#edit_otherAgencyInput');
            const $code   = $modal.find('input[name="agency_code"]');

            // Ensure we only bind once per modal
            if ($agency.data('bound-change')) return;
            $agency.data('bound-change', true);

            $agency.on('change', function () {
                const val = $(this).val();
                const $opt = $(this).find('option:selected');
                const code = ($opt.data('code') ?? '').toString();

                if (code) $code.val(code);
                const isOther = val === 'Other';
                $other.toggle(isOther);
                $other.prop('required', isOther);

                if (!isOther) $other.val('');
            });
        }

        function setAgencyFromRow($modal, rowData) {
            const $agency = $modal.find('#edit_agencyDropdown');
            const $other  = $modal.find('#edit_otherAgencyInput');

            const incomingName  = (rowData.agency_name || '').trim();
            const incomingOther = (rowData.agency_name_other || '').trim();

            const options = $agency.find('option').map(function () {
                return ($(this).val() || '').trim();
            }).get();

            if (incomingName && options.includes(incomingName)) {
                $agency.val(incomingName).trigger('change');
                if (incomingName === 'Other') {
                    $other.val(incomingOther || '');
                }
                return;
            }

            if (incomingName && !options.includes(incomingName)) {
                $agency.val('Other').trigger('change');
                $other.val(incomingOther || incomingName);
                return;
            }

            $agency.val('').trigger('change');
            $other.val('');
        }

        $(document).on('click', '#socialWorkersTable .edit-btn', function () {
        
            const table   = $('#socialWorkersTable').DataTable();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            const $modal = $('#editSocialWorker');
            const $form  = $modal.find('form');
   
            bindAgencyHandlers($modal);

            const actionTemplate = $form.attr('data-action-template') || $form.attr('action');
            if (actionTemplate) {
                $form.attr('action', actionTemplate.replace('__ID__', rowData.id));
            }

            $modal.find('input[name="name"]').val(rowData.name ?? '');
            $modal.find('input[name="email"]').val(rowData.email ?? '');
            $modal.find('input[name="staff_id"]').val(rowData.staff_id ?? '');

            $modal.find('input[name="agency_code"]').val(rowData.agency_code ?? '');
            $modal.find('input[name="placement_state"]').val(rowData.placement_state ?? '');
            $modal.find('input[name="placement_district"]').val(rowData.placement_district ?? '');

            $modal.find('input[name="phone"]').val(rowData.phone ?? '');
            $modal.find('input[name="address_line1"]').val(rowData.address_line1 ?? '');
            $modal.find('input[name="address_line2"]').val(rowData.address_line2 ?? '');
            $modal.find('input[name="city"]').val(rowData.city ?? '');
            $modal.find('input[name="postcode"]').val(rowData.postcode ?? '');
            $modal.find('input[name="state"]').val(rowData.state ?? '');

            const avatarUrl = rowData.avatar_url || rowData.profile?.avatar_url || '';
            $modal.find('#imgPreviewEdit').css('background-image', avatarUrl ? `url('${avatarUrl}')` : '');
            setAgencyFromRow($modal, rowData);

            $modal.modal('show');
        });

        $(document).on('change', '#imageUploadEdit', function () {
            const input = this;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#imgPreviewEdit').css('background-image', `url('${e.target.result}')`);
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    })();
</script>

@endsection
