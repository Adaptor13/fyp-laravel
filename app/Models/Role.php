<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('slug', $permission)->exists();
        }
        
        return $this->permissions()->where('id', $permission->id)->exists();
    }

    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        
        return $this->permissions()->whereIn('slug', $permissions)->exists();
    }

    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        
        $count = $this->permissions()->whereIn('slug', $permissions)->count();
        return $count === count($permissions);
    }

    public function getPrettyNameAttribute()
    {
        return match ($this->name) {
            'admin'            => 'Administrator',
            'social_worker'    => 'Social Worker',
            'public_user'      => 'Public User',
            'healthcare'       => 'Healthcare Worker',
            'law_enforcement'  => 'Law Enforcement',
            'gov_official'     => 'Government Official',
            default            => ucfirst(str_replace('_', ' ', $this->name)),
        };
    }
}
