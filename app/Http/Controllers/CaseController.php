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
                'reports.incident_description',
                'reports.report_status',
                'reports.priority_level',
                'reports.updated_at',
                DB::raw("GROUP_CONCAT(CONCAT(assignee.name, CASE WHEN case_assignments.is_primary = 1 THEN ' (Lead)' ELSE '' END) SEPARATOR ', ') as assigned_names"),
            ])
            ->whereNull('case_assignments.unassigned_at')
            ->groupBy('reports.id', 'reports.reporter_name', 'reports.reporter_email', 'reports.incident_description', 'reports.report_status', 'reports.priority_level', 'reports.updated_at');

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
                'incident_description' => $r->incident_description ?? '—',
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
        
        return view('admin.cases.show', compact('report', 'assignees'));
    }

    public function edit(Report $report)
    {
        $assignableUsers = User::whereIn('role_id', Role::whereIn('name', ['social_worker', 'law_enforcement', 'healthcare', 'gov_official'])->pluck('id'))->get();
        $abuseTypes = json_decode($report->abuse_types, true) ?? [];
        $currentAssignees = $report->assignees->pluck('id')->toArray();
        
        // Get role-specific assignments
        $roleAssignments = [];
        foreach ($report->assignees as $assignee) {
            $roleName = $assignee->role->name;
            $roleAssignments[$roleName] = $assignee->id;
        }
        
        return view('admin.cases.edit', compact('report', 'assignableUsers', 'abuseTypes', 'currentAssignees', 'roleAssignments'));
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
                    'user_id' => auth()->id() ?? null,
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
                    'last_updated_by' => auth()->id() ?? null,
                    'status_updated_at' => now(),
                ]);

                // Handle assignments
                if ($request->has('assignees') && is_array($request->assignees)) {
                    // Filter out empty values
                    $validAssignees = array_filter($request->assignees, function($userId) {
                        return !empty($userId) && $userId !== '';
                    });
                    
                    // Create assignments for all valid assignees
                    $assignedUsers = [];
                    foreach ($validAssignees as $userId) {
                        CaseAssignment::create([
                            'report_id' => $report->id,
                            'user_id' => $userId,
                            'is_primary' => false,
                            'assigned_at' => now(),
                        ]);
                        
                        $user = User::find($userId);
                        if ($user) {
                            $assignedUsers[] = $user->name;
                        }
                    }
                    
                    // Log assignment if users were assigned
                    if (!empty($assignedUsers)) {
                        $report->logAction('assigned', 'Case assigned to: ' . implode(', ', $assignedUsers));
                    }
                }
            });

            return back()->with('success', 'Case created successfully.');
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('Case creation failed: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()
                ->withErrors(['general' => 'Failed to create case. Please try again. Error: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(UpdateCaseRequest $request, Report $report)
    {
        try {
            DB::transaction(function () use ($request, $report) {
                $validated = $request->validated();

                // Handle file uploads and removals
                $filePaths = [];
                
                // Get existing evidence files
                $existingEvidence = json_decode($report->evidence, true) ?? [];
                
                // Get files marked for removal
                $removedEvidence = [];
                if ($request->has('removed_evidence') && !empty($request->removed_evidence)) {
                    $removedEvidence = json_decode($request->removed_evidence, true) ?? [];
                }
                
                // Filter out removed files from existing evidence
                $existingEvidence = array_filter($existingEvidence, function($file) use ($removedEvidence) {
                    return !in_array($file, $removedEvidence);
                });
                
                // Add new uploaded files
                if ($request->hasFile('evidence')) {
                    foreach ($request->file('evidence') as $file) {
                        $filePaths[] = $file->store('evidence', 'public');
                    }
                }
                
                // Combine existing (non-removed) and new files
                $filePaths = array_merge($existingEvidence, $filePaths);
                
                // Delete removed files from storage
                foreach ($removedEvidence as $fileToDelete) {
                    if (Storage::disk('public')->exists($fileToDelete)) {
                        Storage::disk('public')->delete($fileToDelete);
                    }
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
                    // Filter out empty values
                    $validAssignees = array_filter($request->assignees, function($userId) {
                        return !empty($userId) && $userId !== '';
                    });
                    
                    // Get current active assignments
                    $currentAssignments = CaseAssignment::where('report_id', $report->id)
                        ->whereNull('unassigned_at')
                        ->get()
                        ->keyBy('user_id');
                    
                    // Mark assignments that are no longer needed as unassigned
                    $currentUserIds = $currentAssignments->keys()->toArray();
                    $newUserIds = $validAssignees;
                    $toUnassign = array_diff($currentUserIds, $newUserIds);
                    
                    if (!empty($toUnassign)) {
                        CaseAssignment::where('report_id', $report->id)
                            ->whereIn('user_id', $toUnassign)
                            ->whereNull('unassigned_at')
                            ->update(['unassigned_at' => now()]);
                    }
                    
                    // Create or update assignments for all valid assignees
                    foreach ($validAssignees as $userId) {
                        // Check if assignment already exists
                        $existingAssignment = $currentAssignments->get($userId);
                        
                        if (!$existingAssignment) {
                            // Create new assignment
                            CaseAssignment::create([
                                'report_id' => $report->id,
                                'user_id' => $userId,
                                'is_primary' => false,
                                'assigned_at' => now(),
                            ]);
                        }
                    }
                }
            });

            return back()->with('success', 'Case updated successfully.');
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('Case update failed: ' . $e->getMessage(), [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()
                ->withErrors(['general' => 'Failed to update case. Please try again. Error: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Report $report)
    {
        try {
            // Check if user has permission to delete this case
            $user = auth()->user();
            $isAssigned = $report->assignees->contains('id', $user->id);
            $isAdmin = $user->role && $user->role->name === 'admin';
            
            if (!$isAdmin && !$isAssigned) {
                abort(403, 'You do not have permission to delete this case.');
            }

            DB::transaction(function () use ($report) {
                if ($report->evidence) {
                    $filePaths = json_decode($report->evidence, true) ?? [];
                    foreach ($filePaths as $filePath) {
                        if (Storage::disk('public')->exists($filePath)) {
                            Storage::disk('public')->delete($filePath);
                        }
                    }
                }

                // Check if report still exists before deletion
                if (!$report->exists) {
                    throw new \Exception('Report no longer exists');
                }

                // Delete the report (cascade will handle related records)
                $deleted = $report->delete();
                
                if (!$deleted) {
                    throw new \Exception('Failed to delete report from database');
                }
            });

            // Return appropriate response based on request type
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Case deleted successfully.']);
            }
            
            return back()->with('success', 'Case deleted successfully.');
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('Case deletion failed: ' . $e->getMessage(), [
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Failed to delete case. Please try again. Error: ' . $e->getMessage();
            
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }
            
            return back()->with('error', $errorMessage);
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

        $oldStatus = $report->report_status;
        $newStatus = $validated['status'];

        $report->update([
            'report_status' => $newStatus,
            'last_updated_by' => auth()->id(),
            'status_updated_at' => now(),
        ]);

        // Log the status change specifically
        $report->logAction('status_changed', "Status changed from '{$oldStatus}' to '{$newStatus}'", [
            'report_status' => [
                'from' => $oldStatus,
                'to' => $newStatus
            ]
        ]);

        return back()->with('success', 'Case status updated successfully.');
    }

    public function export(Report $report)
    {
        try {
            // Check if user has permission to export this case
            // For now, we'll allow admins and assigned users to export
            $user = auth()->user();
            $isAssigned = $report->assignees->contains('id', $user->id);
            $isAdmin = $user->role && $user->role->name === 'admin';
            
            if (!$isAdmin && !$isAssigned) {
                abort(403, 'You do not have permission to export this case.');
            }

            // Prepare data for the PDF
            $abuseTypes = json_decode($report->abuse_types, true) ?? [];
            $evidence = json_decode($report->evidence, true) ?? [];

            // Generate PDF using the existing PDF template
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.report_pdf', compact('report', 'abuseTypes', 'evidence'));
            
            // Set PDF options
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption('margin-top', 10);
            $pdf->setOption('margin-right', 10);
            $pdf->setOption('margin-bottom', 10);
            $pdf->setOption('margin-left', 10);

            // Generate filename
            $filename = 'Case_' . substr($report->id, 0, 8) . '_' . now()->format('Y-m-d') . '.pdf';

            // Return PDF as download
            return $pdf->download($filename);

        } catch (\Throwable $e) {
            \Log::error('Case export failed: ' . $e->getMessage(), [
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Failed to export case. Please try again.');
        }
    }


}
