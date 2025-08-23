<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthcareProfile extends Model
{
    protected $table = 'healthcare_profiles';

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'profession',       
        'apc_expiry',
        'facility_name',
        'moh_facility_code',
        'state',
    ];

    protected $casts = [
        'apc_expiry' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}