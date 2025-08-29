<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any existing evidence data that might be stored as strings instead of JSON
        $reports = \DB::table('reports')->whereNotNull('evidence')->get();
        
        foreach ($reports as $report) {
            $evidence = $report->evidence;
            
            // If evidence is a string (not JSON), try to decode it
            if (is_string($evidence) && !empty($evidence)) {
                $decoded = json_decode($evidence, true);
                
                // If it's valid JSON, update the record
                if (json_last_error() === JSON_ERROR_NONE) {
                    \DB::table('reports')
                        ->where('id', $report->id)
                        ->update(['evidence' => $evidence]); // Keep as is since it's valid JSON
                } else {
                    // If it's not valid JSON, treat it as a single file path
                    \DB::table('reports')
                        ->where('id', $report->id)
                        ->update(['evidence' => json_encode([$evidence])]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
