<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;
    
    protected $table = 'user_progress';
    
    protected $fillable = [
        'user_id',
        'course_id',
        'current_time',
        'is_completed',
        'attempt_count',
        'last_attempt_at',
    ];
    
    protected $casts = [
        'current_time' => 'integer',
        'is_completed' => 'boolean',
        'attempt_count' => 'integer',
        'last_attempt_at' => 'datetime',
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Check if user has completed the course
    public function isCompleted()
    {
        return $this->is_completed;
    }
    
    // Get completion percentage
    public function getCompletionPercentage()
    {
        if ($this->course->duration_seconds == 0) {
            return 0;
        }
        
        return min(100, round(($this->current_time / $this->course->duration_seconds) * 100));
    }
}