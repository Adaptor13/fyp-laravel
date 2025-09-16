# Anonymous Reporting with Tracking Feature

## Backend Implementation

```php
<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'evidence' => 'nullable|array|max:5',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,mp4,pdf|max:20480',
            'confirmed_truth' => 'accepted'
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
}
```

**Figure 1: Anonymous Reporting Backend Code Snippet**

This code implements the `store()` method in the `ReportController` class that handles anonymous report submissions. The method first validates incoming request data using Laravel's `$request->validate()` method with specific rules: `reporter_name` is nullable with a 255-character limit, `victim_age` is required as a string with 10-character limit, `abuse_types` must be an array with at least one element where each item must match predefined abuse types, `incident_date` must be a valid date not in the future using `before_or_equal:today`, and `evidence` files are limited to 5 files with specific MIME types and 20MB size limit. The code then processes file uploads using `$request->hasFile('evidence')` to check for uploaded files and `$file->store('evidence', 'public')` to store each file in the public disk. The `Report::create()` method creates a new report record with `Str::uuid()` generating a unique identifier, `auth()->check() ? auth()->id() : null` conditionally linking to a user account, and the null coalescing operator (`??`) setting default values for anonymous submissions like `'anonymous'` for name and `'anonymous@gmail.com'` for email. The method returns a redirect with a success message using `redirect()->back()->with('success', ...)`.

## Frontend Implementation

```html
<div class="card">
    <div class="card-header">
        <h5>Report a Child Protection Concern</h5>
        <p class="text-muted mb-0">Your identity is optional. All reports are confidential and protected under PDPA.</p>
        <p class="text-muted mb-0"><small>* Required fields</small></p>
    </div>
    <div class="card-body">
        <form action="{{ route('report.store') }}" method="POST" class="report-form" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Reporter Identity (Optional) -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reporter_name" class="form-label">Your Name (Optional)</label>
                        <input type="text" name="reporter_name" id="reporter_name" class="form-control" placeholder="Enter Your Name" value="{{ old('reporter_name', $prefillName) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reporter_email" class="form-label">Your Email (Optional)</label>
                        <input type="email" name="reporter_email" id="reporter_email" class="form-control" placeholder="Enter Your Email" value="{{ old('reporter_email', $prefillEmail) }}" {{ $readonlyEmail ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Your Contact Number (Optional)</label>
                        <input type="tel" name="reporter_phone" id="phone" class="form-control" placeholder="Enter Your Phone" value="{{ old('reporter_phone', $prefillPhone) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="victim_age" class="form-label">Victim's Approximate Age *</label>
                        <input type="number" name="victim_age" id="victim_age" class="form-control" min="0" max="17" required value="{{ old('victim_age') }}" oninvalid="this.setCustomValidity('Please enter an age between 0 and 17.')" oninput="this.setCustomValidity('')">
                    </div>
                </div>

                <!-- Upload Evidence -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="evidence" class="form-label">Upload Evidence (Optional)</label>
                        <input type="file" name="evidence[]" id="evidence" class="form-control" accept="image/*,video/*,application/pdf" multiple onchange="validateFileUpload(this)">
                        <small class="form-text text-muted">You may upload photos, videos, or documents. Maximum 5 files allowed.</small>
                        <div id="file-validation-message" class="text-danger mt-1" style="display: none;"></div>
                    </div>
                </div>

                <!-- Consent Confirmation -->
                <div class="col-12">
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="confirmed_truth" id="confirmed_truth" class="form-check-input" required>
                        <label class="form-check-label" for="confirmed_truth">I confirm that the information provided is accurate to the best of my knowledge. *</label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="ti ti-send"></i> Submit Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
```

**Figure 2: Anonymous Reporting UI Component**

