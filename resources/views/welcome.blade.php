@extends('layouts.app')

@section('title', 'ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์')

@section('content')
    <div class="flex flex-col items-center justify-center py-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4 text-center">ยินดีต้อนรับสู่ระบบฝึกอบรมออนไลน์</h1>
        <p class="text-gray-600 text-lg max-w-3xl text-center mb-8">
            ระบบฝึกอบรมออนไลน์ที่ออกแบบมาเพื่อการเรียนรู้ที่มีประสิทธิภาพ ด้วยวิดีโอที่มีคุณภาพพร้อมคำถามที่แทรกระหว่างเรียน
        </p>
        
        <div class="flex space-x-4">
            @auth
                <a href="{{ route('home') }}" class="bg-blue-600 text-white py-3 px-6 rounded-lg text-lg hover:bg-blue-700">
                    เข้าสู่คอร์สทั้งหมด
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 text-white py-3 px-6 rounded-lg text-lg hover:bg-blue-700">
                    เข้าสู่ระบบ
                </a>
            @endauth
        </div>
    </div>
    
    <div class="mt-12 bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">คุณสมบัติของระบบ</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 text-4xl mb-4">🎥</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">วิดีโอคุณภาพสูง</h3>
                    <p class="text-gray-600">เรียนรู้ผ่านวิดีโอที่ออกแบบมาเพื่อการเรียนรู้ที่มีประสิทธิภาพสูงสุด</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 text-4xl mb-4">❓</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">คำถามระหว่างเรียน</h3>
                    <p class="text-gray-600">ทดสอบความรู้ความเข้าใจทันทีด้วยคำถามที่แทรกระหว่างการรับชมวิดีโอ</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">สรุปผลคะแนน</h3>
                    <p class="text-gray-600">ดูสรุปผลคะแนนและความก้าวหน้าในการเรียนได้ทันทีหลังเรียนจบ</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-12 py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">พร้อมที่จะเริ่มต้นการเรียนรู้หรือยัง?</h2>
            <p class="text-gray-600 text-lg mb-8">เริ่มต้นการเรียนรู้ด้วยระบบฝึกอบรมออนไลน์ของเราได้แล้ววันนี้</p>
            
            <div class="flex justify-center">
                @auth
                    <a href="{{ route('home') }}" class="bg-blue-600 text-white py-3 px-6 rounded-lg text-lg hover:bg-blue-700">
                        เข้าสู่คอร์สทั้งหมด
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white py-3 px-6 rounded-lg text-lg hover:bg-blue-700">
                        เข้าสู่ระบบเพื่อเริ่มเรียน
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection