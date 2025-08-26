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
                <h4 class="main-title">Manage Admin</h4>
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
                        <i class="ti ti-users f-s-16 ms-2"></i>
                        <a href="#" class="f-s-14 d-flex gap-2">
                            <span class="d-none d-md-block">Users</span>
                        </a>
                    </li>
                    <li class="d-flex active">
                        <i class="ti ti-user f-s-16 ms-2"></i>
                        <span class="f-s-14">Admin</span>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                                <h3 class="header-heading mb-0">1</h3>
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
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdmin">
                            Add
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="overflow-auto data-table-style app-scroll">
                            <table id="casesTable" class="display app-data-table deafult-data-tabel">
                                <thead>
                                    <tr>
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
                        if (d === 'Open') cls = 'success';
                        else if (d === 'Closed') cls = 'danger';
                        else if (d === 'Pending') cls = 'warning';
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
            order: [[4, 'desc']], // sort by Updated (index 4)
            responsive: true
        });
    });
</script>


@endsection
