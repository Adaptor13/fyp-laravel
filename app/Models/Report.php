<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Report extends Model
{
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
