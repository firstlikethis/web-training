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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%);
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: "";
            position: absolute;
            width: 1000px;
            height: 1000px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
            top: -400px;
            right: -400px;
            z-index: -1;
        }
        
        body::after {
            content: "";
            position: absolute;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            bottom: -300px;
            left: -300px;
            z-index: -1;
        }
        
        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
            width: 100%;
            max-width: 450px;
            transition: all 0.3s ease;
        }
        
        .auth-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-5px);
        }
        
        .auth-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
        }
        
        .form-input {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #374151;
            background-color: #F9FAFB;
            background-clip: padding-box;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-input:focus {
            border-color: #93C5FD;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            background-color: #fff;
        }
        
        .btn-primary {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: white;
            background-image: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-image: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        
        .auth-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }
        
        /* Animation */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .auth-card {
            animation: fadeUp 0.6s ease-out forwards;
        }
    </style>
    
    @yield('styles')
</head>
<body class="px-4 py-8">
    <div class="auth-card p-8">
        <div class="text-center">
            <div class="auth-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">@yield('auth-title', 'เข้าสู่ระบบ')</h1>
            <p class="text-gray-500 mb-8">@yield('auth-subtitle', 'ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์')</p>
        </div>
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        @yield('content')
        
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> กลับไปหน้าหลัก
            </a>
        </div>
    </div>
    
    @yield('scripts')
</body>
</html>