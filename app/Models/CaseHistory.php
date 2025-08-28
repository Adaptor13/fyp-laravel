<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class CaseHistory extends Model
{
    use HasUuids;

    protected $table = 'case_history';

    protected $fillable = [
        'report_id',
        'user_id',
        'action',
        'details',
        'changes',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($history) {
            if (empty($history->id)) {
                $history->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the report this history entry belongs to
     */
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    /**
     * Get the user who made this change
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get history for a specific case
     */
    public function scopeForCase($query, $reportId)
    {
        return $query->where('report_id', $reportId);
    }

    /**
     * Scope to get history by action type
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
