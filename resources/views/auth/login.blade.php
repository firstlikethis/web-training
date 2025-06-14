@extends('layouts.auth')

@section('title', 'เข้าสู่ระบบ - ระบบฝึกอบรมออนไลน์')

@section('auth-title', 'เข้าสู่ระบบ')
@section('auth-subtitle', 'ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์')

@section('content')
    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        
        @if(request()->has('redirect'))
            <input type="hidden" name="redirect" value="{{ request()->redirect }}">
        @endif
        
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
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                เข้าสู่ระบบ
            </button>
        </div>
    </form>
@endsection