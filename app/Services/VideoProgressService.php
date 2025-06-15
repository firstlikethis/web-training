<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\UserProgress;

class VideoProgressService
{
    /**
     * Save user progress for a course
     *
     * @param User $user
     * @param Course $course
     * @param int $currentTime
     * @param bool $isCompleted
     * @return UserProgress
     */
    public function saveProgress(User $user, Course $course, int $currentTime, bool $isCompleted = false)
    {
        $progress = UserProgress::firstOrNew([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
        
        if ($currentTime > $progress->current_time || ($isCompleted && !$progress->is_completed)) {
            $progress->current_time = $currentTime;
            
            // คำนวณเปอร์เซ็นต์ความคืบหน้า
            if ($course->duration_seconds > 0) {
                $progress->progress_percentage = min(100, round(($currentTime / $course->duration_seconds) * 100));
            }
            
            // หากดูถึง 95% ของวิดีโอแล้ว ถือว่าเรียนจบ
            if ($currentTime >= ($course->duration_seconds * 1) || $isCompleted) {
                $progress->is_completed = true;
            }
            
            $progress->save();
        }
        
        return $progress;
    }
    
    /**
     * Increment attempt count for user progress
     *
     * @param User $user
     * @param Course $course
     * @return UserProgress
     */
    public function incrementAttempt(User $user, Course $course)
    {
        $progress = UserProgress::firstOrNew([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
        
        $progress->attempt_count += 1;
        $progress->last_attempt_at = now();
        $progress->save();
        
        return $progress;
    }
    
    /**
     * Check if user can access the course based on progress
     *
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public function canAccessCourse(User $user, Course $course)
    {
        // Admin always has access
        if ($user->isAdmin()) {
            return true;
        }
        
        $progress = UserProgress::where([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ])->first();
        
        // If no progress, then user can access (first time)
        if (!$progress) {
            return true;
        }
        
        // Check attempt limits if needed
        // For now, no limit
        return true;
    }
}