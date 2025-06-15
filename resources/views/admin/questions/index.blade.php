@extends('layouts.admin')

@section('title', 'จัดการคำถาม - Admin Dashboard')

@section('page-title', 'จัดการคำถาม')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">คำถามทั้งหมด</h2>
                <p class="text-gray-600">จัดการคำถามทั้งหมดในระบบ</p>
                
                <div class="mt-3">
                    <form action="{{ route('admin.questions.index') }}" method="GET" class="flex flex-wrap gap-2">
                        <select name="course_id" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- ทั้งหมด --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-filter mr-1"></i> กรอง
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.questions.create') }}" class="btn btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i> เพิ่มคำถามใหม่
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คอร์ส</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คำถาม</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เวลาที่แสดง</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เวลาจำกัด</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($questions as $question)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $question->course->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($question->question_text, 50) }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $question->answers->count() }} ตัวเลือก</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ floor($question->time_to_show / 60) }}:{{ str_pad($question->time_to_show % 60, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $question->time_limit_seconds }} วินาที</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $question->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $question->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                        <span class="ml-1 hidden md:inline">แก้ไข</span>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคำถามนี้?')">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="ml-1 hidden md:inline">ลบ</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="text-gray-400 mb-3">
                                        <i class="fas fa-question-circle text-5xl"></i>
                                    </div>
                                    <p class="text-gray-500">ไม่พบข้อมูลคำถาม</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i> เพิ่มคำถามใหม่
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    </div>
@endsection