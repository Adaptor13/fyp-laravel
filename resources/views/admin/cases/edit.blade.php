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
            <div class="form-check">
                <input type="checkbox" name="abuse_types[]" value="Physical Abuse" class="form-check-input" 
                       {{ in_array('Physical Abuse', old('abuse_types', $abuseTypes)) ? 'checked' : '' }}>
                <label class="form-check-label">Physical Abuse</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="abuse_types[]" value="Emotional Abuse" class="form-check-input" 
                       {{ in_array('Emotional Abuse', old('abuse_types', $abuseTypes)) ? 'checked' : '' }}>
                <label class="form-check-label">Emotional Abuse</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="abuse_types[]" value="Sexual Abuse" class="form-check-input" 
                       {{ in_array('Sexual Abuse', old('abuse_types', $abuseTypes)) ? 'checked' : '' }}>
                <label class="form-check-label">Sexual Abuse</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="abuse_types[]" value="Neglect" class="form-check-input" 
                       {{ in_array('Neglect', old('abuse_types', $abuseTypes)) ? 'checked' : '' }}>
                <label class="form-check-label">Neglect</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="abuse_types[]" value="Exploitation" class="form-check-input" 
                       {{ in_array('Exploitation', old('abuse_types', $abuseTypes)) ? 'checked' : '' }}>
                <label class="form-check-label">Exploitation</label>
            </div>
        </div>
    </div>
</div>

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
                           value="{{ old('incident_date', $report->incident_date) }}" required>
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
            
            @if($report->evidence)
                <div class="mt-2">
                    <small class="text-muted">Current files:</small>
                    @foreach(json_decode($report->evidence, true) ?? [] as $file)
                        <div class="badge bg-secondary me-1">{{ basename($file) }}</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

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
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', $currentAssignees)) ? 'selected' : '' }}>
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
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', $currentAssignees)) ? 'selected' : '' }}>
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
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', $currentAssignees)) ? 'selected' : '' }}>
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
                                @foreach($assignableUsers->where('role.name', 'child_welfare') as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', $currentAssignees)) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="primary_assignee" class="form-label">Primary Assignee (Lead)</label>
                            <select name="primary_assignee" id="primary_assignee" class="form-select">
                                <option value="">Select Primary Assignee</option>
                                @foreach($assignableUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('primary_assignee', $primaryAssigneeId) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ optional($user->role)->name }})
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

