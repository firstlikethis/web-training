<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'video_path',
        'duration_seconds',
        'is_active',
    ];
    
    protected $casts = [
        'duration_seconds' => 'integer',
        'is_active' => 'boolean',
    ];
    
    // Relationships
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }
    
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
    
    // Active courses
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}