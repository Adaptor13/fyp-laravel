@extends('layout.landing')

@section('title', 'Report')

@section('content')
    <div class="col-12 col-md-10 col-lg-8 mx-auto my-4">
        @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        <div class="card">
            <div class="card-header">
                <h5>Report a Child Protection Concern</h5>
                <p class="text-muted mb-0">Your identity is optional. All reports are confidential and protected under PDPA.</p>
                <p class="text-muted mb-0"><small>* Required fields</small></p>
            </div>
            <div class="card-body">
                
                <form action="{{ route('report.store') }}" method="POST" class="report-form" enctype="multipart/form-data">
                     @csrf
                   <div class="row">
                        <!-- Reporter Identity (Optional) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reporter_name" class="form-label">Your Name (Optional)</label>
                                <input type="text"name="reporter_name"id="reporter_name" class="form-control"placeholder="Enter Your Name" value="{{ old('reporter_name', $prefillName) }}" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reporter_email" class="form-label">Your Email (Optional)</label>
                               <input type="email" name="reporter_email" id="reporter_email" class="form-control" placeholder="Enter Your Email" value="{{ old('reporter_email', $prefillEmail) }}" {{ $readonlyEmail ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Your Contact Number (Optional)</label>
                                <input
                                    type="tel"
                                    name="reporter_phone"
                                    id="phone"
                                    class="form-control"
                                    placeholder="Enter Your Phone"
                                    value="{{ old('reporter_phone', $prefillPhone) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="victim_age" class="form-label">Victim's Approximate Age *</label>
                                <input type="number"
                                    name="victim_age"
                                    id="victim_age"
                                    class="form-control"
                                    min="0"
                                    max="17"
                                    required
                                    value="{{ old('victim_age') }}"
                                    oninvalid="this.setCustomValidity('Please enter an age between 0 and 17.')"
                                    oninput="this.setCustomValidity('')">
                            </div>
                        </div>

                        <div class="col-md-6 floating-select">
                            <div class="mb-3">
                                <label for="victim_gender" class="form-label">Victim's Gender *</label>
                                <select name="victim_gender" id="victim_gender" class="form-select" required>
                                    <option value="" {{ old('victim_gender') == '' ? 'selected' : '' }} disabled>Select Gender</option>
                                    <option value="Male" {{ old('victim_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('victim_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('victim_gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type of Abuse *</label>
                                @php
                                    $currentAbuseTypes = old('abuse_types', []);
                                    $abuseTypesArray = is_array($currentAbuseTypes) ? $currentAbuseTypes : [];
                                @endphp
                                <div class="form-check">
                                    <input type="checkbox" name="abuse_types[]" value="Physical Abuse" class="form-check-input" 
                                           {{ in_array('Physical Abuse', $abuseTypesArray) ? 'checked' : '' }}>
                                    <label class="form-check-label">Physical Abuse</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="abuse_types[]" value="Emotional Abuse" class="form-check-input" 
                                           {{ in_array('Emotional Abuse', $abuseTypesArray) ? 'checked' : '' }}>
                                    <label class="form-check-label">Emotional Abuse</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="abuse_types[]" value="Sexual Abuse" class="form-check-input" 
                                           {{ in_array('Sexual Abuse', $abuseTypesArray) ? 'checked' : '' }}>
                                    <label class="form-check-label">Sexual Abuse</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="abuse_types[]" value="Neglect" class="form-check-input" 
                                           {{ in_array('Neglect', $abuseTypesArray) ? 'checked' : '' }}>
                                    <label class="form-check-label">Neglect</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="abuse_types[]" value="Exploitation" class="form-check-input" 
                                           {{ in_array('Exploitation', $abuseTypesArray) ? 'checked' : '' }}>
                                    <label class="form-check-label">Exploitation</label>
                                </div>
                                <small class="form-text text-muted">Please select at least one type of abuse</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="incident_description" class="form-label">Incident Description *</label>
                                <textarea name="incident_description" id="incident_description" class="form-control" rows="4" placeholder="Describe what happened..." required>{{ old('incident_description') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="incident_location" class="form-label">Incident Location *</label>
                                <textarea name="incident_location" id="incident_location" class="form-control" rows="2" placeholder="Enter full or approximate location" required>{{ old('incident_location') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="incident_date" class="form-label">Date of Incident *</label>
                                <input type="date" name="incident_date" id="incident_date" class="form-control" required max="{{ date('Y-m-d') }}" value="{{ old('incident_date') }}" onchange="validateIncidentDate(this)">
                                <small class="form-text text-muted">Cannot select future dates</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="suspected_abuser" class="form-label">Suspected Abuser (Optional)</label>
                                <input type="text" name="suspected_abuser" id="suspected_abuser" class="form-control" placeholder="Name or relationship" value="{{ old('suspected_abuser') }}">
                            </div>
                        </div>

                        <!-- Upload Evidence -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="evidence" class="form-label">Upload Evidence (Optional)</label>
                                <input type="file" name="evidence[]" id="evidence" class="form-control" accept="image/*,video/*,application/pdf" multiple onchange="validateFileUpload(this)">
                                <small class="form-text text-muted">You may upload photos, videos, or documents. Maximum 5 files allowed.</small>
                                <div id="file-validation-message" class="text-danger mt-1" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Consent Confirmation -->
                        <div class="col-12">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="confirmed_truth" id="confirmed_truth" class="form-check-input" required>
                                <label class="form-check-label" for="confirmed_truth">I confirm that the information provided is accurate to the best of my knowledge. *</label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12">
                            <div class="text-end">
                                
                                <button type="reset" class="btn btn-secondary" id="resetBtn">Reset</button>
                                <button type="submit" class="btn btn-danger" id="submitBtn">
                                    <span id="submitText">Submit Report</span>
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" style="display: none;"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateFileUpload(input) {
            const maxFiles = 5;
            const files = input.files;
            const messageDiv = document.getElementById('file-validation-message');
            
            if (files.length > maxFiles) {
                messageDiv.textContent = `Maximum ${maxFiles} files allowed. You selected ${files.length} files. Please select only ${maxFiles} files or fewer.`;
                messageDiv.style.display = 'block';
                messageDiv.className = 'text-danger mt-1';
                
                // Clear the input to prevent submission
                input.value = '';
                return false;
            } else if (files.length > 0) {
                // Show success message for valid selection
                messageDiv.textContent = `${files.length} file(s) selected. Maximum ${maxFiles} files allowed.`;
                messageDiv.style.display = 'block';
                messageDiv.className = 'text-success mt-1';
                return true;
            } else {
                // No files selected
                messageDiv.style.display = 'none';
                return true;
            }
        }

        function validateIncidentDate(input) {
            const selectedDate = new Date(input.value);
            const today = new Date();
            today.setHours(23, 59, 59, 999); // Set to end of today to allow today's date
            
            if (selectedDate > today) {
                alert('Please select a date that is not in the future.');
                input.value = ''; // Clear the invalid date
                input.focus();
                return false;
            }
            return true;
        }

        // Form validation before submit
        document.querySelector('.report-form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('evidence');
            const dateInput = document.getElementById('incident_date');
            const abuseTypeCheckboxes = document.querySelectorAll('input[name="abuse_types[]"]:checked');
            const messageDiv = document.getElementById('file-validation-message');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const resetBtn = document.getElementById('resetBtn');
            
            // Check if form is already being submitted
            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Validate abuse types selection
            if (abuseTypeCheckboxes.length === 0) {
                alert('Please select at least one type of abuse.');
                e.preventDefault();
                return false;
            }
            
            // Validate file upload - check file count
            if (fileInput.files.length > 5) {
                messageDiv.textContent = 'Maximum 5 files allowed. Please select fewer files before submitting.';
                messageDiv.style.display = 'block';
                messageDiv.className = 'text-danger mt-1';
                e.preventDefault();
                return false;
            }
            
            // Validate file upload - check individual file validation
            if (!validateFileUpload(fileInput)) {
                e.preventDefault();
                return false;
            }
            
            // Validate incident date
            if (dateInput.value && !validateIncidentDate(dateInput)) {
                e.preventDefault();
                return false;
            }
            
            // If all validations pass, disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitText.textContent = 'Submitting...';
            submitSpinner.style.display = 'inline-block';
        });
    </script>
@endsection
