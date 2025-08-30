<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'user_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that submitted the contact query.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Validation rules for contact queries
     */
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ];
    }

    /**
     * Validation rules for admin updates
     */
    public static function adminRules()
    {
        return [
            'status' => 'required|in:pending,in_progress,resolved',
        ];
    }

    /**
     * Scope for pending queries
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for in progress queries
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for resolved queries
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'in_progress' => 'bg-info',
            'resolved' => 'bg-success',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status display text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            default => ucfirst($this->status)
        };
    }
}
