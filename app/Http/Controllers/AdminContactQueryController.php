<?php

namespace App\Http\Controllers;

use App\Models\ContactQuery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminContactQueryController extends Controller
{
    /**
     * Display a listing of contact queries
     */
    public function index()
    {
        // Get contact query statistics
        $stats = $this->getContactQueryStatistics();
        
        // Get user permissions for frontend permission checking
        $user = auth()->user();
        $userPermissions = $user ? $user->getAllPermissions()->pluck('slug')->toArray() : [];
        
        return view('admin.contact-queries.index', compact('stats', 'userPermissions'));
    }

    /**
     * Get contact query statistics
     */
    private function getContactQueryStatistics()
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);

        // Base query
        $query = ContactQuery::query();

        // Admin can see all queries, others see only their own
        if ($role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $total = (clone $query)->count();
        $pending = (clone $query)->where('status', 'pending')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $resolved = (clone $query)->where('status', 'resolved')->count();
        $today = (clone $query)->whereDate('created_at', today())->count();
        $thisWeek = (clone $query)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $thisMonth = (clone $query)->whereMonth('created_at', now()->month)->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'today' => $today,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get contact queries data for DataTables
     */
    public function getData(Request $request)
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);

        $query = ContactQuery::with('user')
            ->select([
                'contact_queries.id',
                'contact_queries.name',
                'contact_queries.email',
                'contact_queries.subject',
                'contact_queries.message',
                'contact_queries.status',
                'contact_queries.created_at',
                'contact_queries.updated_at',
                'contact_queries.user_id'
            ]);

        // Admin can see all queries, others see only their own
        if ($role !== 'admin') {
            $query->where('contact_queries.user_id', $user->id);
        }

        // Apply search filter
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('contact_queries.name', 'like', "%{$searchValue}%")
                  ->orWhere('contact_queries.email', 'like', "%{$searchValue}%")
                  ->orWhere('contact_queries.subject', 'like', "%{$searchValue}%")
                  ->orWhere('contact_queries.message', 'like', "%{$searchValue}%");
            });
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('contact_queries.status', $request->status);
        }

        // Apply date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('contact_queries.created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('contact_queries.created_at', '<=', $request->date_to);
        }

        $totalRecords = $query->count();
        $filteredRecords = $query->count();

        // Apply ordering
        if ($request->has('order')) {
            $columns = ['id', 'name', 'email', 'subject', 'status', 'created_at'];
            $orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
            $orderDirection = $request->order[0]['dir'] ?? 'desc';
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Apply pagination
        $queries = $query->skip($request->start ?? 0)
                        ->take($request->length ?? 10)
                        ->get();

        $data = $queries->map(function ($query) {
            $statusClass = match($query->status) {
                'pending' => 'bg-warning',
                'in_progress' => 'bg-info',
                'resolved' => 'bg-success',
                default => 'bg-secondary'
            };

            $statusText = match($query->status) {
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'resolved' => 'Resolved',
                default => ucfirst($query->status)
            };

            return [
                'id' => $query->id,
                'name' => $query->name,
                'email' => $query->email,
                'subject' => $query->subject,
                'message' => substr($query->message, 0, 100) . (strlen($query->message) > 100 ? '...' : ''),
                'status' => [
                    'text' => $statusText,
                    'class' => $statusClass
                ],
                'user' => $query->user ? $query->user->name : 'Anonymous',
                'created_at' => $query->created_at->format('M d, Y H:i'),
                'updated_at' => $query->updated_at->format('M d, Y H:i'),
                'actions' => $this->getActionButtons($query)
            ];
        });

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get action buttons for DataTables
     */
    private function getActionButtons($query)
    {
        $buttons = [];
        
        // Start dropdown
        $buttons[] = '<div class="dropdown">';
        $buttons[] = '<button class="bg-none border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
        $buttons[] = '<i class="ti ti-dots"></i>';
        $buttons[] = '</button>';
        $buttons[] = '<ul class="dropdown-menu">';
        
        // View button
        if (auth()->user()->hasPermission('contact_queries.view')) {
            $buttons[] = '<li><a class="dropdown-item" href="' . route('admin.contact-queries.show', $query->id) . '">';
            $buttons[] = '<i class="ti ti-eye text-primary"></i> View Details';
            $buttons[] = '</a></li>';
        }
        
        // Delete button
        if (auth()->user()->hasPermission('contact_queries.delete')) {
            $label = $query->subject ?: $query->name ?: 'ID ' . $query->id;
            $buttons[] = '<li><a class="dropdown-item delete-btn" href="javascript:void(0)" data-id="' . $query->id . '" data-label="' . htmlspecialchars($label) . '">';
            $buttons[] = '<i class="ti ti-trash text-danger"></i> Delete';
            $buttons[] = '</a></li>';
        }
        
        // End dropdown
        $buttons[] = '</ul>';
        $buttons[] = '</div>';
        
        return implode('', $buttons);
    }

    /**
     * Show the specified contact query
     */
    public function show($id)
    {
        $contactQuery = ContactQuery::findOrFail($id);
        
        // Check if user can view this query
        $this->authorize('view', $contactQuery);
        
        return view('admin.contact-queries.show', compact('contactQuery'));
    }

    /**
     * Remove the specified contact query from storage.
     */
    public function destroy($id)
    {
        $contactQuery = ContactQuery::findOrFail($id);
        
        // Check if user can delete this query
        $this->authorize('delete', $contactQuery);

        try {
            $contactQuery->delete();
            return back()->with('success', 'Contact query deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete contact query.');
        }
    }



    /**
     * Update contact query status via AJAX
     */
    public function updateStatus(Request $request, $id)
    {
        $contactQuery = ContactQuery::findOrFail($id);
        
        // Check if user can update status
        $this->authorize('update', $contactQuery);

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        try {
            $oldStatus = $contactQuery->status;
            $contactQuery->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }



    /**
     * Export contact queries
     */
    public function export(Request $request)
    {
        // Check if user can export
        $this->authorize('export', ContactQuery::class);

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

        // Generate CSV
        $filename = 'contact_queries_' . date('Y-m-d_H-i-s') . '.csv';
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
}
