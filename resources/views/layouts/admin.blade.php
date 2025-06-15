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
            min-height: 100vh;
        }
        
        /* Layout Styles */
        .layout-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #4f46e5 0%, #7e3af2 100%);
            width: 16rem;
            transition: all 0.3s;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
            overflow-y: auto;
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
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 16rem;
            width: calc(100% - 16rem);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
                width: 16rem;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .page-content {
                padding-top: 5rem;
            }
        }
        
        /* Header for mobile */
        .mobile-header {
            display: none;
        }
        
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 30;
                height: 4rem;
                background-color: #fff;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                padding: 0 1rem;
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
            z-index: 50;
        }
        
        .hamburger span {
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #4f46e5;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .hamburger span:nth-child(1) {
            top: 2px;
        }
        
        .hamburger span:nth-child(2) {
            top: 10px;
        }
        
        .hamburger span:nth-child(3) {
            top: 18px;
        }
        
        .hamburger.open span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        
        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.open span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
        
        /* Page content padding */
        .page-content {
            flex: 1;
            padding: 1.5rem;
            overflow-x: hidden;
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 35;
            display: none;
        }
        
        @media (max-width: 768px) {
            .sidebar-overlay.open {
                display: block;
            }
        }
        
        /* Footer positioning */
        .main-footer {
            margin-top: auto;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="layout-container">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
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
        
        <!-- Sidebar Overlay (for mobile) -->
        <div id="sidebar-overlay" class="sidebar-overlay"></div>
        
        <!-- Mobile Header -->
        <header class="mobile-header items-center justify-between">
            <button id="hamburger-btn" class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            
            <div class="flex items-center">
                <div class="relative" id="mobile-user-menu">
                    <button class="flex text-sm rounded-full focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center text-white font-medium">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </button>
                    
                    <div id="mobile-user-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
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
        </header>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-20 hidden md:block">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="mr-3">
                                <span class="text-sm text-gray-600">สวัสดี, {{ auth()->user()->name }}</span>
                            </div>
                            
                            <div class="relative" id="desktop-user-menu">
                                <button class="flex text-sm rounded-full focus:outline-none">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center text-white font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                </button>
                                
                                <div id="desktop-user-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
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
            <div class="page-content">
                @if(session('success'))
                    <div class="notification max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
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
                    <div class="notification max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
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
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="main-footer bg-white shadow-sm py-4 px-4">
                <div class="max-w-7xl mx-auto">
                    <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} ระบบฝึกอบรมออนไลน์. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('open');
                hamburgerBtn.classList.toggle('open');
            }
            
            if (hamburgerBtn) {
                hamburgerBtn.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
            
            // Desktop user dropdown
            const desktopUserMenu = document.getElementById('desktop-user-menu');
            const desktopUserDropdown = document.getElementById('desktop-user-dropdown');
            
            if (desktopUserMenu && desktopUserDropdown) {
                desktopUserMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    desktopUserDropdown.classList.toggle('hidden');
                });
            }
            
            // Mobile user dropdown
            const mobileUserMenu = document.getElementById('mobile-user-menu');
            const mobileUserDropdown = document.getElementById('mobile-user-dropdown');
            
            if (mobileUserMenu && mobileUserDropdown) {
                mobileUserMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileUserDropdown.classList.toggle('hidden');
                });
            }
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (desktopUserDropdown && !desktopUserMenu.contains(event.target)) {
                    desktopUserDropdown.classList.add('hidden');
                }
                
                if (mobileUserDropdown && !mobileUserMenu.contains(event.target)) {
                    mobileUserDropdown.classList.add('hidden');
                }
            });
            
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