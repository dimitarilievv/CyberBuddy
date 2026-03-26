<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'date_of_birth',
        'parent_id',
        'is_active',
        'last_login_at',
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
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function createdModules()
    {
        return $this->hasMany(Module::class, 'author_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at')->withTimestamps();
    }

    public function isChild(): bool
    {
        return $this->role === 'child';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class, 'user_id');
    }
    public function userProgress()
    {
        return $this->hasMany(\App\Models\UserProgress::class, 'user_id');
    }
    public function leaderboard()
    {
        return $this->hasOne(\App\Models\Leaderboard::class);
    }
}
