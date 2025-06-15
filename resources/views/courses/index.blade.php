@extends('layouts.app')

@section('title', 'หน้าแรก - ระบบฝึกอบรมออนไลน์')

@section('content')
    <div class="mb-8 text-center max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์</h1>
        <p class="text-gray-600 text-lg">เรียนรู้ได้ทุกที่ทุกเวลาผ่านวิดีโอและการฝึกอบรมที่ออกแบบมาเพื่อคุณโดยเฉพาะ</p>
    </div>
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">คอร์สทั้งหมด</h2>
        
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    @auth
                        <a href="{{ route('course.show', $course) }}" class="block bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105 cursor-pointer">
                    @else
                        <a href="#" onclick="submitLoginForm('course-{{ $course->id }}')" class="block bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105 cursor-pointer">
                    @endauth
                        <div class="relative">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">ไม่มีรูปภาพ</span>
                                </div>
                            @endif
                            
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-2">
                                <span class="text-sm">
                                    {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3" style="overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3;">
                                {{ $course->description ?? 'ไม่มีคำอธิบาย' }}
                            </p>
                            
                            <div class="text-sm text-gray-500 mb-2">
                                {{ $course->questions->count() }} คำถาม
                            </div>
                            
                            @auth
                                @php
                                    $userProgress = $course->userProgress->where('user_id', auth()->id())->first();
                                @endphp
                                
                                @if($userProgress)
                                    <div class="mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $userProgress->progress_percentage }}%"></div>
                                        </div>
                                        <div class="flex justify-between mt-1">
                                            <span class="text-xs text-gray-500">ดูแล้ว {{ $userProgress->progress_percentage }}%</span>
                                            @if($userProgress->is_completed)
                                                <span class="text-xs text-green-600">เรียนจบแล้ว</span>
                                            @endif
                                        </div>
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
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">ยังไม่มีคอร์สในระบบ</p>
            </div>
        @endif
    </div>
    
    <div class="bg-blue-50 rounded-lg p-6 shadow-inner">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-blue-800 mb-3">เกี่ยวกับเรา</h2>
            <p class="text-blue-700 mb-4">
                ระบบฝึกอบรมออนไลน์ของเราออกแบบมาเพื่อให้การเรียนรู้เป็นเรื่องง่ายและมีประสิทธิภาพ
                ด้วยวิดีโอที่มีคุณภาพพร้อมแบบทดสอบที่จะทำให้คุณได้ทบทวนความรู้อย่างเข้าใจ
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-blue-600 text-2xl mb-2">🎥</div>
                    <h3 class="font-bold text-gray-800 mb-1">วิดีโอคุณภาพสูง</h3>
                    <p class="text-gray-600 text-sm">เรียนรู้ผ่านวิดีโอที่ถ่ายทำและตัดต่ออย่างมืออาชีพ</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-blue-600 text-2xl mb-2">✅</div>
                    <h3 class="font-bold text-gray-800 mb-1">แบบทดสอบระหว่างเรียน</h3>
                    <p class="text-gray-600 text-sm">ทดสอบความเข้าใจด้วยคำถามที่แทรกระหว่างการเรียน</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-blue-600 text-2xl mb-2">📊</div>
                    <h3 class="font-bold text-gray-800 mb-1">ติดตามความก้าวหน้า</h3>
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