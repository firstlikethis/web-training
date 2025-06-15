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
        
        // อัปเดตเวลาปัจจุบันเสมอ เพื่อให้แน่ใจว่ามีการบันทึกความคืบหน้า
        $progress->current_time = $currentTime;
        
        // กำหนดสถานะเรียนจบถ้า isCompleted เป็น true หรือดูถึงความยาววิดีโอ
        if ($isCompleted || ($course->duration_seconds > 0 && $currentTime >= $course->duration_seconds)) {
            $progress->is_completed = true;
        }
        
        // คำนวณเปอร์เซ็นต์ความคืบหน้า
        if ($progress->is_completed) {
            // ถ้าเรียนจบแล้ว ให้ตั้งค่าเป็น 100% เสมอ
            $progress->progress_percentage = 100;
        } else if ($course->duration_seconds > 0) {
            // คำนวณปกติถ้ายังไม่จบและความยาววิดีโอไม่ใช่ 0
            $progress->progress_percentage = min(100, round(($currentTime / $course->duration_seconds) * 100));
        } else {
            // ป้องกันกรณี duration_seconds เป็น 0
            $progress->progress_percentage = 0;
        }
        
        $progress->save();
        
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