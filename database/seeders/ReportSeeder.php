<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Get a user to associate reports with (or create one if none exist)
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $mockReports = [
            [
                'victim_age' => '12',
                'victim_gender' => 'Female',
                'abuse_types' => ['Physical Abuse', 'Emotional Abuse'],
                'incident_description' => 'Neighbor reported seeing bruises on child\'s arms and legs. Child appears withdrawn and fearful.',
                'incident_location' => 'Kuala Lumpur, Selangor',
                'incident_date' => '2024-01-15',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'Under Review',
                'priority_level' => 'High',
            ],
            [
                'victim_age' => '8',
                'victim_gender' => 'Male',
                'abuse_types' => ['Neglect'],
                'incident_description' => 'Child frequently seen alone at playground without supervision. Appears malnourished and dirty.',
                'incident_location' => 'Penang, Georgetown',
                'incident_date' => '2024-01-20',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'In Progress',
                'priority_level' => 'Medium',
            ],
            [
                'victim_age' => '15',
                'victim_gender' => 'Female',
                'abuse_types' => ['Sexual Abuse'],
                'incident_description' => 'Teenager disclosed inappropriate behavior by family member. Requires immediate intervention.',
                'incident_location' => 'Johor Bahru, Johor',
                'incident_date' => '2024-01-25',
                'suspected_abuser' => 'Family Member',
                'report_status' => 'Resolved',
                'priority_level' => 'High',
            ],
            [
                'victim_age' => '6',
                'victim_gender' => 'Male',
                'abuse_types' => ['Physical Abuse', 'Emotional Abuse'],
                'incident_description' => 'Teacher noticed behavioral changes and physical marks. Child reluctant to go home.',
                'incident_location' => 'Ipoh, Perak',
                'incident_date' => '2024-02-01',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'Submitted',
                'priority_level' => 'Medium',
            ],
            [
                'victim_age' => '13',
                'victim_gender' => 'Female',
                'abuse_types' => ['Emotional Abuse', 'Neglect'],
                'incident_description' => 'Child reports being constantly criticized and compared unfavorably to siblings. Shows signs of depression.',
                'incident_location' => 'Melaka, Melaka Tengah',
                'incident_date' => '2024-02-05',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'Closed',
                'priority_level' => 'Low',
            ],
        ];

        foreach ($mockReports as $reportData) {
            Report::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'reporter_name' => 'Anonymous Reporter',
                'reporter_email' => 'anonymous@example.com',
                'reporter_phone' => null,
                'victim_age' => $reportData['victim_age'],
                'victim_gender' => $reportData['victim_gender'],
                'abuse_types' => json_encode($reportData['abuse_types']),
                'incident_description' => $reportData['incident_description'],
                'incident_location' => $reportData['incident_location'],
                'incident_date' => $reportData['incident_date'],
                'suspected_abuser' => $reportData['suspected_abuser'],
                'evidence' => json_encode([]),
                'confirmed_truth' => true,
                'report_status' => $reportData['report_status'],
                'priority_level' => $reportData['priority_level'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('Mock reports created successfully for user: ' . $user->email);
    }
}
