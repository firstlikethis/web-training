@extends('layouts.auth')

@section('title', 'เข้าสู่ระบบ - ระบบฝึกอบรมออนไลน์')

@section('auth-title', 'เข้าสู่ระบบ')
@section('auth-subtitle', 'ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์')

@section('content')
    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf
        
        @if(request()->has('redirect'))
            <input type="hidden" name="redirect" value="{{ request()->redirect }}">
        @endif
        
        <div>
            <label for="email" class="block text-gray-700 font-medium mb-2">อีเมล</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    required autofocus>
            </div>
            
            @error('email')
                <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password" class="block text-gray-700 font-medium mb-2">รหัสผ่าน</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="password" name="password" 
                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    required>
            </div>
            
            @error('password')
                <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
            @enderror
        </div>
        
        <div class="pt-2">
            <button type="submit" class="w-full btn-primary flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ
            </button>
        </div>
    </form>
@endsection