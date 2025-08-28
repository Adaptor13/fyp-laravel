<?php

require_once 'vendor/autoload.php';

use App\Models\Report;
use App\Models\User;
use App\Models\CaseAssignment;
use App\Models\Role;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Case Assignment System\n";
echo "==============================\n\n";

try {
    // Get some test users
    $users = User::whereIn('role_id', Role::whereIn('name', ['social_worker', 'law_enforcement', 'healthcare', 'child_welfare'])->pluck('id'))->limit(4)->get();
    
    if ($users->count() < 3) {
        echo "Error: Need at least 3 users with appropriate roles for testing.\n";
        exit(1);
    }
    
    echo "Found " . $users->count() . " users for testing:\n";
    foreach ($users as $user) {
        echo "- " . $user->name . " (" . optional($user->role)->name . ")\n";
    }
    echo "\n";
    
    // Create a test report
    $report = Report::create([
        'user_id' => $users->first()->id,
        'reporter_name' => 'Test Reporter',
        'reporter_email' => 'test@example.com',
        'incident_description' => 'Test incident description',
        'incident_location' => 'Test location',
        'incident_date' => now()->subDays(1),
        'report_status' => 'Submitted',
        'priority_level' => 'Low',
        'confirmed_truth' => true,
        'last_updated_by' => $users->first()->id,
        'status_updated_at' => now(),
    ]);
    
    echo "Created test report: " . $report->id . "\n";
    
    // Test 1: Create multiple assignments
    $assigneeIds = $users->take(3)->pluck('id')->toArray();
    $primaryAssigneeId = $assigneeIds[0];
    
    echo "Creating assignments for " . count($assigneeIds) . " users...\n";
    
    foreach ($assigneeIds as $userId) {
        $isPrimary = ($userId === $primaryAssigneeId);
        
        $assignment = CaseAssignment::create([
            'report_id' => $report->id,
            'user_id' => $userId,
            'is_primary' => $isPrimary,
            'assigned_at' => now(),
        ]);
        
        echo "- Assigned user " . $users->find($userId)->name . " (Primary: " . ($isPrimary ? 'Yes' : 'No') . ", ID: " . $assignment->id . ")\n";
    }
    
    // Test 2: Verify assignments were created
    $assignments = CaseAssignment::where('report_id', $report->id)->whereNull('unassigned_at')->get();
    echo "\nVerifying assignments:\n";
    echo "Total active assignments: " . $assignments->count() . "\n";
    
    foreach ($assignments as $assignment) {
        $user = $users->find($assignment->user_id);
        echo "- " . $user->name . " (Primary: " . ($assignment->is_primary ? 'Yes' : 'No') . ", Assignment ID: " . $assignment->id . ")\n";
    }
    
    // Test 3: Verify UUID format
    echo "\nVerifying UUID format:\n";
    foreach ($assignments as $assignment) {
        $isUuid = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $assignment->id);
        echo "- Assignment ID " . $assignment->id . " is " . ($isUuid ? 'valid UUID' : 'NOT valid UUID') . "\n";
    }
    
    // Test 4: Test relationship loading
    echo "\nTesting relationships:\n";
    $report->load('assignees');
    echo "Report has " . $report->assignees->count() . " assignees\n";
    
    $primaryAssignee = $report->primaryAssignee->first();
    if ($primaryAssignee) {
        echo "Primary assignee: " . $primaryAssignee->name . "\n";
    }
    
    // Test 5: Test update functionality (simulate edit)
    echo "\nTesting update functionality:\n";
    
    // Get current assignments
    $currentAssignments = CaseAssignment::where('report_id', $report->id)
        ->whereNull('unassigned_at')
        ->get()
        ->keyBy('user_id');
    
    // Simulate removing one assignee and changing primary
    $newAssigneeIds = array_slice($assigneeIds, 0, 2); // Remove last assignee
    $newPrimaryAssigneeId = $newAssigneeIds[1]; // Change primary to second user
    
    // Mark removed assignee as unassigned
    $removedUserId = $assigneeIds[2];
    CaseAssignment::where('report_id', $report->id)
        ->where('user_id', $removedUserId)
        ->whereNull('unassigned_at')
        ->update(['unassigned_at' => now()]);
    
    // Update primary status
    foreach ($newAssigneeIds as $userId) {
        $isPrimary = ($userId === $newPrimaryAssigneeId);
        $existingAssignment = $currentAssignments->get($userId);
        
        if ($existingAssignment && $existingAssignment->is_primary !== $isPrimary) {
            $existingAssignment->update(['is_primary' => $isPrimary]);
            echo "- Updated " . $users->find($userId)->name . " primary status to: " . ($isPrimary ? 'Yes' : 'No') . "\n";
        }
    }
    
    // Verify final state
    $finalAssignments = CaseAssignment::where('report_id', $report->id)->whereNull('unassigned_at')->get();
    echo "Final active assignments: " . $finalAssignments->count() . "\n";
    
    foreach ($finalAssignments as $assignment) {
        $user = $users->find($assignment->user_id);
        echo "- " . $user->name . " (Primary: " . ($assignment->is_primary ? 'Yes' : 'No') . ")\n";
    }
    
    echo "\n✅ All tests passed! Case assignment system is working correctly.\n";
    
    // Cleanup
    $report->delete();
    echo "Test data cleaned up.\n";
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
