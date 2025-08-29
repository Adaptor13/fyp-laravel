<style>
.evidence-file-item .btn-close {
    font-size: 0.6rem;
    padding: 0.1rem;
    margin-left: 0.25rem;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.evidence-file-item .btn-close:hover {
    opacity: 1;
}

.evidence-file-item .badge {
    transition: all 0.3s ease;
}

.evidence-file-item:hover .badge {
    background-color: #6c757d !important;
}

.evidence-file-badge {
    transition: all 0.2s ease;
}

.evidence-file-badge:hover {
    background-color: #5a6268 !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.evidence-file-badge:active {
    transform: translateY(0);
}
</style>

@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <!-- Reporter Information -->
    <div class="col-md-6">
        <h6 class="mb-3">Reporter Information</h6>
        
        <div class="mb-3">
            <label for="reporter_name" class="form-label">Reporter Name *</label>
            <input type="text" name="reporter_name" id="reporter_name" class="form-control" 
                   value="{{ old('reporter_name', $report->reporter_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="reporter_email" class="form-label">Reporter Email *</label>
            <input type="email" name="reporter_email" id="reporter_email" class="form-control" 
                   value="{{ old('reporter_email', $report->reporter_email) }}" required>
        </div>

        <div class="mb-3">
            <label for="reporter_phone" class="form-label">Reporter Phone</label>
            <input type="text" name="reporter_phone" id="reporter_phone" class="form-control" 
                   value="{{ old('reporter_phone', $report->reporter_phone) }}">
        </div>
    </div>

    <!-- Victim Information -->
    <div class="col-md-6">
        <h6 class="mb-3">Victim Information</h6>
        
        <div class="mb-3">
            <label for="victim_age" class="form-label">Victim Age</label>
            <input type="text" name="victim_age" id="victim_age" class="form-control" 
                   value="{{ old('victim_age', $report->victim_age) }}" placeholder="e.g., 12">
        </div>

        <div class="mb-3">
            <label for="victim_gender" class="form-label">Victim Gender</label>
            <select name="victim_gender" id="victim_gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="Male" {{ old('victim_gender', $report->victim_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('victim_gender', $report->victim_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('victim_gender', $report->victim_gender) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Abuse Types</label>
            @php
                $currentAbuseTypes = old('abuse_types', $report->abuse_types ?? []);
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
        </div>
    </div>
</div>

<hr>

<div class="row">
    <!-- Incident Details -->
    <div class="col-md-12">
        <h6 class="mb-3">Incident Details</h6>
        
        <div class="mb-3">
            <label for="incident_description" class="form-label">Incident Description *</label>
            <textarea name="incident_description" id="incident_description" class="form-control" 
                      rows="4" required>{{ old('incident_description', $report->incident_description) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="incident_location" class="form-label">Incident Location *</label>
                    <input type="text" name="incident_location" id="incident_location" class="form-control" 
                           value="{{ old('incident_location', $report->incident_location) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="incident_date" class="form-label">Incident Date *</label>
                    <input type="date" name="incident_date" id="incident_date" class="form-control" 
                           value="{{ old('incident_date', $report->incident_date) }}" required max="{{ date('Y-m-d') }}" onchange="validateIncidentDate(this)">
                    <small class="form-text text-muted">Cannot select future dates</small>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="suspected_abuser" class="form-label">Suspected Abuser</label>
            <input type="text" name="suspected_abuser" id="suspected_abuser" class="form-control" 
                   value="{{ old('suspected_abuser', $report->suspected_abuser) }}" placeholder="e.g., Parent/Guardian, Teacher, etc.">
        </div>

        <div class="mb-3">
            <label for="evidence" class="form-label">Evidence Files</label>
            <input type="file" name="evidence[]" id="evidence" class="form-control" multiple 
                   accept=".jpg,.jpeg,.png,.mp4,.pdf">
            <small class="form-text text-muted">You can select multiple files (JPG, PNG, MP4, PDF up to 20MB each)</small>
            
            @if(!empty($report->evidence))
                <div class="mt-2">
                    <small class="text-muted">Current files:</small>
                    <div id="current-evidence-files">
                        @foreach($report->evidence as $index => $file)
                            <div class="evidence-file-item d-inline-block me-2 mb-2" data-file="{{ $file }}">
                                <div class="badge bg-secondary d-flex align-items-center evidence-file-badge" 
                                     style="cursor: pointer;" data-file="{{ $file }}" data-filename="{{ basename($file) }}">
                                    <span class="me-2">{{ basename($file) }}</span>
                                    <button type="button" class="btn-close btn-close-white btn-sm remove-evidence" 
                                            data-file="{{ $file }}" aria-label="Remove file"></button>
                                </div>
                                <input type="hidden" name="existing_evidence[]" value="{{ $file }}">
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="removed_evidence" id="removed-evidence" value="">
                </div>
            @endif
        </div>
    </div>
</div>

<hr>

<div class="row">
    <!-- Case Management -->
    <div class="col-md-12">
        <h6 class="mb-3">Case Management</h6>
        
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="report_status" class="form-label">Status *</label>
                    <select name="report_status" id="report_status" class="form-select" required>
                        <option value="">Select Status</option>
                        <option value="Submitted" {{ old('report_status', $report->report_status) == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="Under Review" {{ old('report_status', $report->report_status) == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                        <option value="In Progress" {{ old('report_status', $report->report_status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ old('report_status', $report->report_status) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="Closed" {{ old('report_status', $report->report_status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="priority_level" class="form-label">Priority *</label>
                    <select name="priority_level" id="priority_level" class="form-select" required>
                        <option value="">Select Priority</option>
                        <option value="Low" {{ old('priority_level', $report->priority_level) == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority_level', $report->priority_level) == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority_level', $report->priority_level) == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <h6 class="mb-3">Case Assignments</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="law_enforcement_assignee" class="form-label">Law Enforcement</label>
                            <select name="assignees[]" id="law_enforcement_assignee" class="form-select">
                                <option value="">Select Law Enforcement Officer</option>
                                @foreach($assignableUsers->where('role.name', 'law_enforcement') as $user)
                                    <option value="{{ $user->id }}" {{ old('assignees.0', $roleAssignments['law_enforcement'] ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="healthcare_assignee" class="form-label">Healthcare Professional</label>
                            <select name="assignees[]" id="healthcare_assignee" class="form-select">
                                <option value="">Select Healthcare Professional</option>
                                @foreach($assignableUsers->where('role.name', 'healthcare') as $user)
                                    <option value="{{ $user->id }}" {{ old('assignees.1', $roleAssignments['healthcare'] ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="social_worker_assignee" class="form-label">Social Worker</label>
                            <select name="assignees[]" id="social_worker_assignee" class="form-select">
                                <option value="">Select Social Worker</option>
                                @foreach($assignableUsers->where('role.name', 'social_worker') as $user)
                                    <option value="{{ $user->id }}" {{ old('assignees.2', $roleAssignments['social_worker'] ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="child_welfare_assignee" class="form-label">Child Welfare Officer</label>
                            <select name="assignees[]" id="child_welfare_assignee" class="form-select">
                                <option value="">Select Child Welfare Officer</option>
                                @foreach($assignableUsers->where('role.name', 'gov_official') as $user)
                                    <option value="{{ $user->id }}" {{ old('assignees.3', $roleAssignments['gov_official'] ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Evidence Viewer Modal -->
<div class="modal fade" id="evidenceViewerModal" tabindex="-1" aria-labelledby="evidenceViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evidenceViewerModalLabel">Evidence File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="evidenceContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="downloadEvidenceLink" class="btn btn-primary" download>
                    <i class="ti ti-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle evidence file removal
    $(document).on('click', '.remove-evidence', function() {
        const fileToRemove = $(this).data('file');
        const $fileItem = $(this).closest('.evidence-file-item');
        const fileName = $(this).siblings('span').text();
        
        // Show confirmation dialog
        if (confirm(`Are you sure you want to remove "${fileName}"? This action cannot be undone.`)) {
            // Add to removed evidence list
            let removedEvidence = $('#removed-evidence').val();
            let removedArray = removedEvidence ? JSON.parse(removedEvidence) : [];
            
            if (!removedArray.includes(fileToRemove)) {
                removedArray.push(fileToRemove);
                $('#removed-evidence').val(JSON.stringify(removedArray));
            }
            
            // Remove the file item from display
            $fileItem.fadeOut(300, function() {
                $(this).remove();
            });
            
            // File removal handled silently - no alert needed
        }
    });
    
    // Handle evidence file viewing
    $(document).on('click', '.evidence-file-badge', function(e) {
        // Prevent triggering if clicking on remove button
        if ($(e.target).hasClass('remove-evidence') || $(e.target).closest('.remove-evidence').length) {
            return;
        }
        
        const filePath = $(this).data('file');
        const fileName = $(this).data('filename');
        const fileExtension = fileName.split('.').pop().toLowerCase();
        
        // Update modal title
        $('#evidenceViewerModalLabel').text(fileName);
        
        // Set download link
        $('#downloadEvidenceLink').attr('href', `/storage/${filePath}`);
        $('#downloadEvidenceLink').attr('download', fileName);
        
        // Show loading state
        $('#evidenceContent').html('<div class="text-center"><i class="ti ti-loader ti-spin fs-1"></i><p class="mt-2">Loading...</p></div>');
        
        // Show modal
        $('#evidenceViewerModal').modal('show');
        
        // Load content based on file type
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
            // Image file
            $('#evidenceContent').html(`
                <img src="/storage/${filePath}" class="img-fluid" alt="${fileName}" style="max-height: 70vh;">
            `);
        } else if (fileExtension === 'pdf') {
            // PDF file
            $('#evidenceContent').html(`
                <iframe src="/storage/${filePath}" width="100%" height="70vh" frameborder="0"></iframe>
            `);
        } else if (['mp4', 'avi', 'mov', 'wmv'].includes(fileExtension)) {
            // Video file
            $('#evidenceContent').html(`
                <video controls width="100%" style="max-height: 70vh;">
                    <source src="/storage/${filePath}" type="video/${fileExtension}">
                    Your browser does not support the video tag.
                </video>
            `);
        } else {
            // Other file types - show download link
            $('#evidenceContent').html(`
                <div class="text-center">
                    <i class="ti ti-file fs-1 text-muted"></i>
                    <p class="mt-2">This file type cannot be previewed.</p>
                    <p class="text-muted">Click the download button below to view the file.</p>
                </div>
            `);
        }
    });
    
    // Helper function to show alerts (if not already defined)
    if (typeof showAlert === 'undefined') {
        window.showAlert = function(message, type = 'info') {
            const alertClass = type === 'success' ? 'alert-success' : 
                              type === 'danger' ? 'alert-danger' : 
                              type === 'warning' ? 'alert-warning' : 'alert-info';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <strong>${type === 'success' ? 'Success:' : type === 'danger' ? 'Error:' : type === 'warning' ? 'Warning:' : 'Info:'}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            // Remove any existing alerts
            $('.alert').remove();
            
            // Add new alert at the top of the modal
            $('.modal-body').prepend(alertHtml);
            
            // Auto-remove alert after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        };
    }

    // Date validation function for edit case form
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
});
</script>

