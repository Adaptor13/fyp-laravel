<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact Queries Export - {{ date('Y-m-d H:i:s') }}</title>
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
        .status-pending { background-color: #fff3e0; }
        .status-in_progress { background-color: #e3f2fd; }
        .status-resolved { background-color: #e8f5e8; }
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
        .message-cell {
            max-width: 200px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SinDa Contact Queries Export Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        <p>Total Queries: {{ $queries->count() }}</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Queries:</strong> {{ $queries->count() }}</p>
        <p><strong>Pending:</strong> {{ $queries->where('status', 'pending')->count() }}</p>
        <p><strong>In Progress:</strong> {{ $queries->where('status', 'in_progress')->count() }}</p>
        <p><strong>Resolved:</strong> {{ $queries->where('status', 'resolved')->count() }}</p>
        <p><strong>This Month:</strong> {{ $queries->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
        <p><strong>This Week:</strong> {{ $queries->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>User</th>
                <th>Created</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queries as $query)
            <tr class="status-{{ $query->status }}">
                <td>{{ $query->id }}</td>
                <td>{{ $query->name }}</td>
                <td>{{ $query->email }}</td>
                <td>{{ $query->subject }}</td>
                <td class="message-cell">{{ Str::limit($query->message, 100) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $query->status)) }}</td>
                <td>{{ $query->user ? $query->user->name : 'Anonymous' }}</td>
                <td>{{ $query->created_at->format('Y-m-d') }}</td>
                <td>{{ $query->updated_at->format('Y-m-d') }}</td>
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
