# Activity Logs Module - SinDa Laravel Project

## Overview

The Activity Logs module tracks user login and logout events in the SinDa system. It provides comprehensive logging with detailed information about each authentication event, including device information, IP addresses, and session data.

## Features

- **Login/Logout Tracking**: Automatically logs all successful login and logout events
- **Failed Login Attempts**: Tracks failed login attempts for security monitoring
- **Device Information**: Captures browser and operating system information
- **IP Address Tracking**: Records IP addresses for security auditing
- **Session Management**: Tracks session IDs and durations
- **Filtering & Search**: Advanced filtering by user, event type, and date range
- **Export Functionality**: Export logs to CSV format
- **Statistics Dashboard**: Real-time statistics and metrics
- **Permission-Based Access**: Role-based access control for viewing and exporting logs

## Database Structure

### Activity Logs Table (`activity_logs`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | UUID | Primary key |
| `user_id` | UUID | Foreign key to users table (nullable for failed logins) |
| `event_type` | String | Type of event ('login', 'logout', 'failed_login') |
| `description` | String | Human-readable description of the event |
| `ip_address` | String | IP address of the user |
| `user_agent` | String | Browser user agent string |
| `session_id` | String | Laravel session ID |
| `metadata` | JSON | Additional event data (browser info, timestamps, etc.) |
| `created_at` | Timestamp | Event timestamp |
| `updated_at` | Timestamp | Last update timestamp |

## Implementation Details

### Models

#### ActivityLog Model (`app/Models/ActivityLog.php`)
- Handles database interactions for activity logs
- Includes relationships to User model
- Provides scopes for filtering (by event type, user, date range)
- Contains helper methods for formatting display data
- Extracts browser and OS information from user agent strings

### Controllers

#### ActivityLogController (`app/Http/Controllers/ActivityLogController.php`)
- **index()**: Displays the activity logs dashboard
- **getData()**: Provides DataTables-compatible JSON data with filtering
- **getFilterOptions()**: Returns filter options for the frontend
- **exportCSV()**: Exports filtered logs to CSV format
- **getStatistics()**: Returns real-time statistics for the dashboard

### Traits

#### LogsUserActivity (`app/Traits/LogsUserActivity.php`)
- **logLogin()**: Logs successful login events
- **logLogout()**: Logs logout events with session duration
- **logFailedLogin()**: Logs failed login attempts
- **calculateSessionDuration()**: Calculates session duration for logout events

### Views

#### Activity Logs Index (`resources/views/admin/activity-logs/index.blade.php`)
- Modern, responsive dashboard interface
- Statistics cards showing key metrics
- Advanced filtering options
- DataTables integration for pagination and sorting
- Export functionality
- Real-time data loading

## Authentication Integration

The module integrates with the existing authentication system:

### AuthController Updates
- **Login Method**: Automatically logs successful logins
- **Logout Method**: Logs logout events before session destruction
- **Failed Login Handling**: Tracks failed login attempts

### Automatic Logging
All authentication events are automatically logged without requiring manual intervention:

```php
// Login logging (automatically called in AuthController)
self::logLogin(Auth::user(), $request);

// Logout logging (automatically called in AuthController)
self::logLogout($user, $request);

// Failed login logging (automatically called in AuthController)
self::logFailedLogin($request->email, $request);
```

## Permissions

The module uses Laravel's permission system:

- **activity_logs.view**: Required to view activity logs
- **activity_logs.export**: Required to export logs to CSV

### Role Assignments
- **Admin**: Full access to view and export activity logs
- **Other Roles**: No access to activity logs (for security)

## Usage

### Accessing Activity Logs
1. Navigate to the admin dashboard
2. Click on "Audit & Logs" in the sidebar
3. Select "Activity Logs"

### Filtering Logs
- **Event Type**: Filter by login, logout, or failed login events
- **User**: Filter by specific users
- **Date Range**: Filter by date from/to
- **Apply/Clear**: Use buttons to apply or clear filters

### Exporting Data
- Click "Export CSV" to download filtered logs
- Export includes all relevant information in CSV format
- Respects current filter settings

### Viewing Statistics
The dashboard displays real-time statistics:
- Total logs count
- Login events count
- Logout events count
- Today's events count

## Security Features

- **IP Address Tracking**: Records IP addresses for security auditing
- **Session Management**: Tracks session IDs and durations
- **Device Fingerprinting**: Captures browser and OS information
- **Failed Login Monitoring**: Tracks failed login attempts
- **Permission-Based Access**: Restricts access to authorized users only

## Technical Notes

### Database Indexes
The activity_logs table includes optimized indexes for:
- User ID and creation date
- Event type and creation date
- Creation date only

### Performance Considerations
- Uses DataTables server-side processing for large datasets
- Implements efficient filtering and pagination
- Optimized database queries with proper indexing

### Browser Detection
The module includes built-in browser and OS detection:
- Chrome, Firefox, Safari, Edge detection
- Windows, macOS, Linux, Android, iOS detection
- Version information extraction

## Future Enhancements

Potential improvements for the Activity Logs module:
- Geographic location tracking based on IP addresses
- Real-time notifications for suspicious activity
- Advanced analytics and reporting
- Integration with external security monitoring systems
- Automated cleanup of old log entries
- Enhanced export formats (PDF, Excel)

## Troubleshooting

### Common Issues

1. **No logs appearing**: Check if the user has proper permissions
2. **Export not working**: Verify the user has export permissions
3. **Performance issues**: Check database indexes and query optimization
4. **Missing user information**: Ensure user relationships are properly set up

### Debugging
- Check Laravel logs for any errors
- Verify database migrations have been run
- Ensure permissions are properly assigned to roles
- Test authentication flow to verify logging is working

## Dependencies

- Laravel 12
- DataTables (for frontend table functionality)
- UUID support for primary keys
- JSON column support for metadata storage
