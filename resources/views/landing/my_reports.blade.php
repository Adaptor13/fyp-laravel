@extends('layout.landing')

@section('title', 'My Reports')

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Submitted Reports</h2>
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table id="myReportsTable" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Report ID</th>
                                <th>Victim Age</th>
                                <th>Victim Gender</th>
                                <th>Abuse Types</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assignees</th>
                                <th>Submitted Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ substr($report->id, 0, 8) }}...</span>
                                    </td>
                                    <td>{{ $report->victim_age ?? 'Not specified' }}</td>
                                    <td>
                                        @if($report->victim_gender)
                                            <span class="badge bg-info">{{ ucfirst($report->victim_gender) }}</span>
                                        @else
                                            <span class="badge bg-secondary">Not specified</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report->abuse_types && is_array($report->abuse_types) && count($report->abuse_types) > 0)
                                            @foreach($report->abuse_types as $type)
                                                <span class="badge bg-warning text-dark me-1">{{ $type }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($report->report_status ?? 'Submitted') {
                                                'Submitted' => 'bg-primary',
                                                'Under Review' => 'bg-warning text-dark',
                                                'In Progress' => 'bg-info',
                                                'Resolved' => 'bg-success',
                                                'Closed' => 'bg-secondary',
                                                default => 'bg-primary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $report->report_status ?? 'Submitted' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $priorityClass = match($report->priority_level ?? 'Medium') {
                                                'High' => 'bg-danger',
                                                'Medium' => 'bg-warning text-dark',
                                                'Low' => 'bg-success',
                                                default => 'bg-warning text-dark'
                                            };
                                        @endphp
                                        <span class="badge {{ $priorityClass }}">{{ $report->priority_level ?? 'Medium' }}</span>
                                    </td>
                                    <td>
                                        @if($report->assignees && $report->assignees->count() > 0)
                                            @php
                                                $totalCount = $report->assignees->count();
                                            @endphp
                                            <span class="badge bg-success" title="{{ $totalCount }} assignee(s) total">
                                                <i class="bi bi-people me-1"></i>{{ $totalCount }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-person-x me-1"></i>None
                                            </span>
                                        @endif
                                    </td>
                                <td data-order="{{ $report->created_at->timestamp }}">{{ $report->created_at->format('d M Y H:i') }}</td>
                                     <td>
                                         <button class="btn btn-sm btn-outline-primary" title="View Details" 
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#reportModal{{ $loop->index }}">
                                             <i class="bi bi-eye"></i>
                                         </button>
                                     </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                 </div>

                 <!-- Report Detail Modals -->
                 @foreach($reports as $report)
                     <div class="modal fade" id="reportModal{{ $loop->index }}" tabindex="-1" aria-labelledby="reportModalLabel{{ $loop->index }}" aria-hidden="true">
                         <div class="modal-dialog modal-lg">
                             <div class="modal-content">
                                 <div class="modal-header">
                                     <h5 class="modal-title" id="reportModalLabel{{ $loop->index }}">
                                         <i class="bi bi-file-earmark-text me-2"></i>Report Details
                                     </h5>
                                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                 </div>
                                 <div class="modal-body">
                                     <div class="row">
                                         <!-- Report ID and Status -->
                                         <div class="col-12 mb-3">
                                             <div class="d-flex justify-content-between align-items-center">
                                                 <div>
                                                     <strong>Report ID:</strong> 
                                                     <span class="badge bg-secondary">{{ $report->id }}</span>
                                                 </div>
                                                 <div>
                                                     @php
                                                         $statusClass = match($report->report_status ?? 'Submitted') {
                                                             'Submitted' => 'bg-primary',
                                                             'Under Review' => 'bg-warning text-dark',
                                                             'In Progress' => 'bg-info',
                                                             'Resolved' => 'bg-success',
                                                             'Closed' => 'bg-secondary',
                                                             default => 'bg-primary'
                                                         };
                                                     @endphp
                                                     <span class="badge {{ $statusClass }} fs-6">{{ $report->report_status ?? 'Submitted' }}</span>
                                                 </div>
                                             </div>
                                         </div>

                                         <!-- Victim Information -->
                                         <div class="col-md-6 mb-3">
                                             <h6 class="text-primary"><i class="bi bi-person me-2"></i>Victim Information</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     <p><strong>Age:</strong> {{ $report->victim_age ?? 'Not specified' }}</p>
                                                     <p><strong>Gender:</strong> 
                                                         @if($report->victim_gender)
                                                             <span class="badge bg-info">{{ ucfirst($report->victim_gender) }}</span>
                                                         @else
                                                             <span class="text-muted">Not specified</span>
                                                         @endif
                                                     </p>
                                                 </div>
                                             </div>
                                         </div>

                                         <!-- Incident Information -->
                                         <div class="col-md-6 mb-3">
                                             <h6 class="text-primary"><i class="bi bi-geo-alt me-2"></i>Incident Information</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     <p><strong>Location:</strong> {{ $report->incident_location }}</p>
                                                     <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</p>
                                                     <p><strong>Priority:</strong> 
                                                         @php
                                                             $priorityClass = match($report->priority_level ?? 'Medium') {
                                                                 'High' => 'bg-danger',
                                                                 'Medium' => 'bg-warning text-dark',
                                                                 'Low' => 'bg-success',
                                                                 default => 'bg-warning text-dark'
                                                             };
                                                         @endphp
                                                         <span class="badge {{ $priorityClass }}">{{ $report->priority_level ?? 'Medium' }}</span>
                                                     </p>
                                                 </div>
                                             </div>
                                         </div>

                                         <!-- Abuse Types -->
                                         <div class="col-12 mb-3">
                                             <h6 class="text-primary"><i class="bi bi-exclamation-triangle me-2"></i>Abuse Types</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     @if($report->abuse_types && is_array($report->abuse_types) && count($report->abuse_types) > 0)
                                                         @foreach($report->abuse_types as $type)
                                                             <span class="badge bg-warning text-dark me-2 mb-2">{{ $type }}</span>
                                                         @endforeach
                                                     @else
                                                         <span class="text-muted">Not specified</span>
                                                     @endif
                                                 </div>
                                             </div>
                                         </div>

                                         <!-- Incident Description -->
                                         <div class="col-12 mb-3">
                                             <h6 class="text-primary"><i class="bi bi-chat-text me-2"></i>Incident Description</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     <p class="mb-0">{{ $report->incident_description }}</p>
                                                 </div>
                                             </div>
                                         </div>

                                         <!-- Suspected Abuser -->
                                         @if($report->suspected_abuser)
                                         <div class="col-md-6 mb-3">
                                             <h6 class="text-primary"><i class="bi bi-person-x me-2"></i>Suspected Abuser</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     <p class="mb-0">{{ $report->suspected_abuser }}</p>
                                                 </div>
                                             </div>
                                         </div>
                                         @endif

                                         <!-- Evidence -->
                                         @if($report->evidence)
                                         <div class="col-md-6 mb-3">
                                             <h6 class="text-primary"><i class="bi bi-paperclip me-2"></i>Evidence</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     @if($report->evidence && is_array($report->evidence) && count($report->evidence) > 0)
                                                         <p class="text-success mb-0">
                                                             <i class="bi bi-check-circle me-2"></i>{{ count($report->evidence) }} file(s) attached
                                                         </p>
                                                     @else
                                                         <p class="text-muted mb-0">No files attached</p>
                                                     @endif
                                                 </div>
                                             </div>
                                         </div>
                                         @endif

                                         <!-- Case Assignees -->
                                         <div class="col-12 mb-3">
                                             <h6 class="text-primary"><i class="bi bi-people me-2"></i>Case Assignees</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     @if($report->assignees && $report->assignees->count() > 0)
                                                         <div class="row">
                                                             @foreach($report->assignees as $assignee)
                                                                 <div class="col-md-6 mb-2">
                                                                     <div class="d-flex align-items-center">
                                                                         <div class="flex-shrink-0">
                                                                             <span class="badge bg-secondary me-2">
                                                                                 <i class="bi bi-person"></i> Assigned
                                                                             </span>
                                                                         </div>
                                                                         <div class="flex-grow-1">
                                                                             <strong>{{ $assignee->name }}</strong>
                                                                             @if($assignee->profile)
                                                                                 <br><small class="text-muted">{{ $assignee->profile->phone ?? 'No phone' }}</small>
                                                                             @endif
                                                                             <br><small class="text-muted">Assigned: {{ \Carbon\Carbon::parse($assignee->pivot->assigned_at)->format('d M Y, H:i') }}</small>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                             @endforeach
                                                         </div>
                                                     @else
                                                         <p class="text-muted mb-0">
                                                             <i class="bi bi-info-circle me-2"></i>No assignees have been assigned to this case yet.
                                                         </p>
                                                     @endif
                                                 </div>
                                             </div>
                                         </div>

                                         <!-- Timestamps -->
                                         <div class="col-12">
                                             <h6 class="text-primary"><i class="bi bi-clock me-2"></i>Timeline</h6>
                                             <div class="card">
                                                 <div class="card-body">
                                                     <div class="row">
                                                         <div class="col-md-6">
                                                             <p><strong>Submitted:</strong> {{ $report->created_at->format('d M Y, H:i') }}</p>
                                                         </div>
                                                         <div class="col-md-6">
                                                             <p><strong>Last Updated:</strong> {{ $report->updated_at->format('d M Y, H:i') }}</p>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="modal-footer">
                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                     <a href="{{ route('reports.export', $report->id) }}" 
                                        class="btn btn-primary export-btn" 
                                        data-report-id="{{ $report->id }}"
                                        title="Export this report as a PDF document"
                                        data-bs-toggle="tooltip">
                                         <i class="bi bi-download me-2"></i>Export Report
                                     </a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 @endforeach
             @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Reports Found</h4>
                    <p class="text-muted mb-4">You have not submitted any reports yet.</p>
                    <a href="{{ route('report') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Your First Report
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@if(config('app.debug') && $reports->count() == 0)
<div class="container py-3">
    <div class="alert alert-info">
        <h5><i class="bi bi-info-circle me-2"></i>Development Note</h5>
        <p class="mb-2">To see sample data, you can create test reports or add mock data to the database.</p>
        <small>This section only appears in debug mode when no reports exist.</small>
    </div>
</div>
@endif

@endsection

@section('scripts')
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>

$(document).ready(function() {
    $('#myReportsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        order: [[6, 'desc']], // Sort by submitted date (column 6) in descending order
        language: {
            search: "Search reports:",
            lengthMenu: "Show _MENU_ reports per page",
            info: "Showing _START_ to _END_ of _TOTAL_ reports",
            infoEmpty: "Showing 0 to 0 of 0 reports",
            infoFiltered: "(filtered from _MAX_ total reports)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 7], // All columns except date
                orderable: true,
                searchable: true
            },
            {
                targets: 6, // Date column
                orderable: true,
                searchable: false
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function() {
            // Add custom styling
            $('.dataTables_wrapper').addClass('mt-3');
            $('.dataTables_filter input').addClass('form-control-sm');
            $('.dataTables_length select').addClass('form-select-sm');
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    // Handle export button clicks
    $('.export-btn').on('click', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const originalText = $btn.html();
        const reportId = $btn.data('report-id');
        const exportUrl = $btn.attr('href');
        const $modal = $btn.closest('.modal');
        
        // Show loading state
        $btn.html('<i class="bi bi-hourglass-split me-2"></i>Generating PDF...');
        $btn.prop('disabled', true);
        
        // Make AJAX request to check if export is successful
        $.ajax({
            url: exportUrl,
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
                a.download = 'Child_Protection_Report_' + reportId.substring(0, 8) + '_' + new Date().toISOString().split('T')[0] + '.pdf';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                // Reset button and show success
                $btn.html(originalText);
                $btn.prop('disabled', false);
                
                // Show success alert within the modal
                showAlertInModal('PDF generated successfully!', 'success', $modal);
            },
            error: function(xhr, status, error) {
                // Reset button and show error
                $btn.html(originalText);
                $btn.prop('disabled', false);
                
                let errorMessage = 'Failed to generate PDF. Please try again.';
                if (xhr.status === 403) {
                    errorMessage = 'Access denied. You can only export your own reports.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Report not found.';
                }
                
                showAlertInModal(errorMessage, 'error', $modal);
            }
        });
    });
    
    // Alert function for modals
    function showAlertInModal(message, type = 'info', $modal) {
        let alertClass, icon;
        
        switch(type) {
            case 'success':
                alertClass = 'alert-success';
                icon = 'bi-check-circle';
                break;
            case 'error':
                alertClass = 'alert-danger';
                icon = 'bi-exclamation-triangle';
                break;
            default:
                alertClass = 'alert-info';
                icon = 'bi-info-circle';
        }
        
        // Create alert element
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove any existing alerts in this modal
        $modal.find('.alert').remove();
        
        // Insert alert at the top of the modal body
        $modal.find('.modal-body').prepend(alertHtml);
        
        // Auto-remove alert after 5 seconds
        setTimeout(function() {
            $modal.find('.alert').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>
@endsection
