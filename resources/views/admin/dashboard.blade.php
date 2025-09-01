@extends('layout.master')
@section('title', 'SinDa Dashboard')

@section('main-content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @php
                        $user = auth()->user();
                        $role = strtolower(optional($user->role)->name);
                        $roleDisplayNames = [
                            'admin' => 'Admin',
                            'gov_official' => 'Government Official',
                            'social_worker' => 'Social Worker',
                            'law_enforcement' => 'Law Enforcement',
                            'healthcare' => 'Healthcare Professional',
                            'public_user' => 'Public User'
                        ];
                        $roleDisplayName = $roleDisplayNames[$role] ?? 'User';
                    @endphp
                    <h1 class="h3 mb-0 text-gray-800">SinDa {{ $roleDisplayName }} Dashboard</h1>
                    <p class="text-muted mb-0">
                        @if($role === 'admin')
                            Real-time overview of system activity and trends
                        @elseif($role === 'gov_official')
                            Overview of all cases and system activity
                        @elseif(in_array($role, ['social_worker', 'law_enforcement', 'healthcare']))
                            Overview of your assigned cases and activities
                        @else
                            Overview of your reports and case status
                        @endif
                    </p>
                </div>
                <div class="text-end">
                    <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                    @permission('dashboard.export')
                    <div class="mt-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-download me-1"></i>Export Reports
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                <li><h6 class="dropdown-header">CSV Export</h6></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.cases.csv') }}">
                                    <i class="ti ti-file-text text-success me-2"></i>All Cases (CSV)
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.users.csv') }}">
                                    <i class="ti ti-users text-success me-2"></i>All Users (CSV)
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.contact-queries.csv') }}">
                                    <i class="ti ti-mail text-success me-2"></i>All Queries (CSV)
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.session-logs.csv') }}">
                                    <i class="ti ti-activity text-success me-2"></i>Session Logs (CSV)
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">PDF Export</h6></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.cases.pdf') }}">
                                    <i class="ti ti-file-text text-danger me-2"></i>All Cases (PDF)
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.users.pdf') }}">
                                    <i class="ti ti-users text-danger me-2"></i>All Users (PDF)
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.contact-queries.pdf') }}">
                                    <i class="ti ti-mail text-danger me-2"></i>All Queries (PDF)
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard.export.session-logs.pdf') }}">
                                    <i class="ti ti-activity text-danger me-2"></i>Session Logs (PDF)
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    @endpermission
                </div>
            </div>
        </div>
    </div>

    <!-- High-Level Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card hover-effect h-100" style="border-left: 4px solid #007bff;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                @if($role === 'admin' || $role === 'gov_official')
                                    Total Reports
                                @elseif(in_array($role, ['social_worker', 'law_enforcement', 'healthcare']))
                                    My Cases
                                @else
                                    My Reports
                                @endif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_reports']) }}</div>
                            <div class="text-success f-s-14 f-w-500">
                                <i class="ti ti-trending-up"></i> +{{ $recentStats['new_reports'] }} this month
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-primary h-50 w-50 d-flex-center b-r-50">
                                <i class="ti ti-file-text f-s-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card hover-effect h-100" style="border-left: 4px solid #28a745;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                @if($role === 'admin' || $role === 'gov_official')
                                    Active Cases
                                @elseif(in_array($role, ['social_worker', 'law_enforcement', 'healthcare']))
                                    Active Assignments
                                @else
                                    Active Reports
                                @endif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_cases']) }}</div>
                            <div class="text-success f-s-14 f-w-500">
                                <i class="ti ti-trending-up"></i> {{ $assignmentStats['total_assignments'] }} assigned
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-success h-50 w-50 d-flex-center b-r-50">
                                <i class="ti ti-tasks f-s-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($role === 'admin')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card hover-effect h-100" style="border-left: 4px solid #17a2b8;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_users']) }}</div>
                            <div class="text-success f-s-14 f-w-500">
                                <i class="ti ti-trending-up"></i> +{{ $recentStats['new_users'] }} this month
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-info h-50 w-50 d-flex-center b-r-50">
                                <i class="ti ti-users f-s-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card hover-effect h-100" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Contact Queries
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_contact_queries']) }}</div>
                            <div class="text-success f-s-14 f-w-500">
                                <i class="ti ti-trending-up"></i> +{{ $recentStats['new_queries'] }} this month
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-warning h-50 w-50 d-flex-center b-r-50">
                                <i class="ti ti-mail f-s-24 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if($role === 'admin')
    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Monthly Trends Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Activity Trends</h6>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px;">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px;">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row mb-4">
        <!-- User Role Distribution -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Role Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px;">
                        <canvas id="roleDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @else
    <!-- Status Distribution Chart for Non-Admin Users -->
    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @if(in_array($role, ['social_worker', 'law_enforcement', 'healthcare']))
                            Case Status Distribution
                        @else
                            Report Status Distribution
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px;">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

        <!-- Recent Activity -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @if($role === 'admin' || $role === 'gov_official')
                            Recent Reports
                        @elseif(in_array($role, ['social_worker', 'law_enforcement', 'healthcare']))
                            Recent Cases
                        @else
                            Recent Reports
                        @endif
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($recentReports as $report)
                    <div class="d-flex justify-content-between align-items-start p-3 border-bottom">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $report->reporter_name }}</h6>
                            <p class="text-muted mb-1 small">{{ Str::limit($report->incident_description, 80) }}</p>
                            <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="ms-3">
                            @php
                                $statusClass = '';
                                switch($report->report_status) {
                                    case 'Submitted': $statusClass = 'bg-info'; break;
                                    case 'Under Review': $statusClass = 'bg-warning'; break;
                                    case 'In Progress': $statusClass = 'bg-primary'; break;
                                    case 'Resolved': $statusClass = 'bg-success'; break;
                                    case 'Closed': $statusClass = 'bg-secondary'; break;
                                    default: $statusClass = 'bg-secondary';
                                }
                            @endphp
                            <span class="badge {{ $statusClass }} f-s-12 f-w-500">
                                {{ ucfirst($report->report_status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-inbox f-s-48 mb-3"></i>
                        <p>No recent reports</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


</div>


@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Chart (for all users)
    const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(statusDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($statusDistribution)),
            datasets: [{
                data: @json(array_values($statusDistribution)),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    @if($role === 'admin')
    // Monthly Trends Chart (admin only)
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    new Chart(monthlyTrendsCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($monthlyTrends, 'month')),
            datasets: [{
                label: 'Reports',
                data: @json(array_column($monthlyTrends, 'reports')),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }, {
                label: 'Users',
                data: @json(array_column($monthlyTrends, 'users')),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Role Distribution Chart (admin only)
    const roleDistributionCtx = document.getElementById('roleDistributionChart').getContext('2d');
    new Chart(roleDistributionCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($roleDistribution)),
            datasets: [{
                label: 'Users',
                data: @json(array_values($roleDistribution)),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif

    // Export functionality
    @permission('dashboard.export')
    $(document).ready(function() {
        // Handle export dropdown clicks
        $('.dropdown-item').on('click', function(e) {
            e.preventDefault();
            
            const $link = $(this);
            const $icon = $link.find('i');
            const originalIcon = $icon.attr('class');
            
            // Show loading state
            $icon.removeClass().addClass('ti ti-loader ti-spin me-2');
            $link.addClass('disabled');
            
            // Get the export URL
            const exportUrl = $link.attr('href');
            
            // Create a temporary form to handle the download
            const $form = $('<form>', {
                'method': 'GET',
                'action': exportUrl
            });
            
            $('body').append($form);
            $form.submit();
            $form.remove();
            
            // Reset the button after a short delay
            setTimeout(function() {
                $icon.removeClass().addClass(originalIcon);
                $link.removeClass('disabled');
            }, 2000);
        });


    });
    @endpermission
});
</script>
@endsection
