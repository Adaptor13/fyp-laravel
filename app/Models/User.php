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
        'email_verified_at'
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

    public function adminProfile()        // matches ->with(['adminProfile'])
    {
        return $this->hasOne(AdminProfile::class, 'user_id', 'id');
    }

    /**
     * Get all cases assigned to this user
     */
    public function assignedCases()
    {
        return $this->belongsToMany(Report::class, 'case_assignments', 'user_id', 'report_id')
                    ->withPivot('is_primary', 'assigned_at', 'unassigned_at')
                    ->whereNull('case_assignments.unassigned_at')
                    ->withTimestamps();
    }

    /**
     * Get all assignments for this user
     */
    public function caseAssignments()
    {
        return $this->hasMany(CaseAssignment::class, 'user_id')
                    ->whereNull('unassigned_at');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->hasPermission($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->hasAnyPermission($permissions);
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->hasAllPermissions($permissions);
    }

    /**
     * Get all permissions for the user
     */
    public function getAllPermissions()
    {
        if (!$this->role) {
            return collect();
        }
        
        return $this->role->permissions;
    }

    /**
     * Get the user's avatar URL or generate a default avatar
     */
    public function getAvatarUrl()
    {
        if ($this->profile && $this->profile->avatar_path) {
            return asset('storage/' . $this->profile->avatar_path);
        }
        
        return null;
    }

    /**
     * Get the user's initials for default avatar
     */
    public function getInitials()
    {
        $name = trim($this->name);
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 1));
    }

    /**
     * Get a consistent color for the user's default avatar
     */
    public function getAvatarColor()
    {
        $colors = [
            '#667eea', '#764ba2', // Purple gradient
            '#f093fb', '#f5576c', // Pink gradient
            '#4facfe', '#00f2fe', // Blue gradient
            '#43e97b', '#38f9d7', // Green gradient
            '#fa709a', '#fee140', // Orange gradient
            '#a8edea', '#fed6e3', // Light gradient
            '#ff9a9e', '#fecfef', // Soft pink
            '#ffecd2', '#fcb69f', // Peach
            '#a18cd1', '#fbc2eb', // Lavender
            '#fad0c4', '#ffd1ff', // Soft gradient
        ];
        
        $hash = crc32($this->name);
        $index = abs($hash) % count($colors);
        
        return $colors[$index];
    }

    /**
     * Get the background style for default avatar
     */
    public function getAvatarBackgroundStyle()
    {
        $color = $this->getAvatarColor();
        return "background: linear-gradient(135deg, {$color} 0%, " . $this->adjustColor($color, -20) . " 100%);";
    }

    /**
     * Adjust color brightness
     */
    private function adjustColor($hex, $percent)
    {
        $hex = str_replace('#', '', $hex);
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = max(0, min(255, $r + ($r * $percent / 100)));
        $g = max(0, min(255, $g + ($g * $percent / 100)));
        $b = max(0, min(255, $b + ($b * $percent / 100)));
        
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}
