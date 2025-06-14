<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Question;
use App\Models\UserProgress;
use App\Models\UserAnswer;

class AdminController extends Controller
{
    /**
     * แสดงหน้า Dashboard สำหรับ Admin
     */
    public function index()
    {
        // รวบรวมข้อมูลสถิติสำหรับแสดงใน Dashboard
        $stats = [
            'users_count' => User::where('role', 'user')->count(),
            'courses_count' => Course::count(),
            'questions_count' => Question::count(),
            'completed_courses' => UserProgress::where('is_completed', true)->count(),
        ];
        
        // 5 User ล่าสุดที่เข้าสู่ระบบ
        $recent_users = User::where('role', 'user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        
        // 5 Course ที่มีคนเรียนมากที่สุด
        $popular_courses = Course::withCount(['userProgress' => function($query) {
                $query->where('is_completed', true);
            }])
            ->orderBy('user_progress_count', 'desc')
            ->take(5)
            ->get();
        
        // จำนวนการตอบคำถามในแต่ละวันในช่วง 7 วันที่ผ่านมา
        $answers_per_day = UserAnswer::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
        
        return view('admin.dashboard', compact('stats', 'recent_users', 'popular_courses', 'answers_per_day'));
    }
}