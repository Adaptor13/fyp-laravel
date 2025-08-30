<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Models\ContactQuery;
use App\Models\CaseAssignment;
use App\Models\Message;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);
        
        // Get current date and last 30 days
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);
        
        // Base query for reports based on role
        $reportQuery = Report::query();
        
        // Filter reports based on user role
        if ($role !== 'admin') {
            if (in_array($role, ['social_worker', 'law_enforcement', 'healthcare'])) {
                // These roles see only cases assigned to them
                $reportQuery->assignedTo($user->id);
            } elseif ($role === 'gov_official') {
                // Government officials can see all cases (like admin)
                // No additional filtering needed
            } else {
                // Public users see only their own reports
                $reportQuery->where('user_id', $user->id);
            }
        }
        
        // High-level statistics
        $stats = [
            'total_reports' => (clone $reportQuery)->count(),
            'total_users' => User::count(),
            'total_contact_queries' => ContactQuery::count(),
            'active_cases' => (clone $reportQuery)->whereIn('report_status', ['Submitted', 'Under Review', 'In Progress'])->count(),
        ];
        
        // Recent activity (last 30 days)
        $recentStats = [
            'new_reports' => (clone $reportQuery)->where('created_at', '>=', $thirtyDaysAgo)->count(),
            'new_users' => User::where('created_at', '>=', $thirtyDaysAgo)->count(),
            'new_queries' => ContactQuery::where('created_at', '>=', $thirtyDaysAgo)->count(),
            'resolved_cases' => (clone $reportQuery)->where('report_status', 'Resolved')
                ->where('updated_at', '>=', $thirtyDaysAgo)->count(),
        ];
        
        // Monthly trends for the last 6 months
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthlyTrends[] = [
                'month' => $month->format('M Y'),
                'reports' => (clone $reportQuery)->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
                'users' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
            ];
        }
        
        // Report status distribution
        $statusDistribution = (clone $reportQuery)->select('report_status', DB::raw('count(*) as count'))
            ->groupBy('report_status')
            ->get()
            ->pluck('count', 'report_status')
            ->toArray();
        
        // User role distribution (admin only or all users)
        $roleDistributionQuery = User::join('roles', 'users.role_id', '=', 'roles.id');
        if ($role !== 'admin') {
            // Non-admin users see limited role distribution
            $roleDistributionQuery->whereIn('roles.name', ['admin', $user->role->name]);
        }
        $roleDistribution = $roleDistributionQuery->select('roles.name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->get()
            ->pluck('count', 'name')
            ->toArray();
        
        // Recent reports for quick overview
        $recentReports = (clone $reportQuery)->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Case assignment statistics
        $assignmentQuery = CaseAssignment::whereNull('unassigned_at');
        if ($role !== 'admin' && $role !== 'gov_official') {
            if (in_array($role, ['social_worker', 'law_enforcement', 'healthcare'])) {
                $assignmentQuery->where('user_id', $user->id);
            } else {
                // Public users don't see assignment stats
                $assignmentQuery->whereRaw('1=0');
            }
        }
        
        $assignmentStats = [
            'total_assignments' => (clone $assignmentQuery)->count(),
            'primary_assignments' => (clone $assignmentQuery)->where('is_primary', true)->count(),
        ];
        
        // Message activity
        $messageQuery = Message::query();
        if ($role !== 'admin' && $role !== 'gov_official') {
            if (in_array($role, ['social_worker', 'law_enforcement', 'healthcare'])) {
                // These roles see messages for their assigned cases
                $messageQuery->whereHas('messageable', function($q) use ($user) {
                    $q->assignedTo($user->id);
                });
            } else {
                // Public users see messages for their own reports
                $messageQuery->whereHas('messageable', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }
        
        $messageStats = [
            'total_messages' => (clone $messageQuery)->count(),
            'recent_messages' => (clone $messageQuery)->where('created_at', '>=', $thirtyDaysAgo)->count(),
        ];
        
        return view('admin.dashboard', compact(
            'stats',
            'recentStats', 
            'monthlyTrends',
            'statusDistribution',
            'roleDistribution',
            'recentReports',
            'assignmentStats',
            'messageStats'
        ));
    }

    /**
     * Export cases/reports data to CSV
     */
    public function exportCasesCSV(Request $request)
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);
        
        // Base query for reports
        $query = Report::with(['user', 'assignees.role']);
        
        // Filter reports based on user role
        if ($role !== 'admin' && $role !== 'gov_official') {
            if (in_array($role, ['social_worker', 'law_enforcement', 'healthcare'])) {
                $query->assignedTo($user->id);
            } else {
                $query->where('user_id', $user->id);
            }
        }

        // Apply filters
        if ($request->has('status') && !empty($request->status)) {
            $query->where('report_status', $request->status);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('abuse_type') && !empty($request->abuse_type)) {
            $query->whereJsonContains('abuse_types', $request->abuse_type);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        $filename = 'cases_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Reporter Name', 'Email', 'Phone', 'Incident Description', 
                'Abuse Types', 'Evidence', 'Status', 'Priority', 'Location',
                'Assigned To', 'Created At', 'Updated At'
            ]);

            // Add data
            foreach ($reports as $report) {
                $assignedTo = $report->assignees->map(function($assignee) {
                    return $assignee->name . ' (' . $assignee->role->name . ')';
                })->implode(', ');

                fputcsv($file, [
                    $report->id,
                    $report->reporter_name,
                    $report->reporter_email,
                    $report->reporter_phone,
                    $report->incident_description,
                    is_array($report->abuse_types) ? implode(', ', $report->abuse_types) : $report->abuse_types,
                    is_array($report->evidence) ? implode(', ', $report->evidence) : $report->evidence,
                    $report->report_status,
                    $report->priority_level,
                    $report->incident_location,
                    $assignedTo,
                    $report->created_at->format('Y-m-d H:i:s'),
                    $report->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export cases/reports data to PDF
     */
    public function exportCasesPDF(Request $request)
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);
        
        // Base query for reports
        $query = Report::with(['user', 'assignees.role']);
        
        // Filter reports based on user role
        if ($role !== 'admin' && $role !== 'gov_official') {
            if (in_array($role, ['social_worker', 'law_enforcement', 'healthcare'])) {
                $query->assignedTo($user->id);
            } else {
                $query->where('user_id', $user->id);
            }
        }

        // Apply filters
        if ($request->has('status') && !empty($request->status)) {
            $query->where('report_status', $request->status);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('abuse_type') && !empty($request->abuse_type)) {
            $query->whereJsonContains('abuse_types', $request->abuse_type);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.cases_pdf', compact('reports'));
        $pdf->setPaper('a4', 'landscape');
        
        $filename = 'cases_export_' . date('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export users data to CSV
     */
    public function exportUsersCSV(Request $request)
    {
        $query = User::with(['role', 'profile']);

        // Apply filters
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('email_verified_at', '!=', null);
            } else {
                $query->where('email_verified_at', null);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Role', 'Phone', 'Address', 'City', 'State',
                'Email Verified', 'Created At', 'Last Login'
            ]);

            // Add data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role ? $user->role->name : 'No Role',
                    optional($user->profile)->phone ?? '',
                    optional($user->profile)->address_line1 ?? '',
                    optional($user->profile)->city ?? '',
                    optional($user->profile)->state ?? '',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export users data to PDF
     */
    public function exportUsersPDF(Request $request)
    {
        $query = User::with(['role', 'profile']);

        // Apply filters
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('email_verified_at', '!=', null);
            } else {
                $query->where('email_verified_at', null);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.users_pdf', compact('users'));
            $pdf->setPaper('a4', 'landscape');
            
            $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('PDF export error: ' . $e->getMessage());
            abort(500, 'PDF export failed: ' . $e->getMessage());
        }
    }

    /**
     * Export contact queries data to CSV
     */
    public function exportContactQueriesCSV(Request $request)
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);

        $query = ContactQuery::with('user');

        // Admin can see all queries, others see only their own
        if ($role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $queries = $query->orderBy('created_at', 'desc')->get();

        $filename = 'contact_queries_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($queries) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Subject', 'Message', 'Status', 
                'User', 'Created At', 'Updated At'
            ]);

            // Add data
            foreach ($queries as $query) {
                fputcsv($file, [
                    $query->id,
                    $query->name,
                    $query->email,
                    $query->subject,
                    $query->message,
                    $query->status,
                    $query->user ? $query->user->name : 'Anonymous',
                    $query->created_at->format('Y-m-d H:i:s'),
                    $query->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export contact queries data to PDF
     */
    public function exportContactQueriesPDF(Request $request)
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);

        $query = ContactQuery::with('user');

        // Admin can see all queries, others see only their own
        if ($role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $queries = $query->orderBy('created_at', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.contact_queries_pdf', compact('queries'));
        $pdf->setPaper('a4', 'landscape');
        
        $filename = 'contact_queries_export_' . date('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($filename);
    }
}
