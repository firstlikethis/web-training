@extends('layouts.admin')

@section('title', 'สร้างคอร์สใหม่ - Admin Dashboard')

@section('page-title', 'สร้างคอร์สใหม่')

@section('styles')
<style>
    /* Upload Area Styling */
    .upload-area {
        border: 2px dashed #e2e8f0;
        border-radius: 1rem;
        transition: all 0.3s;
    }
    
    .upload-area:hover, .upload-area.dragging {
        border-color: #7e3af2;
        background-color: rgba(126, 58, 242, 0.05);
    }
    
    .upload-area.active {
        border-color: #10b981;
        background-color: rgba(16, 185, 129, 0.05);
    }
    
    .upload-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto;
        color: #7e3af2;
        opacity: 0.6;
        transition: all 0.3s;
    }
    
    .upload-area:hover .upload-icon,
    .upload-area.dragging .upload-icon {
        transform: scale(1.1);
        opacity: 1;
    }
    
    /* Upload Progress */
    .upload-progress {
        height: 8px;
        border-radius: 9999px;
        overflow: hidden;
        background: #e5e7eb;
    }
    
    .upload-progress-bar {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #7e3af2, #4f46e5);
        transition: width 0.3s ease;
    }
    
    /* File Preview */
    .file-preview {
        border-radius: 0.5rem;
        overflow: hidden;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
    }
    
    .file-preview:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection

@section('content')
    <div class="card bg-white rounded-2xl shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">อัปโหลดวิดีโอสำหรับคอร์ส</h2>
        
        <div class="text-center text-gray-600 mb-8">
            <p class="mb-2">กรุณาอัปโหลดไฟล์วิดีโอของคุณ</p>
            <p class="text-sm text-gray-500">รองรับไฟล์ MP4, WebM, Ogg ขนาดไม่เกิน 100MB</p>
        </div>
        
        <form action="{{ route('admin.courses.store_video') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            
            <div class="mb-8">
                <!-- Upload Area -->
                <div id="upload-area" class="upload-area p-8 flex flex-col items-center justify-center cursor-pointer">
                    <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg" class="hidden" required>
                    
                    <div class="upload-icon mb-4">
                        <i class="fas fa-cloud-upload-alt text-5xl"></i>
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-800 mb-2">ลากและวางไฟล์ หรือ คลิกที่นี่</h3>
                    <p class="text-sm text-gray-500">รองรับไฟล์ MP4, WebM, Ogg</p>
                </div>
                
                <!-- File Preview (แสดงเมื่อเลือกไฟล์แล้ว) -->
                <div id="file-preview" class="file-preview p-4 mt-6 hidden">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 rounded-md bg-indigo-100 flex items-center justify-center text-indigo-500">
                            <i class="fas fa-file-video text-xl"></i>
                        </div>
                        
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 id="file-name" class="text-base font-medium text-gray-900">filename.mp4</h4>
                                    <p id="file-info" class="text-sm text-gray-500">0 MB • 00:00</p>
                                </div>
                                
                                <button type="button" id="remove-file" class="text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="mt-2">
                                <div class="upload-progress">
                                    <div id="upload-progress-bar" class="upload-progress-bar w-0"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span id="upload-status" class="text-xs text-gray-500">รอการอัปโหลด</span>
                                    <span id="upload-percentage" class="text-xs font-medium text-indigo-600">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @error('video')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-end">
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary px-4 py-2 mr-3">
                    <i class="fas fa-arrow-left mr-1"></i> ยกเลิก
                </a>
                <button type="submit" id="submit-btn" class="btn btn-primary px-4 py-2">
                    <span id="btn-text">ถัดไป</span>
                    <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('upload-area');
        const videoInput = document.getElementById('video');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileInfo = document.getElementById('file-info');
        const removeFileBtn = document.getElementById('remove-file');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const progressBar = document.getElementById('upload-progress-bar');
        const uploadStatus = document.getElementById('upload-status');
        const uploadPercentage = document.getElementById('upload-percentage');
        
        // คลิกที่ upload area เพื่อเลือกไฟล์
        uploadArea.addEventListener('click', function() {
            videoInput.click();
        });
        
        // เมื่อเลือกไฟล์
        videoInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        // Drag & Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Highlight drop area when dragging over it
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function() {
                uploadArea.classList.add('dragging');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function() {
                uploadArea.classList.remove('dragging');
            }, false);
        });
        
        // Handle dropped files
        uploadArea.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            handleFiles(files);
        }, false);
        
        // ลบไฟล์ที่เลือก
        removeFileBtn.addEventListener('click', function() {
            resetUpload();
        });
        
        // ส่งฟอร์ม
        uploadForm.addEventListener('submit', function(e) {
            if (videoInput.files.length === 0) {
                e.preventDefault();
                alert('กรุณาเลือกไฟล์วิดีโอก่อนดำเนินการต่อ');
                return;
            }
            
            // แสดง progress bar
            simulateUpload();
            
            // ปิดปุ่ม submit
            submitBtn.disabled = true;
            btnText.textContent = 'กำลังอัปโหลด...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        });
        
        // จัดการไฟล์ที่เลือก
        function handleFiles(files) {
            if (files.length === 0) return;
            
            const file = files[0];
            
            // ตรวจสอบประเภทไฟล์
            if (!file.type.match('video.*')) {
                alert('กรุณาเลือกไฟล์วิดีโอเท่านั้น');
                return;
            }
            
            // ตรวจสอบขนาดไฟล์ (100MB)
            if (file.size > 100 * 1024 * 1024) {
                alert('ไฟล์ขนาดใหญ่เกินไป กรุณาเลือกไฟล์ขนาดไม่เกิน 100MB');
                return;
            }
            
            // แสดงข้อมูลไฟล์
            fileName.textContent = file.name;
            
            // แสดงขนาดไฟล์
            const fileSize = formatFileSize(file.size);
            
            // ดึงความยาววิดีโอ
            const video = document.createElement('video');
            video.preload = 'metadata';
            
            video.onloadedmetadata = function() {
                const duration = formatDuration(video.duration);
                fileInfo.textContent = `${fileSize} • ${duration}`;
                
                // เปลี่ยนสถานะ upload area
                uploadArea.classList.add('active');
                
                // แสดง preview
                filePreview.classList.remove('hidden');
            };
            
            video.src = URL.createObjectURL(file);
        }
        
        // รีเซ็ตการอัปโหลด
        function resetUpload() {
            videoInput.value = '';
            filePreview.classList.add('hidden');
            uploadArea.classList.remove('active', 'dragging');
            progressBar.style.width = '0%';
            uploadStatus.textContent = 'รอการอัปโหลด';
            uploadPercentage.textContent = '0%';
            
            // เปิดปุ่ม submit
            submitBtn.disabled = false;
            btnText.textContent = 'ถัดไป';
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        
        // จำลองการอัปโหลด
        function simulateUpload() {
            let progress = 0;
            uploadStatus.textContent = 'กำลังอัปโหลด...';
            
            const interval = setInterval(function() {
                progress += Math.random() * 5;
                
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    uploadStatus.textContent = 'อัปโหลดเสร็จสิ้น';
                }
                
                progressBar.style.width = `${progress}%`;
                uploadPercentage.textContent = `${Math.round(progress)}%`;
            }, 200);
        }
        
        // ฟอร์แมตขนาดไฟล์
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // ฟอร์แมตความยาววิดีโอ
        function formatDuration(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            
            return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
    });
</script>
@endsection