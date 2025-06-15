@extends('layouts.admin')

@section('title', 'จัดการคอร์ส - Admin Dashboard')

@section('page-title', 'จัดการคอร์ส')

@section('content')
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">คอร์สทั้งหมด</h2>
                <p class="text-gray-600">จัดการคอร์สทั้งหมดในระบบ</p>
            </div>
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i> เพิ่มคอร์สใหม่
                </a>
            </div>
        </div>
    </div>
    
    <!-- ตัวกรองสำหรับคอร์ส -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">ค้นหาและกรอง</h3>
        <form action="{{ route('admin.courses.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="category_filter" class="block text-sm font-medium text-gray-700 mb-1">กรองตามหมวดหมู่</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-folder text-gray-400"></i>
                        </div>
                        <select name="category" id="category_filter" 
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">ทั้งหมด</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">ค้นหา</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="ชื่อคอร์ส...">
                    </div>
                </div>
                
                <div class="flex items-end space-x-3">
                    <button type="submit" class="btn btn-primary flex-1 md:flex-none">
                        <i class="fas fa-filter mr-1"></i> กรอง
                    </button>
                    
                    @if(request('category') || request('search'))
                        <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex-1 md:flex-none text-center">
                            <i class="fas fa-times mr-1"></i> ล้างตัวกรอง
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    
    <!-- คอร์สที่อยู่ระหว่างการสร้าง (Draft) -->
    @if($draftCourses->count() > 0)
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="w-4 h-4 rounded-full bg-yellow-400 mr-2"></div>
            <h3 class="text-lg font-semibold text-gray-800">คอร์สที่กำลังสร้าง (Draft)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คอร์ส</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วิดีโอ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สร้างเมื่อ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($draftCourses as $course)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-500">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                        <div class="flex items-center mt-1">
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                                อยู่ระหว่างการสร้าง
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->video_path)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> อัปโหลดแล้ว
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> ยังไม่มีวิดีโอ
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                                    {{ $course->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.courses.edit_details', $course) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                        <span class="ml-1 hidden md:inline">แก้ไข/เผยแพร่</span>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.courses.cancel_draft', $course) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิก? วิดีโอและข้อมูลทั้งหมดจะถูกลบ')">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="ml-1 hidden md:inline">ยกเลิก</span>
                                        </button>
                                    </form>
                                </div>
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
    
    <!-- คอร์สที่เผยแพร่แล้ว (Published) -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center mb-4">
            <div class="w-4 h-4 rounded-full bg-green-400 mr-2"></div>
            <h3 class="text-lg font-semibold text-gray-800">คอร์สที่เผยแพร่แล้ว</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คอร์ส</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หมวดหมู่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวนคำถาม</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ความยาว</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($courses as $course)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($course->thumbnail)
                                        <div class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden">
                                            <img class="h-10 w-10 object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                        <div class="text-xs text-gray-500">สร้างเมื่อ {{ $course->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $course->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-question-circle mr-1"></i> {{ $course->questions_count }} คำถาม
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                                    {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> เปิดใช้งาน
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> ปิดใช้งาน
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                        <span class="ml-1 hidden md:inline">แก้ไข</span>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคอร์สนี้?')">
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
                                    <div class="h-16 w-16 text-gray-400 mb-4">
                                        <i class="fas fa-book text-5xl"></i>
                                    </div>
                                    <p class="text-base font-medium text-gray-900 mb-1">ไม่พบข้อมูลคอร์ส</p>
                                    <p class="text-sm text-gray-500 mb-4">เริ่มสร้างคอร์สใหม่ตอนนี้</p>
                                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> เพิ่มคอร์สใหม่
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $courses->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection