<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaseMessageController extends Controller
{
    /**
     * Display messages for a specific case
     */
    public function index(Report $case)
    {
        // Check if user can view this case
        $this->authorize('view', $case);

        // Check if case has assignees
        $hasAssignees = $case->assignees()->count() > 0;

        $messages = $case->messages()->with('sender')->latest()->get();

        return response()->json([
            'messages' => $messages,
            'case_status' => $case->report_status,
            'can_post' => $case->report_status !== 'Closed' && $hasAssignees,
            'has_assignees' => $hasAssignees
        ]);
    }

    /**
     * Store a new message for a case
     */
    public function store(Request $request, Report $case)
    {
        // Check if user can post to this case
        $this->authorize('post', $case);

        // Validate request
        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        // Check if case is closed
        if ($case->report_status === 'Closed') {
            return response()->json([
                'error' => 'Cannot send messages to closed cases'
            ], 403);
        }

        // Check if case has assignees
        if ($case->assignees()->count() === 0) {
            return response()->json([
                'error' => 'Cannot send messages to cases without assignees'
            ], 403);
        }

        try {
            DB::transaction(function () use ($request, $case) {
                // Create the message
                $message = Message::create([
                    'messageable_type' => Report::class,
                    'messageable_id' => $case->id,
                    'sender_id' => Auth::id(),
                    'body' => $request->body,
                    'attachments' => null, // For future implementation
                ]);

                // Update last_message_at on the case
                $case->update([
                    'last_message_at' => now()
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send message'
            ], 500);
        }
    }
}
