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
        'progress_percentage',
    ];
    
    protected $casts = [
        'current_time' => 'integer',
        'is_completed' => 'boolean',
        'attempt_count' => 'integer',
        'last_attempt_at' => 'datetime',
        'progress_percentage' => 'integer',
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
        // ถ้าเรียนจบแล้ว ควรแสดง 100% เสมอ
        if ($this->is_completed) {
            return 100;
        }
        
        if ($this->course->duration_seconds == 0) {
            return 0;
        }
        
        return min(100, round(($this->current_time / $this->course->duration_seconds) * 100));
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            if ($model->is_completed) {
                $model->progress_percentage = 100;
            }
        });
    }
}