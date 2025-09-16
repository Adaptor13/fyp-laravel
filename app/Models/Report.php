<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\LogsCaseHistory;


class Report extends Model
{
    use LogsCaseHistory;
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($report) {
            if (empty($report->id)) {
                $report->id = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'user_id',      
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'victim_age',
        'victim_gender',
        'abuse_types',
        'incident_description',
        'incident_location',
        'incident_date',
        'suspected_abuser',
        'evidence',
        'confirmed_truth',
        'report_status',
        'priority_level',
        'last_updated_by',
        'status_updated_at',
        'last_message_at',
    ];

    protected $casts = [
        'abuse_types' => 'array',
        'evidence' => 'array',
        'last_message_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who last updated this report
     */
    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    /**
     * Get all assignees for this case
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'case_assignments', 'report_id', 'user_id')
                    ->withPivot('is_primary', 'assigned_at', 'unassigned_at')
                    ->whereNull('case_assignments.unassigned_at')
                    ->withTimestamps();
    }



    /**
     * Get all active assignments for this case
     */
    public function assignments()
    {
        return $this->hasMany(CaseAssignment::class, 'report_id')
                    ->whereNull('unassigned_at');
    }

    /**
     * Scope to get cases assigned to a specific user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->whereHas('assignees', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Get the case history for this report
     */
    public function history()
    {
        return $this->hasMany(CaseHistory::class, 'report_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get all messages for this case
     */
    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable')->orderBy('created_at', 'desc');
    }
}
