@extends('layouts.admin')

@section('title', 'Admin Dashboard - ระบบฝึกอบรมออนไลน์')

@section('page-title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- จำนวนผู้ใช้ -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-600 mb-2">ผู้ใช้ทั้งหมด</h2>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['users_count'] }}</p>
        </div>
        
        <!-- จำนวนคอร์ส -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-600 mb-2">คอร์สทั้งหมด</h2>
            <p class="text-3xl font-bold text-green-600">{{ $stats['courses_count'] }}</p>
        </div>
        
        <!-- จำนวนคำถาม -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-600 mb-2">คำถามทั้งหมด</h2>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['questions_count'] }}</p>
        </div>
        
        <!-- จำนวนการเรียนจบ -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-600 mb-2">การเรียนจบ</h2>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['completed_courses'] }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- ผู้ใช้ล่าสุด -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">ผู้ใช้ล่าสุด</h2>
            
            @if($recent_users->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 text-left">ชื่อ</th>
                            <th class="py-2 text-left">อีเมล</th>
                            <th class="py-2 text-left">เข้าใช้ล่าสุด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_users as $user)
                            <tr class="border-b">
                                <td class="py-2">{{ $user->name }}</td>
                                <td class="py-2">{{ $user->email }}</td>
                                <td class="py-2">{{ $user->updated_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">ยังไม่มีผู้ใช้ในระบบ</p>
            @endif
        </div>
        
        <!-- คอร์สยอดนิยม -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">คอร์สยอดนิยม</h2>
            
            @if($popular_courses->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 text-left">ชื่อคอร์ส</th>
                            <th class="py-2 text-left">จำนวนผู้เรียนจบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($popular_courses as $course)
                            <tr class="border-b">
                                <td class="py-2">{{ $course->title }}</td>
                                <td class="py-2">{{ $course->user_progress_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">ยังไม่มีคอร์สในระบบ</p>
            @endif
        </div>
    </div>
    
    <!-- กราฟสถิติการตอบคำถาม 7 วันล่าสุด -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-bold mb-4">การตอบคำถาม 7 วันล่าสุด</h2>
        
        <div class="h-64">
            <canvas id="answersChart"></canvas>
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
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'จำนวนการตอบคำถาม',
                        data: counts,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
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
                    }
                }
            });
        });
    </script>
@endsection