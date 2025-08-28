<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CaseAssignment extends Model
{
    use HasUuids;

    protected $fillable = [
        'report_id',
        'user_id',
        'is_primary',
        'assigned_at',
        'unassigned_at'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'assigned_at' => 'datetime',
        'unassigned_at' => 'datetime',
    ];

    /**
     * Get the report this assignment belongs to
     */
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    /**
     * Get the user this assignment belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get only active assignments
     */
    public function scopeActive($query)
    {
        return $query->whereNull('unassigned_at');
    }

    /**
     * Scope to get only primary assignments
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
