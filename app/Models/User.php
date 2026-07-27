<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'total_points',
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
            'total_points' => 'integer',
        ];
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function challengeProgress(): HasMany
    {
        return $this->hasMany(UserChallengeProgress::class);
    }

    public function createdChallenges(): HasMany
    {
        return $this->hasMany(Challenge::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
