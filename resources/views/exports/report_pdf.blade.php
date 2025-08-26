<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Protection Report - {{ substr($report->id, 0, 8) }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .report-id {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .report-id strong {
            color: #dc3545;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 16px;
            border-radius: 5px 5px 0 0;
            margin: 0;
        }
        
        .section-content {
            border: 1px solid #ddd;
            border-top: none;
            padding: 15px;
            background-color: #fafafa;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 5px 10px 5px 0;
            color: #555;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin: 2px;
        }
        
        .badge-primary { background-color: #007bff; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        
        .description {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .timestamp {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body { margin: 0; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHILD PROTECTION REPORT</h1>
        <p>Confidential Document - For Official Use Only</p>
    </div>
    
    <div class="report-id">
        <strong>Report ID:</strong> {{ $report->id }} | 
        <strong>Status:</strong> 
        <span class="badge badge-{{ $report->report_status === 'Submitted' ? 'primary' : ($report->report_status === 'Under Review' ? 'warning' : ($report->report_status === 'In Progress' ? 'info' : ($report->report_status === 'Resolved' ? 'success' : 'secondary'))) }}">
            {{ $report->report_status ?? 'Submitted' }}
        </span> |
        <strong>Priority:</strong> 
        <span class="badge badge-{{ $report->priority_level === 'High' ? 'danger' : ($report->priority_level === 'Medium' ? 'warning' : 'success') }}">
            {{ $report->priority_level ?? 'Medium' }}
        </span>
    </div>
    
    <!-- Reporter Information -->
    <div class="section">
        <h3 class="section-title">Reporter Information</h3>
        <div class="section-content">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $report->reporter_name ?? 'Anonymous' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $report->reporter_email ?? 'Not provided' }}</div>
                </div>
                @if($report->reporter_phone)
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $report->reporter_phone }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Victim Information -->
    <div class="section">
        <h3 class="section-title">Victim Information</h3>
        <div class="section-content">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Age:</div>
                    <div class="info-value">{{ $report->victim_age ?? 'Not specified' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gender:</div>
                    <div class="info-value">
                        @if($report->victim_gender)
                            <span class="badge badge-info">{{ ucfirst($report->victim_gender) }}</span>
                        @else
                            Not specified
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Incident Information -->
    <div class="section">
        <h3 class="section-title">Incident Information</h3>
        <div class="section-content">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Location:</div>
                    <div class="info-value">{{ $report->incident_location }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</div>
                </div>
                @if($report->suspected_abuser)
                <div class="info-row">
                    <div class="info-label">Suspected Abuser:</div>
                    <div class="info-value">{{ $report->suspected_abuser }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Abuse Types -->
    <div class="section">
        <h3 class="section-title">Types of Abuse Reported</h3>
        <div class="section-content">
            @if(count($abuseTypes) > 0)
                @foreach($abuseTypes as $type)
                    <span class="badge badge-warning">{{ $type }}</span>
                @endforeach
            @else
                <p style="color: #666; font-style: italic;">No specific abuse types specified</p>
            @endif
        </div>
    </div>
    
    <!-- Incident Description -->
    <div class="section">
        <h3 class="section-title">Incident Description</h3>
        <div class="section-content">
            <div class="description">
                {{ $report->incident_description }}
            </div>
        </div>
    </div>
    
    <!-- Evidence -->
    @if(count($evidence) > 0)
    <div class="section">
        <h3 class="section-title">Evidence Attached</h3>
        <div class="section-content">
            <p><strong>Number of files:</strong> {{ count($evidence) }}</p>
            <p style="color: #666; font-size: 14px;">
                <em>Evidence files are stored securely and can be accessed by authorized personnel only.</em>
            </p>
        </div>
    </div>
    @endif
    
    <!-- Timestamps -->
    <div class="timestamp">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Report Submitted:</div>
                <div class="info-value">{{ $report->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Last Updated:</div>
                <div class="info-value">{{ $report->updated_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p><strong>IMPORTANT:</strong> This document contains sensitive information and should be handled with appropriate confidentiality.</p>
        <p>Generated on: {{ now()->format('d M Y, H:i') }}</p>
        <p>Child Protection System - Confidential Report</p>
    </div>
</body>
</html>
