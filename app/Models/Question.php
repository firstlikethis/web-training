<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'question_text',
        'time_to_show',
        'time_limit_seconds',
        'is_active',
    ];
    
    protected $casts = [
        'time_to_show' => 'integer',
        'time_limit_seconds' => 'integer',
        'is_active' => 'boolean',
    ];
    
    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
    
    // Get correct answer
    public function getCorrectAnswer()
    {
        return $this->answers()->where('is_correct', true)->first();
    }
    
    // Active questions
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}