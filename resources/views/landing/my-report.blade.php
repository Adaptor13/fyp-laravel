@extends('layout.landing')
@section('title', 'My Report')
@section('content')

<div class="container py-5">
    <h2 class="mb-4">My Reports</h2>

    <div class="table-responsive">
        <table id="myReportsTable" class="table table-striped align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Incident Date</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <div id="emptyState" class="text-center py-5 d-none">
        <p class="mb-3">You haven’t submitted any reports yet.</p>
        <a href="{{ route('report') }}" class="btn btn-primary">Create a Report</a>
    </div>
</div>

@endsection