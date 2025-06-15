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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        /* Custom Hamburger Menu */
        .hamburger {
            cursor: pointer;
            width: 24px;
            height: 24px;
            position: relative;
        }
        
        .hamburger-line {
            display: block;
            background: #0066cc;
            width: 24px;
            height: 2px;
            position: absolute;
            left: 0;
            transition: all 0.3s;
        }
        
        .hamburger-line:nth-child(1) {
            top: 6px;
        }
        
        .hamburger-line:nth-child(2) {
            top: 12px;
        }
        
        .hamburger-line:nth-child(3) {
            top: 18px;
        }
        
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }
        
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }
        
        /* Mobile Menu */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-out;
        }
        
        .mobile-menu.active {
            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }
        
        /* Corporate Design Elements */
        .corporate-gradient {
            background: linear-gradient(to right, #0066cc, #0099ff);
        }
        
        .corporate-shadow {
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.15);
        }
        
        /* Quiz Answer Styles */
        .quiz-answer {
            @apply border rounded-lg p-4 mb-2 cursor-pointer transition-colors;
            border-color: #e2e8f0;
        }
        
        .quiz-answer:hover {
            background-color: #f8fafc;
            border-color: #0066cc;
        }
        
        .quiz-answer.selected {
            @apply bg-blue-50 border-blue-500;
        }
        
        /* Progress Bar */
        .progress-container {
            width: 100%;
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            margin: 8px 0;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, #0066cc, #0099ff);
            border-radius: 4px;
            transition: width 0.3s ease;
            width: 0%;
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Header with Corporate Design -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-2xl font-bold text-blue-700">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <span class="hidden md:inline">ระบบฝึกอบรมออนไลน์</span>
                        <span class="md:hidden">Training</span>
                    </span>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-700 font-medium">หน้าแรก</a>
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-700 font-medium">คอร์สทั้งหมด</a>
                    
                    @auth
                        <div class="relative group">
                            <button class="flex items-center text-gray-700 hover:text-blue-700 font-medium">
                                <i class="fas fa-user-circle mr-2"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-300">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Admin Dashboard
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> ออกจากระบบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300 shadow-md">
                            <i class="fas fa-sign-in-alt mr-1"></i> เข้าสู่ระบบ
                        </a>
                    @endauth
                </nav>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="hamburger">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="mobile-menu md:hidden mt-3">
                <div class="py-2 space-y-2">
                    <a href="{{ route('home') }}" class="block py-2 px-4 text-gray-700 hover:bg-blue-50 rounded">
                        <i class="fas fa-home mr-2"></i> หน้าแรก
                    </a>
                    <a href="{{ route('home') }}" class="block py-2 px-4 text-gray-700 hover:bg-blue-50 rounded">
                        <i class="fas fa-book mr-2"></i> คอร์สทั้งหมด
                    </a>
                    
                    @auth
                        <div class="border-t border-gray-200 my-2 pt-2">
                            <div class="px-4 py-2 text-sm text-gray-500">เข้าสู่ระบบโดย: {{ auth()->user()->name }}</div>
                            
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 text-gray-700 hover:bg-blue-50 rounded">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Admin Dashboard
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 px-4 text-red-600 hover:bg-red-50 rounded">
                                    <i class="fas fa-sign-out-alt mr-2"></i> ออกจากระบบ
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="block py-2 px-4 text-blue-600 hover:bg-blue-50 rounded">
                            <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 container mx-auto mt-4 corporate-shadow" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 container mx-auto mt-4 corporate-shadow" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">ระบบฝึกอบรมออนไลน์</h3>
                    <p class="text-gray-400">ระบบฝึกอบรมออนไลน์ที่ออกแบบมาเพื่อการเรียนรู้ที่มีประสิทธิภาพ พร้อมด้วยเครื่องมือวัดผลที่แม่นยำ</p>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">ลิงก์ด่วน</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white"><i class="fas fa-chevron-right mr-2 text-xs"></i>หน้าหลัก</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white"><i class="fas fa-chevron-right mr-2 text-xs"></i>เข้าสู่ระบบ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">ติดต่อเรา</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> support@training-app.com</li>
                        <li><i class="fas fa-phone mr-2"></i> 02-XXX-XXXX</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> กรุงเทพฯ, ประเทศไทย</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} ระบบฝึกอบรมออนไลน์. สงวนลิขสิทธิ์.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Hamburger Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenuButton.classList.toggle('active');
                    mobileMenu.classList.toggle('active');
                });
            }
            
            // Setup CSRF token for AJAX requests
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