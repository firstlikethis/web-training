@extends('layouts.auth')

@section('title', 'Admin Login - ระบบฝึกอบรมออนไลน์')

@section('auth-title', 'Admin Login')
@section('auth-subtitle', 'เข้าสู่ระบบสำหรับผู้ดูแลระบบ')

@section('content')
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 text-white text-2xl mb-4">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">ADMIN LOGIN</h1>
            <p class="text-gray-600 mt-2">เข้าสู่ระบบสำหรับผู้ดูแลระบบ</p>
        </div>
        
        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent"
                        required autofocus>
                </div>
                
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" 
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent"
                        required>
                </div>
                
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <button type="submit" class="w-full bg-gray-800 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ Admin
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 text-sm flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i> กลับไปหน้าหลัก
            </a>
        </div>
    </div>
@endsection

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
</style>
@endsection