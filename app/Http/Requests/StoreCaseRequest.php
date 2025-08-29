<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reporter_name' => 'required|string|max:255',
            'reporter_email' => 'required|email|max:255',
            'reporter_phone' => 'nullable|string|max:20',
            'victim_age' => 'nullable|string|max:10',
            'victim_gender' => 'nullable|string|in:Male,Female,Other',
            'abuse_types' => 'nullable|array',
            'abuse_types.*' => 'nullable|string|in:Physical Abuse,Emotional Abuse,Sexual Abuse,Neglect,Exploitation',
            'incident_description' => 'required|string|max:1000',
            'incident_location' => 'required|string|max:255',
            'incident_date' => 'required|date|before_or_equal:today',
            'suspected_abuser' => 'nullable|string|max:255',
            'evidence' => 'nullable|array',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,mp4,pdf|max:20480',
            'existing_evidence' => 'nullable|array',
            'existing_evidence.*' => 'nullable|string',
            'removed_evidence' => 'nullable|string',
            'report_status' => 'required|string|in:Submitted,Under Review,In Progress,Resolved,Closed',
            'priority_level' => 'required|string|in:Low,Medium,High',
            'assignees' => 'nullable|array',
            'assignees.*' => 'nullable|string|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'reporter_name.required' => 'Reporter name is required.',
            'reporter_email.required' => 'Reporter email is required.',
            'reporter_email.email' => 'Please enter a valid email address.',
            'incident_description.required' => 'Incident description is required.',
            'incident_location.required' => 'Incident location is required.',
            'incident_date.required' => 'Incident date is required.',
            'incident_date.before_or_equal' => 'Incident date cannot be in the future.',
            'report_status.required' => 'Report status is required.',
            'priority_level.required' => 'Priority level is required.',
            'assignees.*.exists' => 'One or more selected assignees are invalid.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Filter out empty assignee values
            $assignees = $this->input('assignees');
            if ($assignees && is_array($assignees)) {
                $filteredAssignees = array_filter($assignees, function($value) {
                    return !empty($value) && $value !== '';
                });
                $this->merge(['assignees' => array_values($filteredAssignees)]);
            }
            
            // Filter out empty abuse_types values
            $abuseTypes = $this->input('abuse_types');
            if ($abuseTypes && is_array($abuseTypes)) {
                $filteredAbuseTypes = array_filter($abuseTypes, function($value) {
                    return !empty($value) && $value !== '';
                });
                $this->merge(['abuse_types' => array_values($filteredAbuseTypes)]);
            }
        });
    }
}

