<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getKeyType()
    {
        return 'string';
    }

    public $incrementing = false;

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

       public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function publicUserProfile()
    {
        return $this->hasOne(PublicUserProfile::class, 'user_id', 'id');
    }

    public function socialWorkerProfile()
    {
        return $this->hasOne(SocialWorkerProfile::class, 'user_id', 'id');
    }

    public function healthcareProfile()
    {
        return $this->hasOne(HealthcareProfile::class, 'user_id', 'id');
    }

    public function lawEnforcementProfile()
    {
        return $this->hasOne(LawEnforcementProfile::class, 'user_id', 'id');
    }

    public function govOfficialProfile()
    {
        return $this->hasOne(GovOfficialProfile::class, 'user_id', 'id');
    }


}
