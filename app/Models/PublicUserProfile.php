<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicUserProfile extends Model
{
    protected $table = 'public_user_profiles';

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'display_name',
        'allow_contact',
    ];

    protected $casts = [
        'allow_contact' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
