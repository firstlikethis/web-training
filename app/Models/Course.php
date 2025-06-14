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
        'video_url',
        'duration_seconds',
        'is_active',
        'status',
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
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'published');
    }
    
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    // Check if course is in draft state
    public function isDraft()
    {
        return $this->status === 'draft';
    }
    
    // Check if course is published
    public function isPublished()
    {
        return $this->status === 'published';
    }
}