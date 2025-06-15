<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard - ระบบฝึกอบรมออนไลน์')</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-gray-800 text-white shadow">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold">Admin Dashboard</a>
                
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300">สวัสดี, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-400 hover:text-red-300">ออกจากระบบ</button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Admin Navigation -->
    <nav class="bg-gray-700 text-white">
        <div class="container mx-auto px-4 py-2">
            <div class="flex space-x-6 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="py-2 px-3 @if(request()->routeIs('admin.dashboard')) bg-gray-600 @endif hover:bg-gray-600 rounded whitespace-nowrap">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="py-2 px-3 @if(request()->routeIs('admin.users.*')) bg-gray-600 @endif hover:bg-gray-600 rounded whitespace-nowrap">จัดการผู้ใช้</a>
                <a href="{{ route('admin.categories.index') }}" class="py-2 px-3 @if(request()->routeIs('admin.categories.*')) bg-gray-600 @endif hover:bg-gray-600 rounded whitespace-nowrap">จัดการหมวดหมู่</a>
                <a href="{{ route('admin.courses.index') }}" class="py-2 px-3 @if(request()->routeIs('admin.courses.*')) bg-gray-600 @endif hover:bg-gray-600 rounded whitespace-nowrap">จัดการคอร์ส</a>
                <a href="{{ route('home') }}" class="py-2 px-3 hover:bg-gray-600 rounded whitespace-nowrap" target="_blank">ดูเว็บไซต์</a>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 container mx-auto mt-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 container mx-auto mt-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">@yield('page-title', 'Dashboard')</h1>
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto px-4">
            <p class="text-center text-gray-400 text-sm">&copy; {{ date('Y') }} ระบบฝึกอบรมออนไลน์ - Admin Panel. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Setup CSRF token for AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Add CSRF token to all AJAX requests
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                // Only add for POST requests
                if (options.method === 'POST') {
                    if (!options.headers) {
                        options.headers = {};
                    }
                    
                    // Add CSRF token
                    options.headers['X-CSRF-TOKEN'] = csrfToken;
                }
                
                return originalFetch(url, options);
            };
        });
    </script>
    
    @yield('scripts')
</body>
</html>