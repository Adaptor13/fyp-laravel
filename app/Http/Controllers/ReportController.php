<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'nullable|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
            'reporter_phone' => 'nullable|string|max:20',
            'victim_age' => 'required|string|max:10',
            'victim_gender' => 'required|string|in:Male,Female,Other',
            'abuse_types' => 'required|array|min:1',
            'abuse_types.*' => 'required|string|in:Physical Abuse,Emotional Abuse,Sexual Abuse,Neglect,Exploitation',
            'incident_description' => 'required|string',
            'incident_location' => 'required|string', 
            'incident_date' => 'required|date|before_or_equal:today',
            'suspected_abuser' => 'nullable|string|max:255',
            'evidence' => 'nullable|array|max:5', // Limit to 5 files
            'evidence.*' => 'file|mimes:jpg,jpeg,png,mp4,pdf|max:20480',
            'confirmed_truth' => 'accepted'
        ], [
            'victim_age.required' => 'Victim\'s age is required.',
            'victim_gender.required' => 'Victim\'s gender is required.',
            'victim_gender.in' => 'Please select a valid gender.',
            'abuse_types.required' => 'Please select at least one type of abuse.',
            'abuse_types.min' => 'Please select at least one type of abuse.',
            'abuse_types.*.in' => 'Please select valid abuse types.',
            'incident_description.required' => 'Incident description is required.',
            'incident_location.required' => 'Incident location is required.',
            'incident_date.required' => 'Incident date is required.',
            'incident_date.before_or_equal' => 'Incident date cannot be in the future.',
            'evidence.max' => 'Maximum 5 files allowed for evidence.',
            'confirmed_truth.accepted' => 'You must confirm that the information provided is accurate.'
        ]);

        $filePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $filePaths[] = $file->store('evidence', 'public');
            }
        }

        Report::create([
            'id' => Str::uuid(), // Generate UUID
            'user_id' => auth()->check() ? auth()->id() : null, // Link if logged in
        
            'reporter_name' => $validated['reporter_name'] ?? 'anonymous',
            'reporter_email' => $validated['reporter_email'] ?? 'anonymous@gmail.com',
            'reporter_phone' => $validated['reporter_phone'] ?? null,
            'victim_age' => $validated['victim_age'] ?? null,
            'victim_gender' => $validated['victim_gender'] ?? null,
            'abuse_types' => $validated['abuse_types'] ?? [],
            'incident_description' => $validated['incident_description'],
            'incident_location' => $validated['incident_location'],
            'incident_date' => $validated['incident_date'],
            'suspected_abuser' => $validated['suspected_abuser'] ?? null,
            'evidence' => $filePaths,
            'confirmed_truth' => true,
            'report_status' => 'Submitted',
            'priority_level' => 'Medium',
        ]);

        return redirect()->back()->with('success', 'Your report has been submitted successfully.');
    }

    public function myReports()
    {
        $reports = Report::where('user_id', auth()->id())
            ->with(['assignees' => function($query) {
                $query->with('profile', 'publicUserProfile', 'lawEnforcementProfile', 'socialWorkerProfile', 'healthcareProfile', 'adminProfile', 'govOfficialProfile');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('landing.my_reports', compact('reports'));
    }

    public function export(Report $report)
    {
        try {
            // Check if the user owns this report
            if ($report->user_id !== auth()->id()) {
                abort(403, 'Unauthorized access to this report.');
            }

            // Prepare data for the PDF
            $data = [
                'report' => $report,
                'abuseTypes' => $report->abuse_types ?? [],
                'evidence' => $report->evidence ?? [],
            ];

            // Generate PDF
            $pdf = Pdf::loadView('exports.report_pdf', $data);
            
            // Set PDF options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'defaultFont' => 'Arial',
            ]);

            // Generate filename with timestamp
            $filename = 'Child_Protection_Report_' . substr($report->id, 0, 8) . '_' . $report->created_at->format('Y-m-d') . '.pdf';

            // Return PDF as download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('PDF Export Error: ' . $e->getMessage(), [
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            // Return a user-friendly error response
            return back()->with('error', 'Unable to generate PDF. Please try again later.');
        }
    }
}