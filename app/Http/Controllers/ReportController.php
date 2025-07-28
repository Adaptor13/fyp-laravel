<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'nullable|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
            'reporter_phone' => 'nullable|string|max:20',
            'victim_age' => 'nullable|string|max:10',
            'victim_gender' => 'nullable|string',
            'abuse_types' => 'nullable|array',
            'incident_description' => 'required|string',
            'incident_location' => 'required|string',
            'incident_date' => 'required|date',
            'suspected_abuser' => 'nullable|string|max:255',
            'evidence' => 'nullable|array',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,mp4,pdf|max:20480',
            'confirmed_truth' => 'accepted'
        ]);

        $filePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $filePaths[] = $file->store('evidence', 'public');
            }
        }

        Report::create([
            'reporter_name' => $validated['reporter_name'] ?? null,
            'reporter_email' => $validated['reporter_email'] ?? null,
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
        ]);

        return redirect()->back()->with('success', 'Your report has been submitted successfully.');
    }
}