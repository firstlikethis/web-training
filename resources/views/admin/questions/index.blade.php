@extends('layouts.admin')

@section('title', 'จัดการคำถาม - Admin Dashboard')

@section('page-title', 'จัดการคำถาม')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <p class="text-gray-600">จัดการคำถามทั้งหมดในระบบ</p>
            
            <div class="mt-2">
                <form action="{{ route('admin.questions.index') }}" method="GET" class="flex items-center">
                    <select name="course_id" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                        <option value="">-- ทั้งหมด --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gray-200 text-gray-700 py-1 px-3 rounded hover:bg-gray-300">กรอง</button>
                </form>
            </div>
        </div>
        
        <a href="{{ route('admin.questions.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            <span class="mr-1">+</span> เพิ่มคำถามใหม่
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
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
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $question->course->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($question->question_text, 50) }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $question->answers->count() }} ตัวเลือก</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ floor($question->time_to_show / 60) }}:{{ str_pad($question->time_to_show % 60, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $question->time_limit_seconds }} วินาที
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $question->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $question->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.questions.edit', $question) }}" class="text-blue-600 hover:text-blue-900 mr-3">แก้ไข</a>
                            
                            <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคำถามนี้?')">ลบ</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">ไม่พบข้อมูลคำถาม</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $questions->links() }}
    </div>
@endsection