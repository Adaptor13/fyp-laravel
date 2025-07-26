@extends('layout.landing')

@section('title', 'Report')

@section('content')
    <div class="col-12 col-md-10 col-lg-8 mx-auto my-4">
        <div class="card">
            <div class="card-header">
                <h5>Report a Child Protection Concern</h5>
                <p class="text-muted mb-0">Your identity is optional. All reports are confidential and protected under PDPA.</p>
            </div>
            <div class="card-body">
                <form class="report-form">
                    <div class="row">
                        <!-- Reporter Identity (Optional) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Your Name (Optional)</label>
                                <input type="text" class="form-control" placeholder="Enter Your Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Your Email (Optional)</label>
                                <input type="email" class="form-control" placeholder="Enter Your Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Your Contact Number (Optional)</label>
                                <input type="tel" class="form-control" placeholder="Enter Your Phone">
                            </div>
                        </div>

                        <!-- Victim Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Victim's Approximate Age</label>
                                <input type="number" class="form-control" min="0" max="17" required>
                            </div>
                        </div>
                        <div class="col-md-6 floating-select">
                            <div class="mb-3">
                                <label class="form-label">Victim's Gender</label>
                                <select class="form-select" required>
                                    <option selected disabled>Select Gender</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Prefer not to say</option>
                                </select>
                            </div>
                        </div>

                        <!-- Incident Details -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type of Abuse</label>
                                <select class="form-select" multiple required>
                                    <option>Physical</option>
                                    <option>Sexual</option>
                                    <option>Neglect</option>
                                    <option>Emotional</option>
                                </select>
                                <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Incident Description</label>
                                <textarea class="form-control" rows="4" placeholder="Describe what happened..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Incident Location</label>
                                <textarea class="form-control" rows="2" placeholder="Enter full or approximate location" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date of Incident</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Suspected Abuser (Optional)</label>
                                <input type="text" class="form-control" placeholder="Name or relationship">
                            </div>
                        </div>

                        <!-- Upload Evidence -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Upload Evidence (Optional)</label>
                                <input type="file" class="form-control" accept="image/*,video/*,application/pdf">
                                <small class="form-text text-muted">You may upload photos, videos, or documents.</small>
                            </div>
                        </div>

                        <!-- Consent Confirmation -->
                        <div class="col-12">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="confirmConsent" required>
                                <label class="form-check-label" for="confirmConsent">I confirm that the information provided is accurate to the best of my knowledge.</label>
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
