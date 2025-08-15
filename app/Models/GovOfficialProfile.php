<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovOfficialProfile extends Model
{
    protected $table = 'gov_official_profiles';

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ministry',        // e.g. KPWKM, KPM
        'department',      // e.g. JKM, JPNIN
        'service_scheme',  // e.g. M, N, FA
        'grade',           // e.g. M41, N29
        'state',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
