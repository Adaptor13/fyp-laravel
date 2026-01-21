<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\CaseHistory;

class CaseHistoryController extends Controller
{
    /**
     * Show the case history for a specific case
     */
    public function show(Request $request, Report $report)
    {
        // Check if user has permission to view this case
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);

        // Admin, gov_official, and social_worker can view all cases
        if (!in_array($role, ['admin', 'gov_official', 'social_worker'])) {
            // Other roles can only view cases assigned to them
            if (!$report->assignees->contains('id', $user->id)) {
                abort(403, 'You do not have permission to view this case.');
            }
        }

        // Get case history with user information
        $history = $report->history()->with('user')->get();

        if ($request->ajax()) {
            return view('admin.cases.history', compact('report', 'history'));
        }

        return view('admin.cases.history', compact('report', 'history'));
    }

    /**
     * Get case history as JSON for AJAX requests
     */
    public function getHistory(Report $report)
    {
        // Check if user has permission to view this case
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);

        // Admin, gov_official, and social_worker can view all cases
        if (!in_array($role, ['admin', 'gov_official', 'social_worker'])) {
            // Other roles can only view cases assigned to them
            if (!$report->assignees->contains('id', $user->id)) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        // Get case history with user information
        $history = $report->history()->with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}
