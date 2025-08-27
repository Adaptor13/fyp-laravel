<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Models\Role;
use App\Models\CaseAssignment;
use Carbon\Carbon;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CaseController extends Controller
{
    public function index()
    {
        // Get case statistics
        $stats = $this->getCaseStatistics();
        return view('admin.cases.index', compact('stats'));
    }

    private function getCaseStatistics()
    {
        $user = auth()->user();
        $role = strtolower(optional($user->role)->name);

        // Base query
        $query = Report::query();

        // Visibility by role
        if (!in_array($role, ['admin','gov_official'])) {
            if (in_array($role, ['social_worker','law_enforcement','healthcare'])) {
                $query->assignedTo($user->id);
            } else {
                $query->whereRaw('1=0'); // blocks public_user or unknown roles
            }
        }

        // Total cases
        $totalCases = $query->count();

        // Open cases (not closed or resolved)
        $openCases = (clone $query)->whereNotIn('report_status', ['Closed', 'Resolved'])->count();

        // Closed cases
        $closedCases = (clone $query)->whereIn('report_status', ['Closed', 'Resolved'])->count();

        // New cases this week
        $newThisWeek = (clone $query)->where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        return [
            'total' => $totalCases,
            'open' => $openCases,
            'closed' => $closedCases,
            'new_this_week' => $newThisWeek
        ];
    }

    public function reportData(Request $req)
    {
        $user = $req->user();
        $role = strtolower(optional($user->role)->name); // admin, gov_official, social_worker, law_enforcement, healthcare, public_user

        // Base query: join to case_assignments to display assignees
        $query = Report::query()
            ->leftJoin('case_assignments', 'reports.id', '=', 'case_assignments.report_id')
            ->leftJoin('users as assignee', 'case_assignments.user_id', '=', 'assignee.id')
            ->select([
                'reports.id',
                'reports.reporter_name',
                'reports.reporter_email',
                'reports.report_status',
                'reports.priority_level',
                'reports.updated_at',
                DB::raw("GROUP_CONCAT(CONCAT(assignee.name, CASE WHEN case_assignments.is_primary = 1 THEN ' (Lead)' ELSE '' END) SEPARATOR ', ') as assigned_names"),
            ])
            ->whereNull('case_assignments.unassigned_at')
            ->groupBy('reports.id', 'reports.reporter_name', 'reports.reporter_email', 'reports.report_status', 'reports.priority_level', 'reports.updated_at');

        // Visibility by role
        if (!in_array($role, ['admin','gov_official'])) {
            if (in_array($role, ['social_worker','law_enforcement','healthcare'])) {
                $query->where('case_assignments.user_id', $user->id);
            } else {
                $query->whereRaw('1=0'); // blocks public_user or unknown roles
            }
        }

        $rows = $query->latest('reports.created_at')->get();

        // Map to DataTables-friendly payload
        $data = $rows->map(function ($r) {
            $assignedDisplay = $r->assigned_names ?: 'Unassigned';

            return [
                'id'       => (string) $r->id,
                'case_id'  => substr($r->id, 0, 17) . '...', // Truncated case ID for display
                'reporter' => [
                    'name'  => $r->reporter_name ?? '—',
                    'email' => $r->reporter_email ?? '—',
                ],
                'status'   => $r->report_status ?? '',
                'priority' => $r->priority_level ?? '',
                'assigned' => $assignedDisplay,
                'updated'  => optional($r->updated_at)->toIso8601String(),
                'actions'  => '<a href="'.route('cases.show', $r->id).'" class="btn btn-sm btn-outline-primary">Open</a>',
            ];
        })->values();

        return response()->json(['data' => $data]);
    }

    public function show(Report $report)
    {
        // Get all assignees for this case
        $assignees = $report->assignees;
        $primaryAssignee = $report->primaryAssignee->first();
        
        return view('admin.cases.show', compact('report', 'assignees', 'primaryAssignee'));
    }

    public function edit(Report $report)
    {
        $assignableUsers = User::whereIn('role_id', Role::whereIn('name', ['social_worker', 'law_enforcement', 'healthcare'])->pluck('id'))->get();
        $abuseTypes = json_decode($report->abuse_types, true) ?? [];
        $currentAssignees = $report->assignees->pluck('id')->toArray();
        $primaryAssigneeId = $report->primaryAssignee->first()?->id;
        
        return view('admin.cases.edit', compact('report', 'assignableUsers', 'abuseTypes', 'currentAssignees', 'primaryAssigneeId'));
    }

    public function store(StoreCaseRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $validated = $request->validated();

                // Handle file uploads
                $filePaths = [];
                if ($request->hasFile('evidence')) {
                    foreach ($request->file('evidence') as $file) {
                        $filePaths[] = $file->store('evidence', 'public');
                    }
                }

                $report = Report::create([
                    'user_id' => auth()->id(),
                    'reporter_name' => $validated['reporter_name'],
                    'reporter_email' => $validated['reporter_email'],
                    'reporter_phone' => $validated['reporter_phone'] ?? null,
                    'victim_age' => $validated['victim_age'] ?? null,
                    'victim_gender' => $validated['victim_gender'] ?? null,
                    'abuse_types' => json_encode($validated['abuse_types'] ?? []),
                    'incident_description' => $validated['incident_description'],
                    'incident_location' => $validated['incident_location'],
                    'incident_date' => $validated['incident_date'],
                    'suspected_abuser' => $validated['suspected_abuser'] ?? null,
                    'evidence' => json_encode($filePaths),
                    'confirmed_truth' => true,
                    'report_status' => $validated['report_status'],
                    'priority_level' => $validated['priority_level'],
                    'last_updated_by' => auth()->id(),
                    'status_updated_at' => now(),
                ]);

                // Handle assignments
                if ($request->has('assignees') && is_array($request->assignees)) {
                    foreach ($request->assignees as $index => $userId) {
                        if ($userId) {
                            $isPrimary = $request->input('primary_assignee') == $userId;
                            
                            CaseAssignment::create([
                                'report_id' => $report->id,
                                'user_id' => $userId,
                                'is_primary' => $isPrimary,
                                'assigned_at' => now(),
                            ]);
                        }
                    }
                }
            });

            return back()->with('success', 'Case created successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to create case. Please try again.'])
                ->withInput();
        }
    }

    public function update(UpdateCaseRequest $request, Report $report)
    {
        try {
            DB::transaction(function () use ($request, $report) {
                $validated = $request->validated();

                // Handle file uploads
                $filePaths = [];
                if ($request->hasFile('evidence')) {
                    foreach ($request->file('evidence') as $file) {
                        $filePaths[] = $file->store('evidence', 'public');
                    }
                }

                // Keep existing files if no new ones uploaded
                if (empty($filePaths) && $report->evidence) {
                    $filePaths = json_decode($report->evidence, true) ?? [];
                }

                $report->update([
                    'reporter_name' => $validated['reporter_name'],
                    'reporter_email' => $validated['reporter_email'],
                    'reporter_phone' => $validated['reporter_phone'] ?? null,
                    'victim_age' => $validated['victim_age'] ?? null,
                    'victim_gender' => $validated['victim_gender'] ?? null,
                    'abuse_types' => json_encode($validated['abuse_types'] ?? []),
                    'incident_description' => $validated['incident_description'],
                    'incident_location' => $validated['incident_location'],
                    'incident_date' => $validated['incident_date'],
                    'suspected_abuser' => $validated['suspected_abuser'] ?? null,
                    'evidence' => json_encode($filePaths),
                    'report_status' => $validated['report_status'],
                    'priority_level' => $validated['priority_level'],
                    'last_updated_by' => auth()->id(),
                    'status_updated_at' => now(),
                ]);

                // Handle assignments
                if ($request->has('assignees')) {
                    // Mark all current assignments as unassigned
                    CaseAssignment::where('report_id', $report->id)
                        ->whereNull('unassigned_at')
                        ->update(['unassigned_at' => now()]);

                    // Create new assignments
                    if (is_array($request->assignees)) {
                        foreach ($request->assignees as $userId) {
                            if ($userId) {
                                $isPrimary = $request->input('primary_assignee') == $userId;
                                
                                CaseAssignment::create([
                                    'report_id' => $report->id,
                                    'user_id' => $userId,
                                    'is_primary' => $isPrimary,
                                    'assigned_at' => now(),
                                ]);
                            }
                        }
                    }
                }
            });

            return back()->with('success', 'Case updated successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to update case. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(Report $report)
    {
        try {
            DB::transaction(function () use ($report) {
                // Delete associated files
                if ($report->evidence) {
                    $filePaths = json_decode($report->evidence, true) ?? [];
                    foreach ($filePaths as $filePath) {
                        Storage::disk('public')->delete($filePath);
                    }
                }

                $report->delete();
            });

            return back()->with('success', 'Case deleted successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete case. Please try again.');
        }
    }

    public function addNote(Request $request, Report $report)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        // This would typically go to a separate notes table
        // For now, we'll just update the case with a note
        $report->update([
            'last_updated_by' => auth()->id(),
            'status_updated_at' => now(),
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Submitted,Under Review,In Progress,Resolved,Closed',
        ]);

        $report->update([
            'report_status' => $validated['status'],
            'last_updated_by' => auth()->id(),
            'status_updated_at' => now(),
        ]);

        return back()->with('success', 'Case status updated successfully.');
    }
}
