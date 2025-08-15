<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialWorkerProfile extends Model
{
    protected $table = 'social_worker_profiles';

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'agency_name',
        'agency_code',
        'placement_state',
        'placement_district',
        'staff_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
