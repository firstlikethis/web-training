@extends('layouts.admin')

@section('title', 'สร้างคอร์สใหม่ - Admin Dashboard')

@section('page-title', 'สร้างคอร์สใหม่')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">ขั้นตอนที่ 1: อัปโหลดวิดีโอ</h2>
            <p class="text-gray-600 mb-4">เริ่มต้นด้วยการอัปโหลดวิดีโอหรือระบุ URL ของวิดีโอ</p>
        </div>
        
        <form action="{{ route('admin.courses.store_video') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">เลือกประเภทวิดีโอ</label>
                <div class="flex space-x-4 mb-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="video_type" value="upload" class="video-type-radio" checked>
                        <span class="ml-2">อัปโหลดไฟล์วิดีโอ</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="video_type" value="url" class="video-type-radio">
                        <span class="ml-2">ใช้ URL วิดีโอ</span>
                    </label>
                </div>
                
                <!-- สำหรับอัปโหลดไฟล์ -->
                <div id="video-upload-section">
                    <label for="video" class="block text-sm font-medium text-gray-700 mb-1">ไฟล์วิดีโอ (MP4, WebM, Ogg)</label>
                    <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <p class="text-sm text-gray-500 mt-1">ระบบจะคำนวณความยาววิดีโอให้อัตโนมัติ</p>
                    
                    @error('video')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- สำหรับ URL วิดีโอ -->
                <div id="video-url-section" class="hidden">
                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">URL วิดีโอ (YouTube, Vimeo)</label>
                    <input type="url" name="video_url" id="video_url" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                           placeholder="https://www.youtube.com/embed/...">
                    <p class="text-sm text-gray-500 mt-1">สำหรับ YouTube ให้ใช้ URL แบบ Embed: https://www.youtube.com/embed/VIDEO_ID</p>
                    
                    @error('video_url')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex items-center justify-end mt-8">
                <a href="{{ route('admin.courses.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">ยกเลิก</a>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">อัปโหลดวิดีโอและไปขั้นตอนถัดไป</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // สำหรับสลับระหว่างวิดีโอแบบอัพโหลดกับ URL
        const radioButtons = document.querySelectorAll('.video-type-radio');
        const uploadSection = document.getElementById('video-upload-section');
        const urlSection = document.getElementById('video-url-section');
        const videoInput = document.getElementById('video');
        const urlInput = document.getElementById('video_url');
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'upload') {
                    uploadSection.classList.remove('hidden');
                    urlSection.classList.add('hidden');
                    videoInput.setAttribute('required', '');
                    urlInput.removeAttribute('required');
                } else if (this.value === 'url') {
                    uploadSection.classList.add('hidden');
                    urlSection.classList.remove('hidden');
                    videoInput.removeAttribute('required');
                    urlInput.setAttribute('required', '');
                }
            });
        });
        
        // ตรวจสอบฟอร์มก่อนส่ง
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const videoType = document.querySelector('input[name="video_type"]:checked').value;
            
            if (videoType === 'upload' && !videoInput.files.length) {
                e.preventDefault();
                alert('กรุณาเลือกไฟล์วิดีโอก่อนอัปโหลด');
            } else if (videoType === 'url' && !urlInput.value) {
                e.preventDefault();
                alert('กรุณาระบุ URL ของวิดีโอ');
            }
        });
    });
</script>
@endsection