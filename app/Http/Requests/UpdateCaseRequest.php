<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaseRequest extends FormRequest
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
            'abuse_types.*' => 'string|in:Physical Abuse,Emotional Abuse,Sexual Abuse,Neglect,Exploitation',
            'incident_description' => 'required|string|max:1000',
            'incident_location' => 'required|string|max:255',
            'incident_date' => 'required|date|before_or_equal:today',
            'suspected_abuser' => 'nullable|string|max:255',
            'evidence' => 'nullable|array',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,mp4,pdf|max:20480',
            'report_status' => 'required|string|in:Submitted,Under Review,In Progress,Resolved,Closed',
            'priority_level' => 'required|string|in:Low,Medium,High',
            'assigned_to' => 'nullable|string|exists:users,id',
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
            'assigned_to.exists' => 'Selected assignee does not exist.',
        ];
    }
}

