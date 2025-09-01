<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Session Logs Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #495057;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 6px 8px;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Session Logs Report</h1>
        <p>SinDa System - Session Activity Export</p>
        <p>Generated on: {{ now()->format('F d, Y \a\t g:i A') }}</p>
        <p>Total Records: {{ count($sessionData) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>User</th>
                <th>Email</th>
                <th>IP Address</th>
                <th>Browser</th>
                <th>OS</th>
                <th>Session ID</th>
                <th>Last Activity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessionData as $session)
            <tr>
                <td>{{ $session['datetime'] }}</td>
                <td>{{ $session['user'] }}</td>
                <td>{{ $session['email'] }}</td>
                <td>{{ $session['ip_address'] }}</td>
                <td>{{ $session['browser'] }}</td>
                <td>{{ $session['os'] }}</td>
                <td style="font-family: monospace; font-size: 10px;">{{ $session['session_id'] }}</td>
                <td>{{ $session['last_activity'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                    No session data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the SinDa System</p>
        <p>For questions or support, please contact the system administrator</p>
    </div>
</body>
</html>
