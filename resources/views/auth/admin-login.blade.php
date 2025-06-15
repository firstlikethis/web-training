@extends('layouts.auth')

@section('title', 'Admin Login - ระบบฝึกอบรมออนไลน์')

@section('auth-title', 'เข้าสู่ระบบผู้ดูแลระบบ')
@section('auth-subtitle', 'สำหรับผู้ดูแลระบบเท่านั้น')

@section('content')
    <div class="bg-indigo-50 p-4 rounded-lg mb-6 border-l-4 border-indigo-500">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-shield-alt text-indigo-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-indigo-700">เฉพาะผู้ดูแลระบบเท่านั้นที่สามารถเข้าถึงหน้านี้ได้</p>
            </div>
        </div>
    </div>
    
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md animate-pulse" role="alert">
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
    
    <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label for="email" class="block text-gray-700 font-medium mb-2">อีเมลผู้ดูแลระบบ</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user-shield text-gray-400"></i>
                </div>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
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
                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                    required>
            </div>
            
            @error('password')
                <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
            @enderror
        </div>
        
        <div class="pt-2">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบผู้ดูแล
            </button>
        </div>
    </form>
@endsection

@section('styles')
<style>
    .auth-icon {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
</style>
@endsection