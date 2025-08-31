@extends('layout.admin')

@section('title', 'Law Enforcement Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ti ti-user-circle me-2"></i>
                            Law Enforcement Profile
                        </h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="ti ti-trash me-1"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ti ti-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ti ti-alert-circle me-2"></i>
                            Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('law.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="ti ti-user me-2"></i>Personal Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->profile?->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="ti ti-map-pin me-2"></i>Address Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_line1" class="form-label">Address Line 1</label>
                                    <input type="text" class="form-control @error('address_line1') is-invalid @enderror" 
                                           id="address_line1" name="address_line1" value="{{ old('address_line1', $user->profile?->address_line1) }}">
                                    @error('address_line1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_line2" class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control @error('address_line2') is-invalid @enderror" 
                                           id="address_line2" name="address_line2" value="{{ old('address_line2', $user->profile?->address_line2) }}">
                                    @error('address_line2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $user->profile?->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-select @error('state') is-invalid @enderror" id="state" name="state">
                                        <option value="">Select State</option>
                                        <option value="Johor" {{ old('state', $user->profile?->state) == 'Johor' ? 'selected' : '' }}>Johor</option>
                                        <option value="Kedah" {{ old('state', $user->profile?->state) == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                        <option value="Kelantan" {{ old('state', $user->profile?->state) == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                        <option value="Melaka" {{ old('state', $user->profile?->state) == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                        <option value="Negeri Sembilan" {{ old('state', $user->profile?->state) == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                        <option value="Pahang" {{ old('state', $user->profile?->state) == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                        <option value="Perak" {{ old('state', $user->profile?->state) == 'Perak' ? 'selected' : '' }}>Perak</option>
                                        <option value="Perlis" {{ old('state', $user->profile?->state) == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                        <option value="Pulau Pinang" {{ old('state', $user->profile?->state) == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                        <option value="Sabah" {{ old('state', $user->profile?->state) == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                        <option value="Sarawak" {{ old('state', $user->profile?->state) == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                        <option value="Selangor" {{ old('state', $user->profile?->state) == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                        <option value="Terengganu" {{ old('state', $user->profile?->state) == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                        <option value="W.P. Kuala Lumpur" {{ old('state', $user->profile?->state) == 'W.P. Kuala Lumpur' ? 'selected' : '' }}>W.P. Kuala Lumpur</option>
                                        <option value="W.P. Labuan" {{ old('state', $user->profile?->state) == 'W.P. Labuan' ? 'selected' : '' }}>W.P. Labuan</option>
                                        <option value="W.P. Putrajaya" {{ old('state', $user->profile?->state) == 'W.P. Putrajaya' ? 'selected' : '' }}>W.P. Putrajaya</option>
                                    </select>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="postcode" class="form-label">Postcode</label>
                                    <input type="text" class="form-control @error('postcode') is-invalid @enderror" 
                                           id="postcode" name="postcode" value="{{ old('postcode', $user->profile?->postcode) }}" maxlength="5">
                                    @error('postcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Law Enforcement Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="ti ti-shield me-2"></i>Law Enforcement Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agency" class="form-label">Agency</label>
                                    <select class="form-select @error('agency') is-invalid @enderror" id="agency" name="agency">
                                        <option value="">Select Agency</option>
                                        <option value="PDRM" {{ old('agency', $user->lawEnforcementProfile?->agency) == 'PDRM' ? 'selected' : '' }}>PDRM</option>
                                        <option value="AADK" {{ old('agency', $user->lawEnforcementProfile?->agency) == 'AADK' ? 'selected' : '' }}>AADK</option>
                                    </select>
                                    @error('agency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="badge_number" class="form-label">Badge Number</label>
                                    <input type="text" class="form-control @error('badge_number') is-invalid @enderror" 
                                           id="badge_number" name="badge_number" value="{{ old('badge_number', $user->lawEnforcementProfile?->badge_number) }}">
                                    @error('badge_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rank" class="form-label">Rank</label>
                                    <input type="text" class="form-control @error('rank') is-invalid @enderror" 
                                           id="rank" name="rank" value="{{ old('rank', $user->lawEnforcementProfile?->rank) }}" placeholder="e.g. Inspector, Sergeant">
                                    @error('rank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="station" class="form-label">Station</label>
                                    <input type="text" class="form-control @error('station') is-invalid @enderror" 
                                           id="station" name="station" value="{{ old('station', $user->lawEnforcementProfile?->station) }}" placeholder="e.g. IPD Petaling Jaya">
                                    @error('station')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="ti ti-alert-triangle text-danger me-2"></i>Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <p class="text-danger mb-0"><strong>Warning:</strong> All your data will be permanently deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('law.profile.destroy') }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i> Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // Phone number masking
        $('#phone').mask('000-0000000');
        
        // Postcode validation - only numbers
        $('#postcode').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endsection
