@extends('layouts.admin')

@section('title', 'แก้ไขคอร์ส - Admin Dashboard')

@section('page-title', 'แก้ไขคอร์ส')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">ชื่อคอร์ส</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $course->description) }}</textarea>
                    
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">รูปภาพปก</label>
                    
                    @if($course->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-32 w-auto object-cover rounded">
                            <p class="text-sm text-gray-500 mt-1">รูปภาพปัจจุบัน</p>
                        </div>
                    @endif
                    
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">อัปโหลดเฉพาะเมื่อต้องการเปลี่ยนรูปภาพ</p>
                    
                    @error('thumbnail')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="video" class="block text-sm font-medium text-gray-700 mb-1">ไฟล์วิดีโอ (MP4, WebM, Ogg)</label>
                    
                    @if($course->video_path)
                        <div class="mb-2">
                            <p class="text-sm text-gray-500">มีไฟล์วิดีโออยู่แล้ว</p>
                        </div>
                    @endif
                    
                    <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">อัปโหลดเฉพาะเมื่อต้องการเปลี่ยนวิดีโอ</p>
                    
                    @error('video')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="duration_seconds" class="block text-sm font-medium text-gray-700 mb-1">ความยาววิดีโอ (วินาที)</label>
                    <input type="number" name="duration_seconds" id="duration_seconds" min="0" value="{{ old('duration_seconds', $course->duration_seconds) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    
                    @error('duration_seconds')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">เปิดใช้งานคอร์ส</label>
                </div>
            </div>
            
            <div class="flex items-center justify-end">
                <a href="{{ route('admin.courses.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">ยกเลิก</a>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">บันทึกการเปลี่ยนแปลง</button>
            </div>
        </form>
    </div>
@endsection