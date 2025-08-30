<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users Export - {{ date('Y-m-d H:i:s') }}</title>
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
        .role-admin { background-color: #ffebee; }
        .role-gov_official { background-color: #e3f2fd; }
        .role-social_worker { background-color: #e8f5e8; }
        .role-law_enforcement { background-color: #fff3e0; }
        .role-healthcare { background-color: #f3e5f5; }
        .role-public_user { background-color: #f5f5f5; }
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
        .verified {
            color: #4caf50;
            font-weight: bold;
        }
        .unverified {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SinDa Users Export Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        <p>Total Users: {{ $users->count() }}</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Users:</strong> {{ $users->count() }}</p>
        <p><strong>Email Verified:</strong> {{ $users->where('email_verified_at', '!=', null)->count() }}</p>
        <p><strong>Email Unverified:</strong> {{ $users->where('email_verified_at', null)->count() }}</p>
        @foreach($users->groupBy('role.name') as $roleName => $roleUsers)
        <p><strong>{{ ucfirst(str_replace('_', ' ', $roleName)) }}:</strong> {{ $roleUsers->count() }}</p>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Email Status</th>
                <th>Created</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="role-{{ strtolower(str_replace(' ', '_', $user->role ? $user->role->name : 'no_role')) }}">
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role ? ucfirst(str_replace('_', ' ', $user->role->name)) : 'No Role' }}</td>
                <td>{{ optional($user->profile)->phone ?? 'N/A' }}</td>
                <td>{{ optional($user->profile)->address_line1 ?? 'N/A' }}</td>
                <td>{{ optional($user->profile)->city ?? 'N/A' }}</td>
                <td>{{ optional($user->profile)->state ?? 'N/A' }}</td>
                <td class="{{ $user->email_verified_at ? 'verified' : 'unverified' }}">
                    {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                </td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d') : 'Never' }}</td>
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
