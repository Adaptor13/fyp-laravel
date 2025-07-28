<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
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
}
