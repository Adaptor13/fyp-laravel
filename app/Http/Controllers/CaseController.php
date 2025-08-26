<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CaseController extends Controller
{
    public function index()
    {
        return view('admin.cases.index');
    }

    public function reportData(Request $req)
    {
        $user = $req->user();
        $role = strtolower(optional($user->role)->name); // admin, gov_official, social_worker, law_enforcement, healthcare, public_user

        // Base query: join to users to display assignee name
        $query = Report::query()
            ->leftJoin('users as assignee', 'reports.assigned_to', '=', 'assignee.id')
            ->select([
                'reports.id',
                'reports.reporter_name',
                'reports.reporter_email',
                'reports.report_status',
                'reports.priority_level',
                'reports.updated_at',
                'reports.assigned_to',
                DB::raw("COALESCE(assignee.name, 'Unassigned') as assigned_name"),
            ]);

        // Visibility by role
        if (!in_array($role, ['admin','gov_official'])) {
            if (in_array($role, ['social_worker','law_enforcement','healthcare'])) {
                $query->where('reports.assigned_to', $user->id);
            } else {
                $query->whereRaw('1=0'); // blocks public_user or unknown roles
            }
        }

        $rows = $query->latest('reports.created_at')->get();

        // Map to DataTables-friendly payload
        $data = $rows->map(function ($r) {
            $assignedDisplay = $r->assigned_name ?: ($r->assigned_to ?: 'Unassigned');

            return [
                'id'       => (string) $r->id,
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
        // simple detail view; you can add history later
        $assignee = $report->assigned_to ? User::find($report->assigned_to) : null;
        return view('admin.cases.show', compact('report','assignee'));
    }
}
