@extends('layout.master')
@section('title', 'Contact Query Details')
@section('css')
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="main-title">Contact Query Details</h4>
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
                    <i class="ti ti-messages f-s-16 ms-2"></i>
                    <a href="{{ route('admin.contact-queries.index') }}" class="f-s-14 d-flex gap-2">
                        <span class="d-none d-md-block">Contact Queries</span>
                    </a>
                </li>
                <li class="d-flex active">
                    <i class="ti ti-eye f-s-16 ms-2"></i>
                    <span class="f-s-14">Query Details</span>
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
                    <h5 class="mb-0">Query #{{ $contactQuery->id }}</h5>
                    <div>
                        <a href="{{ route('admin.contact-queries.index') }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Back to Queries">
                            <i class="ti ti-arrow-left"></i>
                        </a>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Query Information -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="ti ti-message-circle"></i> Query Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Name:</strong> {{ $contactQuery->name }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Email:</strong> {{ $contactQuery->email }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Subject:</strong> {{ $contactQuery->subject }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Status:</strong> 
                                            <span class="badge {{ $contactQuery->status_badge_class }}">
                                                {{ $contactQuery->status_text }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Submitted:</strong> {{ $contactQuery->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <strong>Message:</strong>
                                            <div class="border rounded p-3 bg-light mt-2">
                                                {{ $contactQuery->message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Information -->
                        <div class="col-md-4">
                            <!-- User Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="ti ti-user"></i> User Information</h6>
                                </div>
                                <div class="card-body">
                                    @if($contactQuery->user)
                                        <p><strong>User:</strong> {{ $contactQuery->user->name }}</p>
                                        <p><strong>Email:</strong> {{ $contactQuery->user->email }}</p>
                                        <p><strong>Role:</strong> {{ optional($contactQuery->user->role)->name ?? 'N/A' }}</p>
                                        <p><strong>Member Since:</strong> {{ $contactQuery->user->created_at->format('M d, Y') }}</p>
                                    @else
                                        <p class="text-muted">Anonymous user</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Status Change Buttons -->
                            @if(auth()->user()->hasPermission('contact_queries.edit'))
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="ti ti-settings"></i> Change Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-warning btn-sm" onclick="updateStatus('pending')">
                                            <i class="ti ti-clock"></i> Mark as Pending
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" onclick="updateStatus('in_progress')">
                                            <i class="ti ti-loader"></i> Mark as In Progress
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" onclick="updateStatus('resolved')">
                                            <i class="ti ti-check"></i> Mark as Resolved
                                        </button>
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
</div>
@endsection

@section('script')
<script>
function updateStatus(status) {
    $.ajax({
        url: '{{ route("admin.contact-queries.update-status", $contactQuery->id) }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: status
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Failed to update status: ' + response.message);
            }
        },
        error: function() {
            alert('Failed to update status. Please try again.');
        }
    });
}

// Auto-remove session alerts after 4 seconds
setTimeout(function() {
    $('.alert').fadeOut(function() {
        $(this).remove();
    });
}, 4000);
</script>
@endsection
