@extends('layout.master')
@section('title', 'Case Details')
@section('css')
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Case Details</h4>
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
                        <a href="{{ route('cases.index') }}" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Cases</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-file-text f-s-16 ms-2"></i>
                        <span class="f-s-14">Case Details</span>
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Case #{{ substr($report->id, 0, 17) }}...</h5>
                        <div>
                            <a href="{{ route('cases.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Back to Cases
                            </a>
                            <button class="btn btn-info" onclick="exportCase('{{ $report->id }}')">
                                <i class="ti ti-download"></i> Export PDF
                            </button>
                            @permission('cases.edit')
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editCase" 
                                    onclick="loadEditForm('{{ $report->id }}')">
                                <i class="ti ti-edit"></i> Edit Case
                            </button>
                            @endpermission
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Case Status & Priority -->
                            <div class="col-md-12 mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Status</h6>
                                                @php
                                                    $statusClass = 'secondary';
                                                    if ($report->report_status === 'In Progress') $statusClass = 'success';
                                                    elseif (in_array($report->report_status, ['Closed', 'Resolved'])) $statusClass = 'danger';
                                                    elseif ($report->report_status === 'Under Review') $statusClass = 'warning';
                                                    elseif ($report->report_status === 'Submitted') $statusClass = 'info';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }} fs-6">{{ $report->report_status }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Priority</h6>
                                                @php
                                                    $priorityClass = 'secondary';
                                                    if ($report->priority_level === 'High') $priorityClass = 'danger';
                                                    elseif ($report->priority_level === 'Medium') $priorityClass = 'warning';
                                                    elseif ($report->priority_level === 'Low') $priorityClass = 'info';
                                                @endphp
                                                <span class="badge bg-{{ $priorityClass }} fs-6">{{ $report->priority_level }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Assigned To</h6>
                                                @if($assignees->count() > 0)
                                                    <div class="d-flex flex-column gap-1">
                                                        @foreach($assignees as $assignee)
                                                            <span class="badge bg-primary fs-6">
                                                                {{ $assignee->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary fs-6">Unassigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Created</h6>
                                                <small>{{ $report->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reporter Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="ti ti-user"></i> Reporter Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Name:</strong> {{ $report->reporter_name ?? 'Anonymous' }}</p>
                                        <p><strong>Email:</strong> {{ $report->reporter_email ?? 'Not provided' }}</p>
                                        @if($report->reporter_phone)
                                            <p><strong>Phone:</strong> {{ $report->reporter_phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Victim Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="ti ti-heart"></i> Victim Information</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($report->victim_age)
                                            <p><strong>Age:</strong> {{ $report->victim_age }}</p>
                                        @endif
                                        @if($report->victim_gender)
                                            <p><strong>Gender:</strong> {{ $report->victim_gender }}</p>
                                        @endif
                                        @if($report->abuse_types)
                                            <p><strong>Abuse Types:</strong></p>
                                            <div class="mb-2">
                                                @foreach(json_decode($report->abuse_types, true) ?? [] as $abuseType)
                                                    <span class="badge bg-danger me-1">{{ $abuseType }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Incident Details -->
                            <div class="col-md-12 mt-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="ti ti-alert-triangle"></i> Incident Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Location:</strong> {{ $report->incident_location }}</p>
                                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->incident_date)->format('M d, Y') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                @if($report->suspected_abuser)
                                                    <p><strong>Suspected Abuser:</strong> {{ $report->suspected_abuser }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>Description:</strong></p>
                                                <div class="border rounded p-3 bg-light">
                                                    {{ $report->incident_description }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Case Assignments -->
                            <div class="col-md-12 mt-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="ti ti-users"></i> Case Assignments</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($assignees->count() > 0)
                                            <div class="row">
                                                @foreach($assignees as $assignee)
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <div class="card border-primary">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="avatar avatar-sm bg-primary rounded-circle">
                                                                            <span class="avatar-text">{{ substr($assignee->name, 0, 1) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h6 class="mb-1">
                                                                            {{ $assignee->name }}
                                                                        </h6>
                                                                        <p class="text-muted mb-1">{{ optional($assignee->role)->name }}</p>
                                                                        <small class="text-muted">
                                                                            Assigned: {{ \Carbon\Carbon::parse($assignee->pivot->assigned_at)->format('M d, Y') }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="ti ti-users-off fs-1"></i>
                                                <p class="mt-2">No assignees for this case</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Evidence Files -->
                            @if($report->evidence)
                                <div class="col-md-12 mt-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="ti ti-paperclip"></i> Evidence Files</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach(json_decode($report->evidence, true) ?? [] as $file)
                                                <div class="mb-2">
                                                    <button type="button" class="btn btn-outline-primary btn-sm evidence-viewer-btn" 
                                                            data-file="{{ $file }}" data-filename="{{ basename($file) }}">
                                                        <i class="ti ti-eye"></i> {{ basename($file) }}
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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
@endsection

@section('script')
<script>
function loadEditForm(caseId) {
    // Show loading state
    $('#editCaseContent').html('<div class="text-center"><i class="ti ti-loader ti-spin"></i> Loading...</div>');
    
    // Fetch case data and populate form
    $.get(`/cases/${caseId}/edit`, function(data) {
        $('#editCaseContent').html(data);
        $('#editCaseForm').attr('action', `/cases/${caseId}`);
    }).fail(function() {
        $('#editCaseContent').html('<div class="alert alert-danger">Failed to load case data.</div>');
    });
}

function exportCase(caseId) {
    // Show loading state
    const $btn = $('button[onclick="exportCase(\'' + caseId + '\')"]');
    const originalText = $btn.html();
    $btn.html('<i class="ti ti-loader ti-spin"></i> Generating PDF...');
    $btn.prop('disabled', true);
    
    // Make AJAX request to export the case
    $.ajax({
        url: `/cases/${caseId}/export`,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            // Create download link
            const blob = new Blob([data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `Case_${caseId.substring(0, 8)}_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            // Reset button
            $btn.html(originalText);
            $btn.prop('disabled', false);
            
            // Show success message
            showAlert('PDF exported successfully!', 'success');
        },
        error: function(xhr, status, error) {
            // Reset button
            $btn.html(originalText);
            $btn.prop('disabled', false);
            
            let errorMessage = 'Failed to export PDF. Please try again.';
            if (xhr.status === 403) {
                errorMessage = 'Access denied. You do not have permission to export this case.';
            } else if (xhr.status === 404) {
                errorMessage = 'Case not found.';
            }
            
            showAlert(errorMessage, 'danger');
        }
    });
}

// Handle evidence file viewing
$(document).on('click', '.evidence-viewer-btn', function() {
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
        },
        error: function(xhr, status, error) {
            // Reset button
            $btn.html(originalText);
            $btn.prop('disabled', false);
            
            let errorMessage = 'Failed to export PDF. Please try again.';
            if (xhr.status === 403) {
                errorMessage = 'Access denied. You do not have permission to export this case.';
            } else if (xhr.status === 404) {
                errorMessage = 'Case not found.';
            }
            
            showAlert(errorMessage, 'danger');
        }
    });
}

// Helper function to show alerts
function showAlert(message, type = 'info') {
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
    
    // Add new alert at the top of the container
    $('.container-fluid').prepend(alertHtml);
    
    // Auto-remove alert after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
}
</script>
@endsection

