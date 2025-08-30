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
                'evidence' => ['photos', 'witness_statement'],
                'confirmed_truth' => true,
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
                'evidence' => ['photos', 'medical_report'],
                'confirmed_truth' => true,
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
                'evidence' => ['victim_statement', 'counselor_report'],
                'confirmed_truth' => true,
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
                'evidence' => ['teacher_report', 'school_records'],
                'confirmed_truth' => false,
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
                'evidence' => ['psychological_evaluation'],
                'confirmed_truth' => true,
            ],
            [
                'victim_age' => '10',
                'victim_gender' => 'Male',
                'abuse_types' => ['Physical Abuse'],
                'incident_description' => 'Child arrived at school with visible injuries. Claims they were caused by a fall, but injuries are suspicious.',
                'incident_location' => 'Shah Alam, Selangor',
                'incident_date' => '2024-02-10',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'Under Review',
                'priority_level' => 'High',
                'evidence' => ['medical_examination', 'school_photos'],
                'confirmed_truth' => true,
            ],
            [
                'victim_age' => '16',
                'victim_gender' => 'Female',
                'abuse_types' => ['Sexual Abuse', 'Emotional Abuse'],
                'incident_description' => 'Teenager disclosed online grooming and inappropriate contact. Evidence of manipulation and threats.',
                'incident_location' => 'Cyberjaya, Selangor',
                'incident_date' => '2024-02-12',
                'suspected_abuser' => 'Online Predator',
                'report_status' => 'In Progress',
                'priority_level' => 'High',
                'evidence' => ['digital_evidence', 'screenshots', 'police_report'],
                'confirmed_truth' => true,
            ],
            [
                'victim_age' => '7',
                'victim_gender' => 'Male',
                'abuse_types' => ['Neglect', 'Physical Abuse'],
                'incident_description' => 'Child found wandering alone at night. Shows signs of malnutrition and untreated medical conditions.',
                'incident_location' => 'Kuantan, Pahang',
                'incident_date' => '2024-02-15',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'Submitted',
                'priority_level' => 'High',
                'evidence' => ['police_report', 'medical_records'],
                'confirmed_truth' => true,
            ],
            [
                'victim_age' => '14',
                'victim_gender' => 'Female',
                'abuse_types' => ['Emotional Abuse'],
                'incident_description' => 'Student exhibiting signs of severe anxiety and depression. Reports constant criticism and unrealistic expectations.',
                'incident_location' => 'Petaling Jaya, Selangor',
                'incident_date' => '2024-02-18',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'Under Review',
                'priority_level' => 'Medium',
                'evidence' => ['counselor_report', 'school_records'],
                'confirmed_truth' => false,
            ],
            [
                'victim_age' => '11',
                'victim_gender' => 'Male',
                'abuse_types' => ['Physical Abuse', 'Emotional Abuse'],
                'incident_description' => 'Child reports being hit with objects and verbally abused. Shows fear of authority figures.',
                'incident_location' => 'Seremban, Negeri Sembilan',
                'incident_date' => '2024-02-20',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'In Progress',
                'priority_level' => 'Medium',
                'evidence' => ['victim_statement', 'witness_accounts'],
                'confirmed_truth' => true,
            ],
            [
                'victim_age' => '9',
                'victim_gender' => 'Female',
                'abuse_types' => ['Neglect'],
                'incident_description' => 'Child frequently absent from school. When present, appears unkempt and hungry.',
                'incident_location' => 'Alor Setar, Kedah',
                'incident_date' => '2024-02-22',
                'suspected_abuser' => 'Parent/Guardian',
                'report_status' => 'Submitted',
                'priority_level' => 'Medium',
                'evidence' => ['attendance_records', 'teacher_observations'],
                'confirmed_truth' => true,
            ],
            [
                'victim_age' => '17',
                'victim_gender' => 'Male',
                'abuse_types' => ['Sexual Abuse'],
                'incident_description' => 'Young adult disclosed historical abuse by family member. Seeking support and legal guidance.',
                'incident_location' => 'Kuching, Sarawak',
                'incident_date' => '2024-02-25',
                'suspected_abuser' => 'Family Member',
                'report_status' => 'Resolved',
                'priority_level' => 'Low',
                'evidence' => ['victim_statement', 'counselor_report'],
                'confirmed_truth' => true,
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
                'abuse_types' => $reportData['abuse_types'],
                'incident_description' => $reportData['incident_description'],
                'incident_location' => $reportData['incident_location'],
                'incident_date' => $reportData['incident_date'],
                'suspected_abuser' => $reportData['suspected_abuser'],
                'evidence' => $reportData['evidence'],
                'confirmed_truth' => $reportData['confirmed_truth'],
                'report_status' => $reportData['report_status'],
                'priority_level' => $reportData['priority_level'],
                'last_updated_by' => $user->id,
                'status_updated_at' => now()->subDays(rand(1, 15)),
                'last_message_at' => now()->subDays(rand(0, 10)),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('Mock reports created successfully for user: ' . $user->email);
    }
}
