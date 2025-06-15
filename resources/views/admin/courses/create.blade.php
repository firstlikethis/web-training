@extends('layouts.admin')

@section('title', 'สร้างคอร์สใหม่ - Admin Dashboard')

@section('page-title', 'สร้างคอร์สใหม่')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">อัพโหลดวิดีโอสำหรับคอร์ส</h2>
            <p class="text-gray-600 mb-4">กรุณาอัพโหลดไฟล์วิดีโอของคุณ (รองรับไฟล์ MP4, WebM, Ogg)</p>
        </div>
        
        <form action="{{ route('admin.courses.store_video') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            
            <div class="mb-6">
                <label for="video" class="block text-sm font-medium text-gray-700 mb-1">ไฟล์วิดีโอ (MP4, WebM, Ogg)</label>
                <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                <p class="text-sm text-gray-500 mt-1">ระบบจะคำนวณความยาววิดีโอให้อัตโนมัติด้วย getID3</p>
                
                @error('video')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-end mt-8">
                <a href="{{ route('admin.courses.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">ยกเลิก</a>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">ถัดไป</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ตรวจสอบฟอร์มก่อนส่ง
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const videoInput = document.getElementById('video');
            
            if (videoInput.files.length === 0) {
                e.preventDefault();
                alert('กรุณาเลือกไฟล์วิดีโอก่อนดำเนินการต่อ');
            }
        });
    });
</script>
@endsection