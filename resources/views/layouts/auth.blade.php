<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'เข้าสู่ระบบ - ระบบฝึกอบรมออนไลน์')</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <!-- Login Form Container -->
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">@yield('auth-title', 'เข้าสู่ระบบ')</h1>
            <p class="text-gray-600 mt-2">@yield('auth-subtitle', 'ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์')</p>
        </div>
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        @yield('content')
        
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">กลับไปหน้าหลัก</a>
        </div>
    </div>
    
    @yield('scripts')
</body>
</html>