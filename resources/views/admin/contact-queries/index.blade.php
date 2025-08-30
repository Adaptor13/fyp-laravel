@extends('layout.master')
@section('title', 'Contact Queries Management')
@section('css')
    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select/select2.min.css') }}">
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="main-title">Contact Queries Management</h4>
        </div>
        <div class="col-sm-6 mt-sm-2">
            <ul class="breadcrumb breadcrumb-start float-sm-end">
                <li class="d-flex">
                    <i class="ti ti-home f-s-16"></i>
                    <a href="{{ route('admin_index') }}" class="f-s-14 d-flex gap-2">
                        <span class="d-none d-md-block">Dashboard</span>
                    </a>
                </li>
                <li class="d-flex active">
                    <i class="ti ti-messages f-s-16 ms-2"></i>
                    <span class="f-s-14">Contact Queries</span>
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card bg-primary text-white">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h3 class="header-heading mb-0">{{ $stats['total'] }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">Total Queries</p>
                        </div>
                        <div>
                            <i class="ti ti-messages f-s-36"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card bg-warning text-white">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h3 class="header-heading mb-0">{{ $stats['pending'] }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">Pending</p>
                        </div>
                        <div>
                            <i class="ti ti-clock f-s-36"></i>
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
                            <h3 class="header-heading mb-0">{{ $stats['in_progress'] }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">In Progress</p>
                        </div>
                        <div>
                            <i class="ti ti-loader f-s-36"></i>
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
                            <h3 class="header-heading mb-0">{{ $stats['resolved'] }}</h3>
                            <p class="f-w-300 f-s-12 mb-0">Resolved</p>
                        </div>
                        <div>
                            <i class="ti ti-check f-s-36"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ti ti-filter"></i> Filters & Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateFromFilter" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="dateFromFilter">
                        </div>
                        <div class="col-md-3">
                            <label for="dateToFilter" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="dateToFilter">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="button" class="btn btn-primary" id="applyFilters">
                                    <i class="ti ti-search"></i> Apply Filters
                                </button>
                                <button type="button" class="btn btn-secondary" id="clearFilters">
                                    <i class="ti ti-x"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Queries Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Contact Queries</h5>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-auto data-table-style app-scroll">
                        <table id="contactQueriesTable" class="display app-data-table deafult-data-tabel">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>User</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this contact query?<br>
                <small class="text-secondary">Query: <span id="deleteQueryLabel">—</span></small>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="badge text-light-danger fs-6" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="deleteForm" method="POST" action="{{ route('admin.contact-queries.destroy', '__id__') }}" class="d-none">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('script')
<script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#contactQueriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.contact-queries.data") }}',
            data: function(d) {
                d.status = $('#statusFilter').val();
                d.date_from = $('#dateFromFilter').val();
                d.date_to = $('#dateToFilter').val();
            }
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'subject', name: 'subject'},
            {data: 'message', name: 'message'},
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    return '<span class="badge ' + data.class + '">' + data.text + '</span>';
                }
            },
            {data: 'user', name: 'user'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[6, 'desc']], // Sort by created_at descending
        responsive: true,
        pageLength: 25,
        language: {
            processing: '<i class="ti ti-loader ti-spin"></i> Loading...',
            emptyTable: 'No contact queries found',
            zeroRecords: 'No matching contact queries found'
        }
    });

    // Apply filters
    $('#applyFilters').on('click', function() {
        table.ajax.reload();
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#statusFilter').val('');
        $('#dateFromFilter').val('');
        $('#dateToFilter').val('');
        table.ajax.reload();
    });


    // Auto-remove session alerts after 4 seconds
    setTimeout(function() {
        $('.alert').fadeOut(function() {
            $(this).remove();
        });
    }, 4000);

    // Handle delete contact query
    let deleteId = null;
    const deleteModalEl = document.getElementById('deleteModal');
    const deleteModal = new bootstrap.Modal(deleteModalEl);
    const deleteQueryLabelEl = document.getElementById('deleteQueryLabel');

    // Open modal
    $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        const label = $(this).data('label') || `ID ${deleteId}`;
        deleteQueryLabelEl.textContent = label;
        deleteModal.show();
    });

    $('#confirmDeleteBtn').on('click', function() {
        if (!deleteId) return;
        const form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.contact-queries.destroy', '__id__') }}".replace('__id__', deleteId);
        form.submit();
    });
});
</script>
@endsection
