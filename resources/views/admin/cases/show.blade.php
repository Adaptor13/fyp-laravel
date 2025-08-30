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
                        <h5 class="mb-0">Case #{{ $report->id }}</h5>
                        <div>
                            <a href="{{ route('cases.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Back to Cases">
                                <i class="ti ti-arrow-left"></i>
                            </a>
                            <button class="btn btn-info" id="exportBtn" data-case-id="{{ $report->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Export PDF">
                                <i class="ti ti-download"></i>
                            </button>
                            <a href="#messagesPanel" class="btn btn-info" onclick="scrollToMessages()" data-bs-toggle="tooltip" data-bs-placement="top" title="Messages">
                                <i class="ti ti-messages"></i>
                                <span class="badge bg-light text-dark" id="messagesCount">{{ $assignees->count() > 0 ? '0' : '—' }}</span>
                            </a>
                            @permission('cases.edit')
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editCase" 
                                    onclick="loadEditForm('{{ $report->id }}')" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Case">
                                <i class="ti ti-edit"></i>
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
                                        @if(!empty($report->abuse_types))
                                            <p><strong>Abuse Types:</strong></p>
                                            <div class="mb-2">
                                                @foreach($report->abuse_types as $abuseType)
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
                                                                        @if($assignee->getAvatarUrl())
                                                                            <img src="{{ $assignee->getAvatarUrl() }}" alt="{{ $assignee->name }}" 
                                                                                class="avatar avatar-sm rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                                        @else
                                                                            <div class="avatar avatar-sm rounded-circle" style="width: 40px; height: 40px; {{ $assignee->getAvatarBackgroundStyle() }}">
                                                                                <span class="avatar-text text-white fw-bold">{{ $assignee->getInitials() }}</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h6 class="mb-1">
                                                                            {{ $assignee->name }}
                                                                        </h6>
                                                                        <p class="text-muted mb-1">
                                                                            @php
                                                                                $roleName = optional($assignee->role)->name;
                                                                                $roleClass = match(strtolower($roleName)) {
                                                                                    'law_enforcement' => 'bg-danger',
                                                                                    'healthcare' => 'bg-success',
                                                                                    'social_worker' => 'bg-info',
                                                                                    'gov_official' => 'bg-warning',
                                                                                    'admin' => 'bg-dark',
                                                                                    default => 'bg-secondary'
                                                                                };
                                                                                $roleDisplay = match(strtolower($roleName)) {
                                                                                    'law_enforcement' => 'Law Enforcement',
                                                                                    'healthcare' => 'Healthcare',
                                                                                    'social_worker' => 'Social Worker',
                                                                                    'gov_official' => 'Government Official',
                                                                                    'admin' => 'Administrator',
                                                                                    default => ucfirst(str_replace('_', ' ', $roleName))
                                                                                };
                                                                            @endphp
                                                                            <span class="badge {{ $roleClass }} text-white">
                                                                                <i class="ti ti-shield me-1"></i>{{ $roleDisplay }}
                                                                            </span>
                                                                        </p>
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
                            @if(!empty($report->evidence))
                                <div class="col-md-12 mt-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="ti ti-paperclip"></i> Evidence Files</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($report->evidence as $index => $file)
                                                    @php
                                                        $filename = basename($file);
                                                        $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                                        $iconClass = match(strtolower($extension)) {
                                                            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'ti ti-photo',
                                                            'mp4', 'avi', 'mov', 'wmv' => 'ti ti-video',
                                                            'pdf' => 'ti ti-file-text',
                                                            default => 'ti ti-file'
                                                        };
                                                    @endphp
                                                    <button type="button" class="btn btn-outline-primary btn-sm evidence-viewer-btn" 
                                                            data-file="{{ $file }}" data-filename="{{ $filename }}"
                                                            title="{{ $filename }}">
                                                        <span class="evidence-number me-1">{{ $index + 1 }}.</span>
                                                        <i class="{{ $iconClass }}"></i>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Messages Panel -->
                            <div class="col-md-12 mt-4" id="messagesPanel">
                                @include('admin.cases._messages')
                            </div>
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

// Handle export button clicks
$(document).on('click', '#exportBtn', function() {
    const caseId = $(this).data('case-id');
    
    // Show loading state
    const $btn = $(this);
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
            
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            $('.alert').remove();
            
            $('.container-fluid').prepend(alertHtml);
            
            setTimeout(() => {
                $('.alert').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
    });
});

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

// Auto-remove session alerts after 4 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert) alert.remove();
    });
}, 4000);

