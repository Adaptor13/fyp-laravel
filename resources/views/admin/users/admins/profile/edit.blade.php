@extends('layout.master')
@section('title', 'Edit Admin Profile')
@section('css')
    <!-- Additional CSS for profile page -->
    <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            color: white;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Edit Admin Profile</h4>
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
                        <i class="ti ti-user f-s-16 ms-2"></i>
                        <a href="#" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Profile</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-edit f-s-16 ms-2"></i>
                        <span class="f-s-14">Edit</span>
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Validation Errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-user-edit me-2"></i>
                            Edit My Profile
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Profile Avatar Section -->
                        <div class="text-center mb-4">
                            @if(Auth::user()->getAvatarUrl())
                                <img src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->name }}" class="profile-avatar">
                            @else
                                <div class="avatar-placeholder" style="{{ Auth::user()->getAvatarBackgroundStyle() }}">
                                    {{ Auth::user()->getInitials() }}
                                </div>
                            @endif
                            <div class="mt-3">
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    <i class="ti ti-mail me-1"></i>
                                    {{ $user->email }}
                                </a>
                                <small class="text-muted d-block">(Private — cannot be changed)</small>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}" placeholder="Enter your full name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->profile->phone ?? '') }}" placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Admin Specific Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display Name</label>
                                    <input name="display_name" type="text" class="form-control @error('display_name') is-invalid @enderror"
                                        value="{{ old('display_name', $user->adminProfile->display_name ?? '') }}" placeholder="Enter display name">
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Department</label>
                                    <input name="department" type="text" class="form-control @error('department') is-invalid @enderror"
                                        value="{{ old('department', $user->adminProfile->department ?? '') }}" placeholder="Enter department">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Position</label>
                                    <input name="position" type="text" class="form-control @error('position') is-invalid @enderror"
                                        value="{{ old('position', $user->adminProfile->position ?? '') }}" placeholder="Enter position">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address Information -->
                            <hr class="my-4">
                            <h6 class="mb-3">
                                <i class="ti ti-map-pin me-2"></i>
                                Address Information
                            </h6>

                            <div class="mb-3">
                                <label class="form-label">Address Line 1</label>
                                <input name="address_line1" type="text" class="form-control @error('address_line1') is-invalid @enderror"
                                    value="{{ old('address_line1', $user->profile->address_line1 ?? '') }}" placeholder="Enter address line 1">
                                @error('address_line1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address Line 2</label>
                                <input name="address_line2" type="text" class="form-control @error('address_line2') is-invalid @enderror"
                                    value="{{ old('address_line2', $user->profile->address_line2 ?? '') }}" placeholder="Enter address line 2 (optional)">
                                @error('address_line2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">City</label>
                                    <input name="city" type="text" class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city', $user->profile->city ?? '') }}" placeholder="Enter city">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Postcode</label>
                                    <input name="postcode" type="text" inputmode="numeric" pattern="\d{5}" maxlength="5" 
                                        class="form-control @error('postcode') is-invalid @enderror"
                                        value="{{ old('postcode', $user->profile->postcode ?? '') }}" placeholder="Enter postcode">
                                    @error('postcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State</label>
                                    @php
                                        $states = [
                                            'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 
                                            'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 
                                            'Terengganu', 'W.P. Kuala Lumpur', 'W.P. Labuan', 'W.P. Putrajaya'
                                        ];
                                        $stateVal = old('state', $user->profile->state ?? '');
                                    @endphp
                                    <select name="state" class="form-select @error('state') is-invalid @enderror">
                                        <option value="">-- Select State --</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state }}" {{ $stateVal === $state ? 'selected' : '' }}>
                                                {{ $state }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    Save Changes
                                </button>
                                <a href="{{ route('admin_index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="ti ti-x me-1"></i>
                                    Cancel
                                </a>
                            </div>
                        </form>

                        <!-- Danger Zone -->
                        <hr class="my-4">
                        <div class="text-center">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="ti ti-trash me-1"></i>
                                Delete My Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Danger Zone
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ti ti-alert-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center">
                        Are you absolutely sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>
                        Cancel
                    </button>
                    <form action="{{ route('admin.profile.destroy') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash me-1"></i>
                            Yes, Delete My Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide success message after 3 seconds
        setTimeout(() => {
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.remove();
            }
        }, 3000);
    </script>
@endsection
