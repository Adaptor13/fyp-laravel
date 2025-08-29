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
        // Fix any existing abuse_types data that might be stored as strings instead of JSON
        $reports = \DB::table('reports')->whereNotNull('abuse_types')->get();
        
        foreach ($reports as $report) {
            $abuseTypes = $report->abuse_types;
            
            // If abuse_types is a string (not JSON), try to decode it
            if (is_string($abuseTypes) && !empty($abuseTypes)) {
                $decoded = json_decode($abuseTypes, true);
                
                // If it's valid JSON, update the record
                if (json_last_error() === JSON_ERROR_NONE) {
                    \DB::table('reports')
                        ->where('id', $report->id)
                        ->update(['abuse_types' => $abuseTypes]); // Keep as is since it's valid JSON
                } else {
                    // If it's not valid JSON, treat it as a single abuse type
                    \DB::table('reports')
                        ->where('id', $report->id)
                        ->update(['abuse_types' => json_encode([$abuseTypes])]);
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
