@extends('layouts.auth')

@section('title', 'Admin Login - ระบบฝึกอบรมออนไลน์')

@section('auth-title', 'Admin Login')
@section('auth-subtitle', 'เข้าสู่ระบบสำหรับผู้ดูแลระบบ')

@section('content')
    <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label for="email" class="block text-gray-700 font-medium mb-1">อีเมล</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required autofocus>
            
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password" class="block text-gray-700 font-medium mb-1">รหัสผ่าน</label>
            <input type="password" id="password" name="password" 
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <button type="submit" class="w-full bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-700">
                เข้าสู่ระบบ Admin
            </button>
        </div>
    </form>
@endsection