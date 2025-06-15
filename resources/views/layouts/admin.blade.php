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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #4f46e5 0%, #7e3af2 100%);
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            border-radius: 0.5rem;
            margin: 0.25rem 0.75rem;
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Card Styles */
        .card {
            border-radius: 1rem;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-5px);
        }
        
        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7e3af2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        .btn-secondary {
            background: #e5e7eb;
            color: #4b5563;
        }
        
        .btn-secondary:hover {
            background: #d1d5db;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        /* Table Styles */
        table {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        table th {
            background-color: #f3f4f6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #10b981;
            color: white;
        }
        
        .badge-warning {
            background-color: #f59e0b;
            color: white;
        }
        
        .badge-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .badge-info {
            background-color: #3b82f6;
            color: white;
        }
        
        /* Progress Bar */
        .progress-bar {
            height: 0.5rem;
            border-radius: 9999px;
            background: #e5e7eb;
            overflow: hidden;
        }
        
        .progress-value {
            height: 100%;
            border-radius: 9999px;
            background: linear-gradient(90deg, #4f46e5 0%, #7e3af2 100%);
        }
        
        /* Mobile Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
        }
        
        /* Custom Animation for Notifications */
        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .notification {
            animation: slideDown 0.3s ease-out forwards;
        }
        
        /* Hamburger Menu */
        .hamburger {
            width: 24px;
            height: 24px;
            position: relative;
            cursor: pointer;
        }
        
        .hamburger span {
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: white;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .hamburger span:nth-child(1) {
            top: 0;
        }
        
        .hamburger span:nth-child(2) {
            top: 10px;
        }
        
        .hamburger span:nth-child(3) {
            top: 20px;
        }
        
        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(7px, 7px);
        }
        
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="sidebar w-64 md:shadow hidden md:block fixed md:relative inset-y-0 left-0 z-30">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-white">
                        <span class="flex items-center">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            <span>ADMIN PANEL</span>
                        </span>
                    </a>
                </div>
                
                <!-- Sidebar Menu -->
                <div class="flex-1 overflow-y-auto py-4">
                    <nav class="mt-2 px-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-link flex items-center px-4 py-3 text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <div class="mt-6 px-4 text-xs font-semibold text-white/70 uppercase tracking-wider">
                            จัดการข้อมูล
                        </div>
                        
                        <a href="{{ route('admin.users.index') }}" 
                           class="nav-link flex items-center px-4 py-3 mt-2 text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5 h-5 mr-3"></i>
                            <span>จัดการผู้ใช้</span>
                        </a>
                        
                        <a href="{{ route('admin.categories.index') }}" 
                           class="nav-link flex items-center px-4 py-3 mt-2 text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-folder w-5 h-5 mr-3"></i>
                            <span>จัดการหมวดหมู่</span>
                        </a>
                        
                        <a href="{{ route('admin.courses.index') }}" 
                           class="nav-link flex items-center px-4 py-3 mt-2 text-white {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                            <i class="fas fa-book w-5 h-5 mr-3"></i>
                            <span>จัดการคอร์ส</span>
                        </a>
                        
                        <div class="mt-6 px-4 text-xs font-semibold text-white/70 uppercase tracking-wider">
                            เมนูอื่นๆ
                        </div>
                        
                        <a href="{{ route('home') }}" target="_blank" 
                           class="nav-link flex items-center px-4 py-3 mt-2 text-white">
                            <i class="fas fa-home w-5 h-5 mr-3"></i>
                            <span>หน้าเว็บไซต์</span>
                        </a>
                    </nav>
                </div>
                
                <!-- Sidebar Footer -->
                <div class="border-t border-white/10 p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-white bg-white/10 rounded-lg hover:bg-white/20 transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>ออกจากระบบ</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content flex-1 w-full md:ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center md:hidden">
                            <button id="hamburger" class="p-2 rounded-md text-gray-600 hover:text-gray-900 focus:outline-none">
                                <div class="w-6 h-6 relative">
                                    <span class="hamburger-line"></span>
                                    <span class="hamburger-line"></span>
                                    <span class="hamburger-line"></span>
                                </div>
                            </button>
                        </div>
                        
                        <div class="flex items-center">
                            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="hidden md:block mr-3">
                                <span class="text-sm text-gray-600">สวัสดี, {{ auth()->user()->name }}</span>
                            </div>
                            
                            <div class="relative" x-data="{ open: false }">
                                <button id="user-menu-button" class="flex text-sm rounded-full focus:outline-none">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center text-white font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                </button>
                                
                                <div id="user-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-home mr-2"></i> หน้าเว็บไซต์
                                    </a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> ออกจากระบบ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="notification max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" class="close-notification inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none">
                                        <span class="sr-only">ปิด</span>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="notification max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" class="close-notification inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none">
                                        <span class="sr-only">ปิด</span>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Page Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white rounded-lg shadow mx-4 my-8">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} ระบบฝึกอบรมออนไลน์. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black opacity-50 z-20 hidden"></div>
    
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.querySelector('.sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');
            
            if (hamburger && sidebar && mobileOverlay) {
                hamburger.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                    mobileOverlay.classList.toggle('hidden');
                });
                
                mobileOverlay.addEventListener('click', function() {
                    sidebar.classList.add('hidden');
                    mobileOverlay.classList.add('hidden');
                });
            }
            
            // User dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Close notifications
            const closeButtons = document.querySelectorAll('.close-notification');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const notification = this.closest('.notification');
                    notification.classList.add('opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                });
            });
            
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