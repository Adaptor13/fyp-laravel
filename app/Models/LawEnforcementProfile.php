<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawEnforcementProfile extends Model
{
    protected $table = 'law_enforcement_profiles';

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'agency',        // e.g. PDRM, AADK
        'badge_number',
        'rank',
        'station',
        'state',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
