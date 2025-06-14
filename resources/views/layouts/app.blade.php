<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'ระบบฝึกอบรมออนไลน์')</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .quiz-answer {
            @apply border rounded-lg p-4 mb-2 cursor-pointer hover:bg-gray-100 transition-colors;
        }
        .quiz-answer.selected {
            @apply bg-blue-100 border-blue-500;
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">ระบบฝึกอบรมออนไลน์</a>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-gray-700">สวัสดี, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">ออกจากระบบ</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">เข้าสู่ระบบ</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
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
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-white shadow-inner mt-8 py-6">
        <div class="container mx-auto px-4">
            <p class="text-center text-gray-600 text-sm">&copy; {{ date('Y') }} ระบบฝึกอบรมออนไลน์. สงวนลิขสิทธิ์.</p>
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