This code implements a Bootstrap card-based form layout for anonymous reporting with privacy-focused design elements. The form uses `action="{{ route('report.store') }}"` to submit to the backend route, `method="POST"` for data transmission, and `enctype="multipart/form-data"` to handle file uploads. The `@csrf` directive generates a CSRF token for security. Form fields use Bootstrap's grid system with `col-md-6` classes for responsive layout. Personal information fields include `name="reporter_name"` with `placeholder="Enter Your Name"` and `value="{{ old('reporter_name', $prefillName) }}"` to preserve form data on validation errors. The victim age field uses `type="number"` with `min="0" max="17"` attributes and `oninvalid="this.setCustomValidity('...')"` for custom validation messages. The evidence upload field uses `name="evidence[]"` for multiple file selection, `accept="image/*,video/*,application/pdf"` to restrict file types, and `onchange="validateFileUpload(this)"` to trigger client-side validation. The consent checkbox uses `name="confirmed_truth"` with `required` attribute to enforce mandatory agreement before submission.

## JavaScript Implementation

```javascript
function validateFileUpload(input) {
    const maxFiles = 5;
    const files = input.files;
    const messageDiv = document.getElementById('file-validation-message');
    
    if (files.length > maxFiles) {
        messageDiv.textContent = `Maximum ${maxFiles} files allowed. You selected ${files.length} files. Please select only ${maxFiles} files or fewer.`;
        messageDiv.style.display = 'block';
        messageDiv.className = 'text-danger mt-1';
        
        // Clear the input to prevent submission
        input.value = '';
        return false;
    } else if (files.length > 0) {
        // Show success message for valid selection
        messageDiv.textContent = `${files.length} file(s) selected. Maximum ${maxFiles} files allowed.`;
        messageDiv.style.display = 'block';
        messageDiv.className = 'text-success mt-1';
        return true;
    } else {
        // No files selected
        messageDiv.style.display = 'none';
        return true;
    }
}

function validateIncidentDate(input) {
    const selectedDate = new Date(input.value);
    const today = new Date();
    today.setHours(23, 59, 59, 999); // Set to end of today to allow today's date
    
    if (selectedDate > today) {
        alert('Please select a date that is not in the future.');
        input.value = ''; // Clear the invalid date
        input.focus();
        return false;
    }
    return true;
}

// Form validation before submit
document.querySelector('.report-form').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('evidence');
    const dateInput = document.getElementById('incident_date');
    const abuseTypeCheckboxes = document.querySelectorAll('input[name="abuse_types[]"]:checked');
    const messageDiv = document.getElementById('file-validation-message');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    // Check if form is already being submitted
    if (submitBtn.disabled) {
        e.preventDefault();
        return false;
    }
    
    // Validate abuse types selection
    if (abuseTypeCheckboxes.length === 0) {
        alert('Please select at least one type of abuse.');
        e.preventDefault();
        return false;
    }
    
    // Validate file upload - check file count
    if (fileInput.files.length > 5) {
        messageDiv.textContent = 'Maximum 5 files allowed. Please select fewer files before submitting.';
        messageDiv.style.display = 'block';
        messageDiv.className = 'text-danger mt-1';
        e.preventDefault();
        return false;
    }
    
    // Validate file upload - check individual file validation
    if (!validateFileUpload(fileInput)) {
        e.preventDefault();
        return false;
    }
    
    // Validate incident date
    if (dateInput.value && !validateIncidentDate(dateInput)) {
        e.preventDefault();
        return false;
    }
    
    // If all validations pass, disable submit button to prevent double submission
    submitBtn.disabled = true;
    submitText.textContent = 'Submitting...';
    submitSpinner.style.display = 'inline-block';
});
```

**Figure 3: Anonymous Reporting JavaScript Implementation**

This code implements client-side validation and form handling for the anonymous reporting system. The `validateFileUpload(input)` function checks if the number of selected files exceeds the maximum limit of 5, displays error messages using `messageDiv.textContent` and `messageDiv.className`, and clears the input using `input.value = ''` if validation fails. The `validateIncidentDate(input)` function creates Date objects from the input value and current date, uses `setHours(23, 59, 59, 999)` to set the current date to end of day, and prevents future date selection by clearing the input and showing an alert. The form submit event listener uses `document.querySelector('.report-form').addEventListener('submit', ...)` to intercept form submission, validates abuse type selection using `document.querySelectorAll('input[name="abuse_types[]"]:checked')`, checks file count using `fileInput.files.length`, and prevents double submission by disabling the submit button and updating button text to 'Submitting...' with a spinner.
