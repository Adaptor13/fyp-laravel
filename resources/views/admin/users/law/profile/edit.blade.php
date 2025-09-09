@extends('layout.master')
@section('title', 'Edit Law Enforcement Profile')
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
        
        /* Avatar Upload Styles */
        .avatar-upload {
            position: relative;
            max-width: 120px;
            margin: 0 auto;
        }
        
        .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }
        
        .avatar-edit input {
            display: none;
        }
        
        .avatar-edit label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all .2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-edit label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }
        
        
        .avatar-preview {
            width: 120px;
            height: 120px;
            position: relative;
            border-radius: 50%;
            border: 6px solid #f8f8f8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            overflow: hidden;
        }
        
        .avatar-preview > div > div {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
        }
        
        /* Avatar upload styles for modals */
        .avatar-upload {
            position: relative;
            max-width: 120px;
            margin: 0 auto;
        }
        
        .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }
        
        .avatar-edit input {
            display: none;
        }
        
        .avatar-edit label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all .2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-edit label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }
        
        .avatar-preview {
            width: 120px;
            height: 120px;
            position: relative;
            border-radius: 50%;
            border: 6px solid #f8f8f8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            overflow: hidden;
        }
    </style>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="main-title">Edit Law Enforcement Profile</h4>
        </div>
        <div class="col-sm-6 mt-sm-2">
            <ul class="breadcrumb breadcrumb-start float-sm-end">
                <li class="d-flex">
                    <i class="ti ti-home f-s-16"></i>
                    <a href="{{ route('admin.dashboard') }}" class="f-s-14 d-flex gap-2">
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

                    <form method="POST" action="{{ route('law.profile.update') }}" enctype="multipart/form-data" onsubmit="return validateForm()" id="profileForm">
                        @csrf
                        @method('PUT')

                        @include('components.avatar-upload', ['user' => $user])

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Law Enforcement Profile Section -->
                        <hr class="my-4">
                        <h6 class="mb-3">
                            <i class="ti ti-shield me-2"></i>
                            Law Enforcement Profile
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agency <span class="text-danger">*</span></label>
                                <select name="agency" class="form-select @error('agency') is-invalid @enderror" required>
                                    <option value="">Select Agency</option>
                                    <option value="PDRM" {{ old('agency', $user->lawEnforcementProfile?->agency) == 'PDRM' ? 'selected' : '' }}>Polis Diraja Malaysia (PDRM)</option>
                                    <option value="AADK" {{ old('agency', $user->lawEnforcementProfile?->agency) == 'AADK' ? 'selected' : '' }}>Agensi Antidadah Kebangsaan (AADK)</option>
                                </select>
                                @error('agency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Badge Number <span class="text-danger">*</span></label>
                                <input name="badge_number" type="text" class="form-control @error('badge_number') is-invalid @enderror"
                                    value="{{ old('badge_number', $user->lawEnforcementProfile?->badge_number) }}" placeholder="Enter badge number" required>
                                @error('badge_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rank</label>
                                <input name="rank" type="text" class="form-control @error('rank') is-invalid @enderror"
                                    value="{{ old('rank', $user->lawEnforcementProfile?->rank) }}" placeholder="e.g. Inspector">
                                @error('rank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Station</label>
                                <input name="station" type="text" class="form-control @error('station') is-invalid @enderror"
                                    value="{{ old('station', $user->lawEnforcementProfile?->station) }}" placeholder="e.g. IPD Shah Alam">
                                @error('station')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6 mb-3">
                                 <label class="form-label">Placement State <span class="text-danger">*</span></label>
                                 <input name="le_state" type="text" class="form-control @error('le_state') is-invalid @enderror"
                                     value="{{ old('le_state', $user->lawEnforcementProfile?->state) }}" placeholder="e.g. Selangor" required>
                                 @error('le_state')
                                     <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                             </div>
                         </div>

                        <!-- Contact Information (Optional) -->
                        <hr class="my-4">
                        <h6 class="mb-3">
                            <i class="ti ti-map-pin me-2"></i>
                            Contact Information (Optional)
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->profile?->phone) }}" placeholder="e.g. 012-5254545">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address Line 1</label>
                                <input name="address_line1" type="text" class="form-control @error('address_line1') is-invalid @enderror"
                                    value="{{ old('address_line1', $user->profile?->address_line1) }}" placeholder="Street, Apartment, etc">
                                @error('address_line1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address Line 2</label>
                                <input name="address_line2" type="text" class="form-control @error('address_line2') is-invalid @enderror"
                                    value="{{ old('address_line2', $user->profile?->address_line2) }}" placeholder="Unit, Suite">
                                @error('address_line2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input name="city" type="text" class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city', $user->profile?->city) }}" placeholder="City">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Postcode</label>
                                <input name="postcode" type="text" class="form-control @error('postcode') is-invalid @enderror"
                                    value="{{ old('postcode', $user->profile?->postcode) }}" placeholder="43000">
                                @error('postcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mailing State</label>
                                <input name="state" type="text" class="form-control @error('state') is-invalid @enderror"
                                    value="{{ old('state', $user->profile?->state) }}" placeholder="State for mailing address">
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
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary ms-2">
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
                <form action="{{ route('law.profile.destroy') }}" method="POST" class="d-inline">
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
@endsection

@section('script')
    <script src="{{ asset('assets/vendor/filepond/file-encode.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-type.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/js/ready_to_use_form.js') }}"></script>

    <script>
        // Auto-hide success message after 3 seconds
        setTimeout(() => {
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.remove();
            }
        }, 3000);

        // Phone number masking using vanilla JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                // Set placeholder to show the format
                phoneInput.placeholder = '000-0000000';
                
                // Format existing value from database
                function formatPhoneNumber(value) {
                    let cleanValue = value.replace(/\D/g, ''); // Remove non-digits
                    
                    // Format as XXX-XXXXXXX
                    if (cleanValue.length > 0) {
                        if (cleanValue.length <= 3) {
                            return cleanValue;
                        } else if (cleanValue.length <= 10) {
                            return cleanValue.substring(0, 3) + '-' + cleanValue.substring(3, 10);
                        } else {
                            // Limit to 10 digits
                            return cleanValue.substring(0, 3) + '-' + cleanValue.substring(3, 10);
                        }
                    }
                    return cleanValue;
                }
                
                // Format the initial value from database
                if (phoneInput.value) {
                    phoneInput.value = formatPhoneNumber(phoneInput.value);
                }
                
                phoneInput.addEventListener('input', function(e) {
                    e.target.value = formatPhoneNumber(e.target.value);
                });
                
                // Handle backspace and delete
                phoneInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        // Allow backspace to work normally
                        return true;
                    }
                });
            }
        });

        // Avatar upload preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const imageUpload = document.getElementById('imageUpload');
            const imgPreview = document.getElementById('imgPreview');
            const form = document.getElementById('profileForm');
            
            if (imageUpload && imgPreview) {
                imageUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        console.log('File selected:', file.name, file.size, file.type);
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Clear any existing content and set background image
                            imgPreview.innerHTML = '';
                            imgPreview.style.backgroundImage = `url('${e.target.result}')`;
                            imgPreview.style.backgroundSize = 'cover';
                            imgPreview.style.backgroundPosition = 'center';
                            imgPreview.style.backgroundRepeat = 'no-repeat';
                            
                            // Show remove button if avatar is uploaded
                            const removeBtnContainer = document.querySelector('.text-center.mt-2');
                            if (removeBtnContainer) {
                                removeBtnContainer.style.display = 'block';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Add form submit event listener
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form is being submitted...');
                    const avatarFile = document.getElementById('imageUpload').files[0];
                    if (avatarFile) {
                        console.log('Avatar file at submit time:', avatarFile.name, avatarFile.size);
                    } else {
                        console.log('No avatar file at submit time');
                    }
                });
            }
        });

        // Form validation and debugging
        function validateForm() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            
            console.log('Form data being submitted:');
            for (let [key, value] of formData.entries()) {
                if (key === 'avatar') {
                    console.log(key + ':', value.name, value.size, value.type);
                } else {
                    console.log(key + ':', value);
                }
            }
            
            // Check if avatar file is actually in the form data
            const avatarFile = document.getElementById('imageUpload').files[0];
            if (avatarFile) {
                console.log('Avatar file found in input:', avatarFile.name, avatarFile.size);
            } else {
                console.log('No avatar file found in input');
            }
            
            // Check the file input element
            const fileInput = document.getElementById('imageUpload');
            console.log('File input element:', fileInput);
            console.log('File input name:', fileInput.name);
            console.log('File input files:', fileInput.files);
            console.log('File input files length:', fileInput.files.length);
            
            return true; // Allow form submission
        }

        // Remove avatar functionality
        function removeAvatar() {
            if (confirm('Are you sure you want to remove your avatar?')) {
                // Create a hidden input to indicate avatar removal
                const form = document.querySelector('form');
                const removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = 'remove_avatar';
                removeInput.value = '1';
                form.appendChild(removeInput);
                
                // Clear the file input
                const fileInput = document.getElementById('imageUpload');
                if (fileInput) {
                    fileInput.value = '';
                }
                
                // Reset preview to show initials
                const imgPreview = document.getElementById('imgPreview');
                if (imgPreview) {
                    imgPreview.style.backgroundImage = '';
                    imgPreview.innerHTML = `
                        <div class="d-flex align-items-center justify-content-center h-100" style="{{ Auth::user()->getAvatarBackgroundStyle() }}">
                            <span class="text-white fw-bold" style="font-size: 2.5rem;">{{ Auth::user()->getInitials() }}</span>
                        </div>
                    `;
                }
                
                // Hide remove button
                const removeBtn = document.querySelector('button[onclick="removeAvatar()"]');
                if (removeBtn) {
                    removeBtn.closest('.text-center').style.display = 'none';
                }
            }
        }
    </script>
@endsection