// Scroll to messages panel
function scrollToMessages() {
    document.getElementById('messagesPanel').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

// Initialize tooltips
$(document).ready(function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    @if($assignees->count() > 0)
    // Messaging functionality - only initialize if case has assignees
    const caseId = '{{ $report->id }}';
    let canPost = true;
    let allMessages = []; // Store all messages for search functionality

    // Load messages
    function loadMessages() {
        $.get(`/cases/${caseId}/messages`, function(data) {
            canPost = data.can_post;
            allMessages = data.messages; // Store all messages
            renderMessages(allMessages);
            updateComposeForm();
            updateMessageCount(allMessages.length);
            
            // Update message count badge based on assignees
            if (!data.has_assignees) {
                $('#messagesCount').text('—');
            }
        }).fail(function() {
            $('#messagesThread').html(`
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle"></i> Failed to load messages
                </div>
            `);
        });
    }

    // Update message count badge
    function updateMessageCount(count) {
        $('#messagesCount').text(count);
    }

    // Render messages
    function renderMessages(messages) {
        if (messages.length === 0) {
            $('#messagesThread').html(`
                <div class="text-center text-muted">
                    <i class="ti ti-messages-off fs-1"></i>
                    <p class="mt-2">No messages yet</p>
                    <small>Start the conversation by sending the first message</small>
                </div>
            `);
            return;
        }

        // Reverse the messages array to show oldest first (chronological order)
        const chronologicalMessages = messages.slice().reverse();

        const messagesHtml = chronologicalMessages.map(message => {
            const isOwnMessage = message.sender_id === '{{ auth()->id() }}';
            const messageClass = isOwnMessage ? 'text-end' : 'text-start';
            const bubbleClass = isOwnMessage ? 'bg-primary text-white' : 'bg-light';
            
            return `
                <div class="mb-3 ${messageClass}">
                    <div class="d-inline-block ${bubbleClass} rounded p-3" style="max-width: 70%;">
                        <div class="message-body">${escapeHtml(message.body)}</div>
                        <div class="message-meta mt-2">
                            <small class="${isOwnMessage ? 'text-white-50' : 'text-muted'}">
                                <strong>${escapeHtml(message.sender.name)}</strong> • 
                                ${new Date(message.created_at).toLocaleString()}
                            </small>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        $('#messagesThread').html(messagesHtml);
        $('#messagesThread').scrollTop($('#messagesThread')[0].scrollHeight);
    }

    // Update compose form based on case status and assignees
    function updateComposeForm() {
        if (!canPost) {
            $('#messageBody').prop('disabled', true).attr('placeholder', 'Messaging is disabled for closed cases');
            $('#sendMessageBtn').prop('disabled', true).html('<i class="ti ti-lock"></i> Case Closed');
            $('#composeMessage').prepend(`
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle"></i> This case is closed. You can view message history but cannot send new messages.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        }
    }

    // Handle message form submission
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        
        const messageBody = $('#messageBody').val().trim();
        if (!messageBody) return;

        const $btn = $('#sendMessageBtn');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i> Sending...');

        $.ajax({
            url: `/cases/${caseId}/messages`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                body: messageBody
            },
            success: function(response) {
                $('#messageBody').val('');
                $('#charCount').text('0');
                loadMessages(); // Reload messages to show the new one
            },
            error: function(xhr) {
                let errorMessage = 'Failed to send message';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle"></i> ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $('.container-fluid').prepend(alertHtml);
                
                setTimeout(() => {
                    $('.alert').fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Character count
    $('#messageBody').on('input', function() {
        const count = $(this).val().length;
        $('#charCount').text(count);
        
        if (count > 4500) {
            $('#charCount').addClass('text-warning');
        } else {
            $('#charCount').removeClass('text-warning');
        }
    });

    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Search functionality
    $('#messageSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        
        if (searchTerm === '') {
            // Show all messages
            renderMessages(allMessages);
            $('#clearSearch').hide();
        } else {
            // Filter messages
            const filteredMessages = allMessages.filter(message => {
                const messageText = message.body.toLowerCase();
                const senderName = message.sender.name.toLowerCase();
                return messageText.includes(searchTerm) || senderName.includes(searchTerm);
            });
            
            renderMessages(filteredMessages);
            $('#clearSearch').show();
        }
    });

    // Clear search
    $('#clearSearch').on('click', function() {
        $('#messageSearch').val('');
        renderMessages(allMessages);
        $(this).hide();
    });

    // Load messages on page load
    loadMessages();

    // Auto-refresh messages every 30 seconds
    setInterval(loadMessages, 30000);
    @endif
});

</script>

<style>
.evidence-viewer-btn {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    border-radius: 20px;
    transition: all 0.2s ease;
}

.evidence-viewer-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.evidence-filename {
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
}

.evidence-number {
    font-weight: bold;
    color: #6c757d;
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .evidence-viewer-btn {
        max-width: 140px;
        font-size: 0.75rem;
    }
    
    .evidence-filename {
        max-width: 80px;
    }
    
    .evidence-number {
        font-size: 0.7rem;
    }
}
</style>
@endsection

