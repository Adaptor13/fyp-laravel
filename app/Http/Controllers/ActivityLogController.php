<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ActivityLogController extends Controller
{
    /**
     * Display the activity logs index page
     */
    public function index()
    {
        return view('admin.activity-logs.index');
    }

    /**
     * Get activity logs data for DataTables
     */
    public function getData(Request $request)
    {

     
        
        $query = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select([
                'sessions.id as session_id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'users.name',
                'users.email'
            ]);

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('sessions.user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->where('sessions.last_activity', '>=', strtotime($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->where('sessions.last_activity', '<=', strtotime($request->date_to . ' 23:59:59'));
        }

        // Get pagination parameters
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        
        // Get total count before pagination
        $totalRecords = $query->count();
        
        // Apply search if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('sessions.ip_address', 'like', "%{$search}%");
            });
        }
        
        // Get filtered count
        $filteredRecords = $query->count();
        
        // Apply pagination
        $sessions = $query->orderBy('sessions.last_activity', 'desc')
                         ->skip($start)
                         ->take($length)
                         ->get();
        
        // Transform data for DataTable
        $data = [];
        foreach ($sessions as $session) {
            $data[] = [
                'user_info' => $this->formatUserInfo($session),
                'event_type_badge' => '<span class="badge bg-success">Active Session</span>',
                'device_info' => $this->formatDeviceInfo($session),
                'location_info' => $this->formatLocationInfo($session),
                'formatted_time' => date('M d, Y g:i A', $session->last_activity),
                'session_duration' => $this->formatDuration(time() - $session->last_activity)
            ];
        }
        
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get filter options for the activity logs
     */
    public function getFilterOptions()
    {
        $users = User::select('id', 'name', 'email')->get();

        return response()->json([
            'users' => $users
        ]);
    }



    /**
     * Format user information for display
     */
    private function formatUserInfo($session)
    {
        if (!$session->user_id) {
            return '<span class="text-muted">Guest User</span>';
        }
        
        return '
            <div>
                <h6 class="mb-0">' . htmlspecialchars($session->name) . '</h6>
                <p class="text-secondary mb-0">' . htmlspecialchars($session->email) . '</p>
            </div>
        ';
    }

    /**
     * Format device information for display
     */
    private function formatDeviceInfo($session)
    {
        $browser = $this->getBrowserInfo($session->user_agent);
        $os = $this->getOperatingSystem($session->user_agent);
        
        return '
            <div>
                <small class="text-muted">' . htmlspecialchars($browser) . '</small><br>
                <small class="text-muted">' . htmlspecialchars($os) . '</small>
            </div>
        ';
    }

    /**
     * Get browser information from user agent
     */
    private function getBrowserInfo($userAgent)
    {
        if (!$userAgent) {
            return 'Unknown';
        }

        $browser = 'Unknown';
        
        if (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/MSIE|Trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        }

        return $browser;
    }

    /**
     * Get operating system information from user agent
     */
    private function getOperatingSystem($userAgent)
    {
        if (!$userAgent) {
            return 'Unknown';
        }

        $os = 'Unknown';
        
        if (preg_match('/Windows/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iOS/i', $userAgent)) {
            $os = 'iOS';
        }

        return $os;
    }

    /**
     * Format location information for display
     */
    private function formatLocationInfo($session)
    {
        $info = [];
        if ($session->ip_address) {
            $info[] = '<small class="text-muted">IP: ' . htmlspecialchars($session->ip_address) . '</small>';
        }
        if ($session->session_id) {
            $info[] = '<small class="text-muted">Session: ' . substr(htmlspecialchars($session->session_id), 0, 8) . '...</small>';
        }
        return implode('<br>', $info);
    }

    /**
     * Format duration in human readable format
     */
    private function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' seconds ago';
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . ' minutes ago';
        } elseif ($seconds < 86400) {
            return floor($seconds / 3600) . ' hours ago';
        } else {
            return floor($seconds / 86400) . ' days ago';
        }
    }
}
