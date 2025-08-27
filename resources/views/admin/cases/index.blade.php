@extends('layout.master')
@section('title', 'Cases')
@section('css')

    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select/select2.min.css') }}">


@endsection


@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Manage Cases</h4>
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
                        <i class="ti ti-folder f-s-16 ms-2"></i>
                        <a href="#" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Cases</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-file-text f-s-16 ms-2"></i>
                        <span class="f-s-14">All Cases</span>
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
                                <h3 class="header-heading mb-0">{{ $stats['total'] ?? 0 }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Total Cases</p>
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
                                <h3 class="header-heading mb-0">{{ $stats['open'] ?? 0 }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Open Cases</p>
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
                                <h3 class="header-heading mb-0">{{ $stats['closed'] ?? 0 }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">Closed Cases</p>
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
                                <h3 class="header-heading mb-0">{{ $stats['new_this_week'] ?? 0 }}</h3>
                                <p class="f-w-300 f-s-12 mb-0">New This Week</p>
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
                        <h5 class="mb-0">Cases</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCase">
                            Add Case
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="overflow-auto data-table-style app-scroll">
                            <table id="casesTable" class="display app-data-table deafult-data-tabel">
                                <thead>
                                    <tr>
                                        <th>Case ID</th>
                                        <th>Reporter</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assigned To</th>
                                        <th>Updated</th>
                                        <th>Actions</th>
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

    <!-- Add Case Modal -->
    <div class="modal fade" id="addCase" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('cases.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add New Case</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
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
                                           value="{{ old('reporter_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="reporter_email" class="form-label">Reporter Email *</label>
                                    <input type="email" name="reporter_email" id="reporter_email" class="form-control" 
                                           value="{{ old('reporter_email') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="reporter_phone" class="form-label">Reporter Phone</label>
                                    <input type="text" name="reporter_phone" id="reporter_phone" class="form-control" 
                                           value="{{ old('reporter_phone') }}">
                                </div>
                            </div>

                            <!-- Victim Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Victim Information</h6>
                                
                                <div class="mb-3">
                                    <label for="victim_age" class="form-label">Victim Age</label>
                                    <input type="text" name="victim_age" id="victim_age" class="form-control" 
                                           value="{{ old('victim_age') }}" placeholder="e.g., 12">
                                </div>

                                <div class="mb-3">
                                    <label for="victim_gender" class="form-label">Victim Gender</label>
                                    <select name="victim_gender" id="victim_gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('victim_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('victim_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('victim_gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Abuse Types</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="abuse_types[]" value="Physical Abuse" class="form-check-input" 
                                               {{ in_array('Physical Abuse', old('abuse_types', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Physical Abuse</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="abuse_types[]" value="Emotional Abuse" class="form-check-input" 
                                               {{ in_array('Emotional Abuse', old('abuse_types', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Emotional Abuse</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="abuse_types[]" value="Sexual Abuse" class="form-check-input" 
                                               {{ in_array('Sexual Abuse', old('abuse_types', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Sexual Abuse</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="abuse_types[]" value="Neglect" class="form-check-input" 
                                               {{ in_array('Neglect', old('abuse_types', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Neglect</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="abuse_types[]" value="Exploitation" class="form-check-input" 
                                               {{ in_array('Exploitation', old('abuse_types', [])) ? 'checked' : '' }}>
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
                                              rows="4" required>{{ old('incident_description') }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="incident_location" class="form-label">Incident Location *</label>
                                            <input type="text" name="incident_location" id="incident_location" class="form-control" 
                                                   value="{{ old('incident_location') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="incident_date" class="form-label">Incident Date *</label>
                                            <input type="date" name="incident_date" id="incident_date" class="form-control" 
                                                   value="{{ old('incident_date') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="suspected_abuser" class="form-label">Suspected Abuser</label>
                                    <input type="text" name="suspected_abuser" id="suspected_abuser" class="form-control" 
                                           value="{{ old('suspected_abuser') }}" placeholder="e.g., Parent/Guardian, Teacher, etc.">
                                </div>

                                <div class="mb-3">
                                    <label for="evidence" class="form-label">Evidence Files</label>
                                    <input type="file" name="evidence[]" id="evidence" class="form-control" multiple 
                                           accept=".jpg,.jpeg,.png,.mp4,.pdf">
                                    <small class="form-text text-muted">You can select multiple files (JPG, PNG, MP4, PDF up to 20MB each)</small>
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
                                                <option value="Submitted" {{ old('report_status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                                <option value="Under Review" {{ old('report_status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                                <option value="In Progress" {{ old('report_status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Resolved" {{ old('report_status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="Closed" {{ old('report_status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="priority_level" class="form-label">Priority *</label>
                                            <select name="priority_level" id="priority_level" class="form-select" required>
                                                <option value="">Select Priority</option>
                                                <option value="Low" {{ old('priority_level') == 'Low' ? 'selected' : '' }}>Low</option>
                                                <option value="Medium" {{ old('priority_level') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="High" {{ old('priority_level') == 'High' ? 'selected' : '' }}>High</option>
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
                                                        @foreach(\App\Models\User::whereHas('role', function($q) { $q->where('name', 'law_enforcement'); })->get() as $user)
                                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', [])) ? 'selected' : '' }}>
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
                                                        @foreach(\App\Models\User::whereHas('role', function($q) { $q->where('name', 'healthcare'); })->get() as $user)
                                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', [])) ? 'selected' : '' }}>
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
                                                        @foreach(\App\Models\User::whereHas('role', function($q) { $q->where('name', 'social_worker'); })->get() as $user)
                                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', [])) ? 'selected' : '' }}>
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
                                                        @foreach(\App\Models\User::whereHas('role', function($q) { $q->where('name', 'child_welfare'); })->get() as $user)
                                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('assignees', [])) ? 'selected' : '' }}>
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
                                                        @foreach(\App\Models\User::whereIn('role_id', \App\Models\Role::whereIn('name', ['social_worker', 'law_enforcement', 'healthcare', 'child_welfare'])->pluck('id'))->get() as $user)
                                                            <option value="{{ $user->id }}" {{ old('primary_assignee') == $user->id ? 'selected' : '' }}>
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
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Case</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Case Modal -->
    <div class="modal fade" id="editCase" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" id="editCaseForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Edit Case</h5>
                        <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div id="editCaseContent">
                            <!-- Content will be loaded dynamically -->
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Update Case</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCaseModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Confirm Delete</h5>
                    <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this case? This action cannot be undone.</p>
                    <p class="mb-0"><strong>Case:</strong> <span id="deleteCaseLabel"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteCaseForm" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Case</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/file-encode.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-type.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/js/ready_to_use_form.js') }}"></script>

   <script>
    $(function () {
        $('#casesTable').DataTable({
            processing: true,
            ajax: {
                url: '{{ route('cases.data') }}',
                type: 'GET',
                dataSrc: 'data'
            },
            columns: [
                // Case ID column
                { 
                    data: 'case_id', 
                    name: 'case_id',
                    render: function (caseId) {
                        return `<span class="badge bg-secondary">${caseId}</span>`;
                    }
                },
                
                // Reporter column (name + email stacked)
                { 
                    data: 'reporter', 
                    name: 'reporter',
                    render: function (reporter) {
                        if (!reporter) return '—';
                        const safeName  = reporter.name || '—';
                        const safeEmail = reporter.email || '';
                        return `
                            <div>
                                <h6 class="mb-0">${safeName}</h6>
                                <p class="text-secondary mb-0">${safeEmail}</p>
                            </div>
                        `;
                    }
                },

                // Status with badge
                { 
                    data: 'status', 
                    name: 'status', 
                    render: function (d) {
                        if (!d) return '—';
                        let cls = 'secondary';
                        if (d === 'In Progress') cls = 'success';
                        else if (d === 'Closed' || d === 'Resolved') cls = 'danger';
                        else if (d === 'Under Review') cls = 'warning';
                        else if (d === 'Submitted') cls = 'info';
                        return `<span class="badge bg-${cls}">${d}</span>`;
                    }
                },

                // Priority with badge
                { 
                    data: 'priority', 
                    name: 'priority', 
                    render: function (d) {
                        if (!d) return '—';
                        let cls = 'secondary';
                        if (d === 'High') cls = 'danger';
                        else if (d === 'Medium') cls = 'warning';
                        else if (d === 'Low') cls = 'info';
                        return `<span class="badge bg-${cls}">${d}</span>`;
                    }
                },

                // Assigned with badge
                { 
                    data: 'assigned', 
                    name: 'assigned', 
                    render: function (d) {
                        return d 
                            ? `<span class="badge bg-primary">${d}</span>` 
                            : `<span class="badge bg-secondary">Unassigned</span>`;
                    }
                },

                // Updated date
                {
                    data: 'updated',
                    name: 'updated',
                    render: function (d) {
                        return d ? new Date(d).toLocaleString() : '—';
                    }
                },

                // Action buttons
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (row) {
                        // Use reporter name/email as label if available
                        const reporterName  = row.reporter?.name || '';
                        const reporterEmail = row.reporter?.email || '';
                        const label = reporterName || reporterEmail || `ID ${row.id}`;

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
                                                data-bs-target="#editCase">
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
            order: [[5, 'desc']], // sort by Updated (index 5, now that we added Case ID column)
            responsive: true
        });

        // Handle edit button clicks
        $(document).on('click', '.edit-btn', function() {
            const caseId = $(this).data('id');
            
            // Show loading state
            $('#editCaseContent').html('<div class="text-center"><i class="ti ti-loader ti-spin"></i> Loading...</div>');
            
            // Fetch case data and populate form
            $.get(`/cases/${caseId}/edit`, function(data) {
                $('#editCaseContent').html(data);
                $('#editCaseForm').attr('action', `/cases/${caseId}`);
            }).fail(function() {
                $('#editCaseContent').html('<div class="alert alert-danger">Failed to load case data.</div>');
            });
        });

        // Handle delete button clicks
        $(document).on('click', '.delete-btn', function() {
            const caseId = $(this).data('id');
            const label = $(this).data('label');
            
            $('#deleteCaseLabel').text(label);
            $('#deleteCaseForm').attr('action', `/cases/${caseId}`);
            $('#deleteCaseModal').modal('show');
        });
    });
</script>

<script>
    setTimeout(() => {
            const a = document.querySelector('.alert');
            if (a) a.remove();
        }, 4000);

</script>


@endsection