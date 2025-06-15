@extends('layouts.admin')

@section('title', 'จัดการคอร์ส - Admin Dashboard')

@section('page-title', 'จัดการคอร์ส')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">จัดการคอร์สทั้งหมดในระบบ</p>
        <a href="{{ route('admin.courses.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            <span class="mr-1">+</span> เพิ่มคอร์สใหม่
        </a>
    </div>
    
    <!-- คอร์สที่อยู่ระหว่างการสร้าง (Draft) -->
    @if($draftCourses->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">คอร์สที่กำลังสร้าง (Draft)</h2>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คอร์ส</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วิดีโอ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สร้างเมื่อ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($draftCourses as $course)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                <div class="text-xs text-gray-500">(อยู่ระหว่างการสร้าง)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->video_path)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        อัปโหลดแล้ว
                                    </span>
                                @elseif($course->video_url)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        YouTube
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        ยังไม่มีวิดีโอ
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $course->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.courses.edit_details', $course) }}" class="text-blue-600 hover:text-blue-900 mr-3">แก้ไข/เผยแพร่</a>
                                
                                <form method="POST" action="{{ route('admin.courses.cancel_draft', $course) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิก? วิดีโอและข้อมูลทั้งหมดจะถูกลบ')">ยกเลิก</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination สำหรับ draft courses -->
        <div class="mt-4">
            {{ $draftCourses->links() }}
        </div>
    </div>
    @endif
    
    <!-- ตัวกรองสำหรับคอร์ส -->
    <div class="mb-6">
        <form action="{{ route('admin.courses.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="category_filter" class="block text-sm font-medium text-gray-700 mb-1">กรองตามหมวดหมู่</label>
                    <select name="category" id="category_filter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">ทั้งหมด</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">ค้นหา</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ชื่อคอร์ส..." 
                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                        กรอง
                    </button>
                </div>
                @if(request('category') || request('search'))
                    <div>
                        <a href="{{ route('admin.courses.index') }}" class="text-gray-600 hover:text-gray-800">ล้างตัวกรอง</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
    
    <!-- คอร์สที่เผยแพร่แล้ว (Published) -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-4">คอร์สที่เผยแพร่แล้ว</h2>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คอร์ส</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หมวดหมู่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวนคำถาม</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ความยาว</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($courses as $course)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($course->thumbnail)
                                        <div class="flex-shrink-0 h-10 w-10 mr-4">
                                            <img class="h-10 w-10 rounded-md object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                        <div class="text-sm text-gray-500">สร้างเมื่อ {{ $course->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->category)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $course->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $course->questions_count }} คำถาม
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $course->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="text-blue-600 hover:text-blue-900 mr-3">แก้ไข</a>
                                
                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคอร์สนี้?')">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">ไม่พบข้อมูลคอร์ส</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $courses->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection