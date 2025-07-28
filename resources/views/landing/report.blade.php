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
            </div>
            <div class="card-body">
                
                <form action="{{ route('report.store') }}" method="POST" class="report-form" enctype="multipart/form-data">
                     @csrf
                   <div class="row">
                        <!-- Reporter Identity (Optional) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reporter_name" class="form-label">Your Name (Optional)</label>
                                <input type="text" name="reporter_name" id="reporter_name" class="form-control" placeholder="Enter Your Name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reporter_email" class="form-label">Your Email (Optional)</label>
                                <input type="email" name="reporter_email" id="reporter_email" class="form-control" placeholder="Enter Your Email">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reporter_phone" class="form-label">Your Contact Number (Optional)</label>
                                <input type="tel" name="reporter_phone" id="reporter_phone" class="form-control" placeholder="Enter Your Phone">
                            </div>
                        </div>

                        <!-- Victim Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="victim_age" class="form-label">Victim's Approximate Age</label>
                                <input type="number" name="victim_age" id="victim_age" class="form-control" min="0" max="17" required>
                            </div>
                        </div>

                        <div class="col-md-6 floating-select">
                            <div class="mb-3">
                                <label for="victim_gender" class="form-label">Victim's Gender</label>
                                <select name="victim_gender" id="victim_gender" class="form-select" required>
                                    <option selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>
                        </div>

                        <!-- Incident Details -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="abuse_types" class="form-label">Type of Abuse</label>
                                <select name="abuse_types[]" id="abuse_types" class="form-select" multiple required>
                                    <option value="Physical">Physical</option>
                                    <option value="Sexual">Sexual</option>
                                    <option value="Neglect">Neglect</option>
                                    <option value="Emotional">Emotional</option>
                                </select>
                                <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="incident_description" class="form-label">Incident Description</label>
                                <textarea name="incident_description" id="incident_description" class="form-control" rows="4" placeholder="Describe what happened..." required></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="incident_location" class="form-label">Incident Location</label>
                                <textarea name="incident_location" id="incident_location" class="form-control" rows="2" placeholder="Enter full or approximate location" required></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="incident_date" class="form-label">Date of Incident</label>
                                <input type="date" name="incident_date" id="incident_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="suspected_abuser" class="form-label">Suspected Abuser (Optional)</label>
                                <input type="text" name="suspected_abuser" id="suspected_abuser" class="form-control" placeholder="Name or relationship">
                            </div>
                        </div>

                        <!-- Upload Evidence -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="evidence" class="form-label">Upload Evidence (Optional)</label>
                                <input type="file" name="evidence[]" id="evidence" class="form-control" accept="image/*,video/*,application/pdf" multiple>
                                <small class="form-text text-muted">You may upload photos, videos, or documents.</small>
                            </div>
                        </div>

                        <!-- Consent Confirmation -->
                        <div class="col-12">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="confirmed_truth" id="confirmed_truth" class="form-check-input" required>
                                <label class="form-check-label" for="confirmed_truth">I confirm that the information provided is accurate to the best of my knowledge.</label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-danger">Submit Report</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
