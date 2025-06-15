@extends('layouts.app')

@section('title', 'ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์')

@section('content')
    <div class="relative overflow-hidden">
        <!-- Hero Section -->
        <div class="relative bg-white py-16">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-r from-blue-50 to-indigo-100 rounded-bl-[100px]"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-200 to-indigo-200 rounded-full transform translate-x-1/3 -translate-y-1/2 opacity-30"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-blue-200 to-indigo-200 rounded-full transform -translate-x-1/3 translate-y-1/2 opacity-30"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="md:w-1/2 text-center md:text-left">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                            <span class="block">ยกระดับการเรียนรู้</span>
                            <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">ด้วยระบบฝึกอบรมออนไลน์</span>
                        </h1>
                        <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                            ระบบฝึกอบรมออนไลน์ที่ออกแบบมาเพื่อการเรียนรู้ที่มีประสิทธิภาพ พร้อมฟีเจอร์คำถามระหว่างเรียนที่ช่วยเพิ่มการจดจำและความเข้าใจ
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            @auth
                                <a href="{{ route('home') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all text-center text-lg font-medium">
                                    <i class="fas fa-play-circle mr-2"></i> เริ่มเรียนทันที
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all text-center text-lg font-medium">
                                    <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ
                                </a>
                            @endauth
                            
                            <a href="#features" class="px-8 py-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all text-center text-lg font-medium">
                                <i class="fas fa-info-circle mr-2"></i> ดูรายละเอียด
                            </a>
                        </div>
                        
                        <div class="mt-8 flex items-center justify-center md:justify-start">
                            <div class="flex -space-x-4">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold border-2 border-white">JD</div>
                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold border-2 border-white">ST</div>
                                <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white text-xs font-bold border-2 border-white">RB</div>
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-800 text-xs font-bold border-2 border-white">99+</div>
                            </div>
                            <p class="text-gray-600 ml-4 text-sm">ผู้ใช้งานกว่า <span class="font-bold text-gray-900">10,000+</span> คนทั่วประเทศ</p>
                        </div>
                    </div>
                    
                    <div class="md:w-1/2">
                        <div class="relative mx-auto w-full max-w-md">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-600 transform rotate-3 rounded-3xl shadow-2xl"></div>
                            <div class="relative bg-white p-6 rounded-3xl shadow-xl overflow-hidden">
                                <div class="aspect-video bg-gray-100 rounded-xl mb-4 flex items-center justify-center">
                                    <i class="fas fa-play-circle text-6xl text-blue-500 opacity-50"></i>
                                </div>
                                <div class="space-y-3">
                                    <div class="h-6 bg-gray-100 rounded-full w-3/4"></div>
                                    <div class="h-4 bg-gray-100 rounded-full"></div>
                                    <div class="h-4 bg-gray-100 rounded-full w-5/6"></div>
                                    <div class="flex gap-2 mt-4">
                                        <div class="h-10 bg-blue-100 rounded-lg flex-1"></div>
                                        <div class="h-10 bg-gray-100 rounded-lg flex-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Features Section -->
        <div id="features" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">คุณสมบัติที่โดดเด่น</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">ค้นพบประสบการณ์การเรียนรู้แบบใหม่ที่ออกแบบมาเพื่อองค์กรระดับมืออาชีพ</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all hover:scale-105">
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                        <div class="p-6">
                            <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 mb-6">
                                <i class="fas fa-film text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">วิดีโอคุณภาพสูง</h3>
                            <p class="text-gray-600">เรียนรู้ผ่านวิดีโอที่ออกแบบและผลิตอย่างมืออาชีพ เพื่อประสบการณ์การเรียนรู้ที่ดีที่สุด</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all hover:scale-105">
                        <div class="h-2 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>
                        <div class="p-6">
                            <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 mb-6">
                                <i class="fas fa-question-circle text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">คำถามระหว่างเรียน</h3>
                            <p class="text-gray-600">เสริมความเข้าใจด้วยคำถามที่แทรกระหว่างการเรียน ช่วยให้ผู้เรียนจดจำและเข้าใจเนื้อหาได้ดียิ่งขึ้น</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all hover:scale-105">
                        <div class="h-2 bg-gradient-to-r from-purple-500 to-purple-600"></div>
                        <div class="p-6">
                            <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-6">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">ติดตามความก้าวหน้า</h3>
                            <p class="text-gray-600">ติดตามและวิเคราะห์ผลการเรียนรู้ด้วยระบบรายงานที่ครบถ้วน ดูผลคะแนนและความก้าวหน้าได้ทันที</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">สำหรับองค์กร</h3>
                            <ul class="space-y-4">
                                <li class="flex">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                    </div>
                                    <p class="ml-3 text-gray-600">บริหารจัดการผู้ใช้และคอร์สได้อย่างมีประสิทธิภาพ</p>
                                </li>
                                <li class="flex">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                    </div>
                                    <p class="ml-3 text-gray-600">ติดตามความคืบหน้าของพนักงานได้แบบ Real-time</p>
                                </li>
                                <li class="flex">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                    </div>
                                    <p class="ml-3 text-gray-600">รายงานผลการเรียนรู้ที่ครบถ้วนและแม่นยำ</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">สำหรับผู้เรียน</h3>
                            <ul class="space-y-4">
                                <li class="flex">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-check text-blue-600 text-xs"></i>
                                    </div>
                                    <p class="ml-3 text-gray-600">เรียนรู้ได้ทุกที่ทุกเวลาตามความสะดวก</p>
                                </li>
                                <li class="flex">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-check text-blue-600 text-xs"></i>
                                    </div>
                                    <p class="ml-3 text-gray-600">ทบทวนความเข้าใจผ่านคำถามระหว่างเรียน</p>
                                </li>
                                <li class="flex">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-check text-blue-600 text-xs"></i>
                                    </div>
                                    <p class="ml-3 text-gray-600">ดูผลคะแนนและความก้าวหน้าได้ทันที</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- CTA Section -->
        <div class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-6">พร้อมที่จะเริ่มต้นการเรียนรู้แล้วหรือยัง?</h2>
                <p class="text-xl text-blue-100 mb-10 max-w-3xl mx-auto">ยกระดับการฝึกอบรมในองค์กรของคุณด้วยระบบที่ออกแบบมาเพื่อประสิทธิภาพสูงสุด</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('home') }}" class="px-8 py-4 bg-white text-blue-700 rounded-lg shadow-lg hover:shadow-xl transition-all text-lg font-medium">
                            <i class="fas fa-play-circle mr-2"></i> เริ่มเรียนทันที
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-blue-700 rounded-lg shadow-lg hover:shadow-xl transition-all text-lg font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .rounded-bl-[100px] {
        border-bottom-left-radius: 100px;
    }
</style>
@endsection