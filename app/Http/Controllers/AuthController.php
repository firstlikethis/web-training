<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * แสดงหน้า login สำหรับ user
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }
    
    /**
     * ทำการ login สำหรับ user
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // ถ้ามี redirect parameter ให้ redirect ไป URL นั้น
            if ($request->has('redirect')) {
                // ตรวจสอบรูปแบบของ redirect
                $redirect = $request->redirect;
                
                // ตรวจสอบถ้า redirect มีรูปแบบ course-X
                if (preg_match('/^course-(\d+)$/', $redirect, $matches)) {
                    $courseId = $matches[1];
                    return redirect()->route('course.show', ['course' => $courseId]);
                }
                
                return redirect($redirect);
            }
            
            // ถ้าเป็น admin ให้ redirect ไปหน้า admin dashboard
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            // ถ้าเป็น user ปกติให้ redirect ไปหน้าแรก
            return redirect()->route('home');
        }
        
        return back()->withErrors([
            'email' => 'ข้อมูลที่คุณกรอกไม่ถูกต้อง',
        ])->withInput($request->except('password'));
    }
    
    /**
     * แสดงหน้า login สำหรับ admin
     */
    public function showAdminLoginForm()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.admin-login');
    }
    
    /**
     * ทำการ login สำหรับ admin
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // ตรวจสอบว่าเป็น admin หรือไม่
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            // ถ้าไม่ใช่ admin ให้ logout และแจ้งเตือน
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'คุณไม่มีสิทธิ์เข้าสู่ระบบ Admin',
            ])->withInput($request->except('password'));
        }
        
        return back()->withErrors([
            'email' => 'ข้อมูลที่คุณกรอกไม่ถูกต้อง',
        ])->withInput($request->except('password'));
    }
    
    /**
     * ทำการ logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
}