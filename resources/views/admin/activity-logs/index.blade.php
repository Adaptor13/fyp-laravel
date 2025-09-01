@extends('layout.master')
@section('title', 'Session Logs')
@section('css')
    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select/select2.min.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="main-title">Session Logs</h4>
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
                        <i class="ti ti-clipboard-list f-s-16 ms-2"></i>
                        <a href="#" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Audit & Logs</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-activity f-s-16 ms-2"></i>
                        <span class="f-s-14">Session Logs</span>
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> Please check the form below for errors.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Session Logs</h5>
                        <div>
                            <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#filterSection">
                                <i class="ti ti-filter"></i> Filters
                            </button>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="collapse" id="filterSection">
                        <div class="card-body border-bottom">
                                                         <div class="row">
                                 <div class="col-md-4">
                                     <label for="userFilter" class="form-label">User</label>
                                     <select class="form-select" id="userFilter">
                                         <option value="">All Users</option>
                                     </select>
                                 </div>
                                 <div class="col-md-4">
                                     <label for="dateFromFilter" class="form-label">Date From</label>
                                     <input type="date" class="form-control" id="dateFromFilter">
                                 </div>
                                 <div class="col-md-4">
                                     <label for="dateToFilter" class="form-label">Date To</label>
                                     <input type="date" class="form-control" id="dateToFilter">
                                 </div>
                             </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="btn btn-primary me-2" id="applyFilters">
                                        <i class="ti ti-search"></i> Apply Filters
                                    </button>
                                    <button class="btn btn-secondary" id="clearFilters">
                                        <i class="ti ti-refresh"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="overflow-auto data-table-style app-scroll">
                            <table id="activityLogsTable" class="display app-data-table deafult-data-tabel">
                                                                 <thead>
                                     <tr>
                                         <th>User</th>
                                         <th>Status</th>
                                         <th>Device Info</th>
                                         <th>Location</th>
                                         <th>Last Activity</th>
                                         <th>Duration</th>
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
@endsection

@section('script')
    <!-- Data Table js -->
    <script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#activityLogsTable').DataTable({
                processing: true,
                serverSide: true,
                                 ajax: {
                     url: '{{ route("admin.activity-logs.data") }}',
                     data: function(d) {
                         d.user_id = $('#userFilter').val();
                         d.date_from = $('#dateFromFilter').val();
                         d.date_to = $('#dateToFilter').val();
                     }
                 },
                                 columns: [
                     {data: 'user_info', name: 'user_info'},
                     {data: 'event_type_badge', name: 'event_type_badge'},
                     {data: 'device_info', name: 'device_info'},
                     {data: 'location_info', name: 'location_info'},
                     {data: 'formatted_time', name: 'formatted_time'},
                     {data: 'session_duration', name: 'session_duration'}
                 ],
                order: [[5, 'desc']], // Sort by date/time descending
                responsive: true,
                pageLength: 25,
                language: {
                    processing: '<i class="ti ti-loader ti-spin"></i> Loading...',
                    emptyTable: 'No activity logs found',
                    zeroRecords: 'No matching activity logs found'
                }
            });

            // Load user filter options
            loadUserFilterOptions();

            // Apply filters
            $('#applyFilters').on('click', function() {
                table.ajax.reload();
            });

                         // Clear filters
             $('#clearFilters').on('click', function() {
                 $('#userFilter').val('');
                 $('#dateFromFilter').val('');
                 $('#dateToFilter').val('');
                 table.ajax.reload();
             });

            // Auto-remove session alerts after 4 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 4000);
        });

        function loadUserFilterOptions() {
            $.ajax({
                url: '{{ route("admin.activity-logs.filter-options") }}',
                type: 'GET',
                success: function(data) {
                    var userSelect = $('#userFilter');
                    data.users.forEach(function(user) {
                        userSelect.append(new Option(user.name + ' (' + user.email + ')', user.id));
                    });
                },
                error: function() {
                    console.error('Failed to load filter options');
                }
            });
        }
    </script>
@endsection
