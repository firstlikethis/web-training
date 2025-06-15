@extends('layouts.admin')

@section('title', 'Admin Dashboard - ระบบฝึกอบรมออนไลน์')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- จำนวนผู้ใช้ -->
        <div class="card p-6 bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 mr-4">
                    <i class="fas fa-users text-indigo-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">ผู้ใช้ทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['users_count'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
                    <span>จัดการผู้ใช้</span>
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        
        <!-- จำนวนคอร์ส -->
        <div class="card p-6 bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <i class="fas fa-book text-green-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">คอร์สทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['courses_count'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.courses.index') }}" class="text-sm text-green-600 hover:text-green-800 flex items-center">
                    <span>จัดการคอร์ส</span>
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        
        <!-- จำนวนคำถาม -->
        <div class="card p-6 bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-question-circle text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">คำถามทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['questions_count'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-sm text-yellow-600 hover:text-yellow-800 flex items-center">
                    <span>ดูรายละเอียด</span>
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        
        <!-- จำนวนการเรียนจบ -->
        <div class="card p-6 bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <i class="fas fa-trophy text-purple-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">การเรียนจบ</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_courses'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-sm text-purple-600 hover:text-purple-800 flex items-center">
                    <span>ดูรายละเอียด</span>
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- ผู้ใช้ล่าสุด -->
        <div class="card bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">ผู้ใช้ล่าสุด</h2>
            </div>
            
            <div class="p-6">
                @if($recent_users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">อีเมล</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เข้าใช้ล่าสุด</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recent_users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-medium">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $user->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีผู้ใช้</h3>
                        <p class="mt-1 text-sm text-gray-500">เริ่มสร้างผู้ใช้ใหม่</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-plus mr-1"></i> เพิ่มผู้ใช้
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- คอร์สยอดนิยม -->
        <div class="card bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">คอร์สยอดนิยม</h2>
            </div>
            
            <div class="p-6">
                @if($popular_courses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คอร์ส</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวนผู้เรียนจบ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($popular_courses as $course)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                @if($course->thumbnail)
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden">
                                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-10 w-10 object-cover">
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center text-gray-500">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                @endif
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $course->title }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $course->user_progress_count }} คน
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <i class="fas fa-book text-3xl"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีคอร์ส</h3>
                        <p class="mt-1 text-sm text-gray-500">เริ่มสร้างคอร์สใหม่</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-plus mr-1"></i> เพิ่มคอร์ส
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- กราฟสถิติการตอบคำถาม -->
    <div class="card bg-white rounded-2xl shadow mt-6 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">การตอบคำถาม 7 วันล่าสุด</h2>
        </div>
        
        <div class="p-6">
            <div class="h-80">
                <canvas id="answersChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('answersChart').getContext('2d');
            
            // ข้อมูลสำหรับกราฟ
            const data = @json($answers_per_day);
            const dates = Object.keys(data);
            const counts = Object.values(data);
            
            // สร้างกราฟ
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'จำนวนการตอบคำถาม',
                        data: counts,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            backgroundColor: 'rgba(79, 70, 229, 0.8)',
                            titleFont: {
                                family: "'Poppins', sans-serif",
                                size: 13
                            },
                            bodyFont: {
                                family: "'Poppins', sans-serif",
                                size: 12
                            },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' ครั้ง';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection