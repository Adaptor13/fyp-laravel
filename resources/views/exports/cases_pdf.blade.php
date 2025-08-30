<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cases Export - {{ date('Y-m-d H:i:s') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status-submitted { background-color: #e3f2fd; }
        .status-under-review { background-color: #fff3e0; }
        .status-in-progress { background-color: #e8f5e8; }
        .status-resolved { background-color: #f3e5f5; }
        .status-closed { background-color: #f5f5f5; }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .summary p {
            margin: 5px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SinDa Cases Export Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        <p>Total Cases: {{ $reports->count() }}</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Cases:</strong> {{ $reports->count() }}</p>
        <p><strong>Submitted:</strong> {{ $reports->where('report_status', 'Submitted')->count() }}</p>
        <p><strong>Under Review:</strong> {{ $reports->where('report_status', 'Under Review')->count() }}</p>
        <p><strong>In Progress:</strong> {{ $reports->where('report_status', 'In Progress')->count() }}</p>
        <p><strong>Resolved:</strong> {{ $reports->where('report_status', 'Resolved')->count() }}</p>
        <p><strong>Closed:</strong> {{ $reports->where('report_status', 'Closed')->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reporter</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Location</th>
                <th>Abuse Types</th>
                <th>Assigned To</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr class="status-{{ strtolower(str_replace(' ', '-', $report->report_status)) }}">
                <td>{{ $report->id }}</td>
                <td>{{ $report->reporter_name }}</td>
                <td>{{ $report->reporter_email }}</td>
                <td>{{ $report->reporter_phone }}</td>
                <td>{{ $report->report_status }}</td>
                <td>{{ $report->priority_level }}</td>
                <td>{{ $report->incident_location }}</td>
                <td>
                    @if(is_array($report->abuse_types))
                        {{ implode(', ', $report->abuse_types) }}
                    @else
                        {{ $report->abuse_types }}
                    @endif
                </td>
                <td>
                    @if($report->assignees->count() > 0)
                        @foreach($report->assignees as $assignee)
                            {{ $assignee->name }} ({{ $assignee->role->name }})<br>
                        @endforeach
                    @else
                        Unassigned
                    @endif
                </td>
                <td>{{ $report->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the SinDa system.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
