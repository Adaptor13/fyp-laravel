@extends('layout.landing')
@section('title', 'Edit Profile')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if (session('success'))
                    <div class="alert alert-success" id="flash-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-light"><strong>Edit My Profile</strong></div>

                    <div class="card-body">
                        <div class="text-center mb-4">
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                {{ $user->email }}
                            </a>
                            <small class="text-muted d-block">(Private — cannot be changed)</small>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT') 

                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">(Required)</span></label>
                                <input name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input name="phone"  class="form-control" id="phone" value="{{ old('phone', $user->profile && $user->profile->phone ? preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $user->profile->phone) : '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address Line 1</label>
                                <input name="address_line1" class="form-control" value="{{ old('address_line1', $user->profile->address_line1 ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address Line 2</label>
                                <input name="address_line2" class="form-control" value="{{ old('address_line2', $user->profile->address_line2 ?? '') }}">
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">City</label>
                                    <input name="city" class="form-control" value="{{ old('city', $user->profile->city ?? '') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Postcode</label>
                                    <input name="postcode" inputmode="numeric" pattern="\d{5}" maxlength="5" class="form-control" value="{{ old('postcode', $user->profile->postcode ?? '') }}">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State</label>
                                    @php
                                    $states = ['Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang','Perak','Perlis',
                                                'Pulau Pinang','Sabah','Sarawak','Selangor','Terengganu',
                                                'W.P. Kuala Lumpur','W.P. Labuan','W.P. Putrajaya'];
                                    $stateVal = old('state', $user->profile->state ?? '');
                                    @endphp
                                    <select name="state" class="form-select">
                                        <option value="">-- Select State --</option>
                                        @foreach($states as $s)
                                            <option value="{{ $s }}" {{ $stateVal === $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if ($user->role?->name === 'public_user')
                                <hr>
                                <h6>Public User Settings</h6>

                                <div class="mb-3">
                                    <label class="form-label">Display Name</label>
                                    <input name="display_name" class="form-control"
                                        value="{{ old('display_name', $user->publicUserProfile->display_name ?? '') }}">
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="allow_contact" name="allow_contact" value="1"
                                        {{ old('allow_contact', $user->publicUserProfile->allow_contact ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_contact">
                                        I agree to be contacted about my report
                                    </label>
                                </div>
                            @endif

                            <div class="text-center">
                                <button class="btn btn-primary px-4" type="submit">Save</button>
                                <a href="{{ route('landing') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Delete My Account
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Delete Account Modal (keep as-is from earlier) --}}
                <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-danger">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="deleteAccountModalLabel">Danger Zone</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you absolutely sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('profile.destroy') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                                </form>
                            </div>
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
                    <h5 class="modal-title" id="deleteAccountModalLabel">Danger Zone</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you absolutely sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('profile.destroy') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        setTimeout(() => document.getElementById('flash-success')?.remove(), 3000);
    </script>
@endsection
