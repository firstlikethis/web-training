<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];
    
    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    // Relationships
    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }
    
    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}