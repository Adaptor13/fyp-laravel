<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactQuery;
use App\Models\User;
use Carbon\Carbon;

class ContactQuerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for sample data
        $users = User::take(5)->get();
        
        $sampleQueries = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'subject' => 'General Inquiry about Child Protection Services',
                'message' => 'I would like to know more about the child protection services offered by your organization. Can you provide information about the reporting process and what happens after a report is submitted?',
                'status' => 'pending',
                'user_id' => $users->first()?->id,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'subject' => 'Technical Support Request',
                'message' => 'I am having trouble accessing the online reporting system. When I try to submit a report, I get an error message. Can someone help me resolve this issue?',
                'status' => 'in_progress',
                'user_id' => $users->skip(1)->first()?->id,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Anonymous',
                'email' => 'anonymous@example.com',
                'subject' => 'Privacy Concerns',
                'message' => 'I am concerned about the privacy of my personal information when submitting reports. How is my data protected and who has access to it?',
                'status' => 'resolved',
                'user_id' => null, // Anonymous user
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael.johnson@example.com',
                'subject' => 'Volunteer Opportunities',
                'message' => 'I am interested in volunteering with your organization to help with child protection efforts. What opportunities are available and how can I get involved?',
                'status' => 'pending',
                'user_id' => $users->skip(2)->first()?->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@example.com',
                'subject' => 'Partnership Inquiry',
                'message' => 'I represent a local community organization and would like to discuss potential partnerships for child protection initiatives. Who should I contact for partnership discussions?',
                'status' => 'in_progress',
                'user_id' => $users->skip(3)->first()?->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subHours(6),
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'subject' => 'Training Program Information',
                'message' => 'I am a social worker and would like to know about any training programs or workshops your organization offers for professionals working in child protection.',
                'status' => 'resolved',
                'user_id' => $users->skip(4)->first()?->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(12),
            ],
        ];

        foreach ($sampleQueries as $query) {
            ContactQuery::create($query);
        }
    }
}
