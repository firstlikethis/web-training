@extends('layouts.app')

@section('title', 'หน้าแรก - ระบบฝึกอบรมออนไลน์')

@section('content')
    <div class="mb-8 text-center max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-3">คอร์สฝึกอบรมทั้งหมด</h1>
        <p class="text-gray-600 text-lg">เลือกคอร์สที่ต้องการเรียนรู้ได้ตามความสนใจ</p>
    </div>
    
    <!-- ส่วนค้นหาและกรองข้อมูล -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 transform transition-all duration-300 hover:shadow-lg">
        <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">ค้นหาคอร์ส</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="ชื่อหรือคำอธิบายคอร์ส..."
                           class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="w-full md:w-48">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                <div class="relative">
                    <select name="category" id="category" 
                            class="w-full py-3 pl-3 pr-10 border border-gray-300 rounded-lg shadow-sm appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">ทั้งหมด</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div>
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> ค้นหา
                </button>
            </div>
        </form>
    </div>
    
    <!-- แสดงหมวดหมู่ทั้งหมด -->
    @if($categories->count() > 0)
        <div class="mb-8 overflow-x-auto pb-2">
            <div class="flex flex-nowrap gap-2 min-w-full">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap 
                    {{ empty(request('category')) ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                    ทั้งหมด
                </a>
                
                @foreach($categories as $category)
                    <a href="{{ route('home', ['category' => $category->id]) }}" 
                       class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap 
                       {{ request('category') == $category->id ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            @if(request('search') || request('category'))
                <span class="mr-2">ผลการค้นหา</span>
                @if(request('search'))
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full mr-2">"{{ request('search') }}"</span>
                @endif
                @if(request('category') && $categories->where('id', request('category'))->first())
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm rounded-full">หมวดหมู่: {{ $categories->where('id', request('category'))->first()->name }}</span>
                @endif
            @else
                <span>คอร์สทั้งหมด</span>
            @endif
        </h2>
        
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                    @auth
                        <a href="{{ route('course.show', $course) }}" class="group bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                    @else
                        <a href="#" onclick="submitLoginForm('course-{{ $course->id }}')" class="group bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                    @endauth
                        <div class="relative aspect-video">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-play-circle text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <span class="text-white font-medium">
                                    <i class="fas fa-play mr-2"></i> เริ่มเรียน
                                </span>
                            </div>
                            
                            <div class="absolute bottom-4 left-4 bg-black/70 text-white px-3 py-1 rounded-full text-xs backdrop-blur-sm">
                                <i class="fas fa-clock mr-1"></i>
                                {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที
                            </div>
                            
                            @if($course->category)
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-blue-600/90 text-white text-xs rounded-full backdrop-blur-sm">
                                        {{ $course->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $course->title }}</h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-2 text-sm" style="min-height: 2.5rem;">
                                {{ $course->description ?? 'ไม่มีคำอธิบาย' }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-question-circle mr-1"></i>
                                    {{ $course->questions->count() }} คำถาม
                                </div>
                                
                                <div class="text-blue-600 text-sm font-medium">
                                    <i class="fas fa-arrow-right ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                            
                            @auth
                                @php
                                    $userProgress = $course->userProgress()
                                        ->where('user_id', auth()->id())
                                        ->first();
                                @endphp
                                
                                @if($userProgress)
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>ความคืบหน้า</span>
                                            <span>{{ $userProgress->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full" style="width: {{ $userProgress->progress_percentage }}%"></div>
                                        </div>
                                        @if($userProgress->is_completed)
                                            <div class="mt-2 text-center">
                                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                    <i class="fas fa-check-circle mr-1"></i> เรียนจบแล้ว
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Form สำหรับ submit เข้าสู่ระบบแบบซ่อน -->
            @guest
                <form id="login-redirect-form" method="POST" action="{{ route('login') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="redirect" id="login-redirect-value" value="">
                </form>
            @endguest
        @else
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-search text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">ไม่พบคอร์สที่ค้นหา</h3>
                <p class="text-gray-600 mb-6">ลองค้นหาด้วยคำค้นอื่น หรือดูคอร์สทั้งหมด</p>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i> ดูคอร์สทั้งหมด
                </a>
            </div>
        @endif
    </div>
    
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8 shadow-inner mb-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-blue-800 mb-4">เกี่ยวกับระบบฝึกอบรมออนไลน์</h2>
            <p class="text-blue-700 mb-6 leading-relaxed">
                ระบบฝึกอบรมออนไลน์ของเราออกแบบมาเพื่อให้การเรียนรู้เป็นเรื่องง่ายและมีประสิทธิภาพ
                ด้วยวิดีโอที่มีคุณภาพพร้อมแบบทดสอบที่จะทำให้คุณได้ทบทวนความรู้อย่างเข้าใจ
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-white p-6 rounded-xl shadow-md transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mx-auto mb-4">
                        <i class="fas fa-film text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">วิดีโอคุณภาพสูง</h3>
                    <p class="text-gray-600 text-sm">เรียนรู้ผ่านวิดีโอที่ถ่ายทำและตัดต่ออย่างมืออาชีพ</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mx-auto mb-4">
                        <i class="fas fa-question-circle text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">แบบทดสอบระหว่างเรียน</h3>
                    <p class="text-gray-600 text-sm">ทดสอบความเข้าใจด้วยคำถามที่แทรกระหว่างการเรียน</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-green-600 mx-auto mb-4">
                        <i class="fas fa-chart-bar text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">ติดตามความก้าวหน้า</h3>
                    <p class="text-gray-600 text-sm">ดูผลคะแนนและความก้าวหน้าในการเรียนได้ทันที</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function submitLoginForm(redirectValue) {
        document.getElementById('login-redirect-value').value = redirectValue;
        document.getElementById('login-redirect-form').submit();
    }
</script>
@endsection