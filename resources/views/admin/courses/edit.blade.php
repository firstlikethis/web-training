@extends('layouts.admin')

@section('title', 'แก้ไขคอร์ส - Admin Dashboard')

@section('page-title', 'แก้ไขคอร์ส')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">แก้ไขคอร์ส</h2>
            <p class="text-gray-600">แก้ไขข้อมูลสำหรับคอร์ส {{ $course->title }}</p>
        </div>
        
        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">ชื่อคอร์ส</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-book text-gray-400"></i>
                        </div>
                        <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $course->description) }}</textarea>
                    
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-folder text-gray-400"></i>
                        </div>
                        <select name="category_id" id="category_id" 
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- ไม่มีหมวดหมู่ --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    @error('category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">รูปภาพปก</label>
                    
                    @if($course->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-32 w-auto object-cover rounded-lg">
                            <p class="text-sm text-gray-500 mt-1">รูปภาพปัจจุบัน</p>
                        </div>
                    @endif
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
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
                            <p class="text-sm text-gray-700">ความยาว: {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที</p>
                        </div>
                    @endif
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-file-video text-gray-400"></i>
                        </div>
                        <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">อัปโหลดเฉพาะเมื่อต้องการเปลี่ยนวิดีโอ</p>
                    
                    @error('video')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">เปิดใช้งานคอร์ส</label>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    ยกเลิก
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>
    
    <!-- ส่วนของการจัดการคำถาม -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">คำถามทั้งหมดในคอร์สนี้</h2>
                <p class="text-gray-600">มีคำถามทั้งหมด {{ $course->questions->count() }} ข้อ</p>
            </div>
            <div class="mt-3 md:mt-0">
                <button type="button" id="add-question-btn" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> เพิ่มคำถามใหม่
                </button>
            </div>
        </div>
        
        <!-- แสดงคำถามที่มีอยู่แล้ว -->
        <div class="space-y-4 mb-6">
            @forelse($course->questions as $question)
                <div class="border rounded-xl overflow-hidden bg-gray-50">
                    <div class="bg-indigo-50 p-4 border-b flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">{{ $question->question_text }}</h3>
                        <div class="flex space-x-2">
                            <button type="button" class="edit-question text-blue-600 hover:text-blue-800 px-2 py-1" data-id="{{ $question->id }}">
                                <i class="fas fa-edit"></i>
                                <span class="ml-1 hidden md:inline">แก้ไข</span>
                            </button>
                            
                            <form method="POST" action="{{ route('admin.courses.questions.delete', $question) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 px-2 py-1" 
                                        onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคำถามนี้?')">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="ml-1 hidden md:inline">ลบ</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="text-sm font-medium text-gray-700">แสดงที่เวลา:</span>
                                <span class="text-sm text-gray-700 ml-1">{{ floor($question->time_to_show / 60) }}:{{ str_pad($question->time_to_show % 60, 2, '0', STR_PAD_LEFT) }} นาที</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">เวลาจำกัดในการตอบ:</span>
                                <span class="text-sm text-gray-700 ml-1">{{ $question->time_limit_seconds }} วินาที</span>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <p class="font-medium text-gray-700 mb-2">ตัวเลือกคำตอบ:</p>
                            <ul class="space-y-2">
                                @foreach($question->answers as $answer)
                                    <li class="p-2 rounded-lg {{ $answer->is_correct ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                                        {{ $answer->answer_text }}
                                        @if($answer->is_correct)
                                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-green-500 text-white">คำตอบที่ถูก</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-gray-50 rounded-xl">
                    <div class="text-gray-400 mb-3">
                        <i class="fas fa-question-circle text-5xl"></i>
                    </div>
                    <p class="text-gray-600">ยังไม่มีคำถามในคอร์สนี้</p>
                    <p class="text-sm text-gray-500 mt-1">คลิกที่ปุ่ม "เพิ่มคำถามใหม่" เพื่อเริ่มสร้างคำถาม</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Modal สำหรับเพิ่มคำถามใหม่ -->
    <div id="question-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-2xl w-full mx-4">
            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-800">เพิ่มคำถามใหม่</h3>
                <p class="text-gray-500 text-sm mt-1">สร้างคำถามใหม่สำหรับคอร์สนี้</p>
            </div>
            
            <form id="add-question-form" method="POST" action="{{ route('admin.courses.questions.add', $course) }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำถาม</label>
                        <textarea name="question_text" id="question_text" rows="2" required
                                  class="w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="กรอกคำถามที่นี่..."></textarea>
                    </div>
                    
                    <div>
                        <label for="time_to_show" class="block text-sm font-medium text-gray-700 mb-1">เวลาที่แสดงคำถาม (วินาที)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <input type="number" name="time_to_show" id="time_to_show" min="0" value="0" required
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>
                    
                    <div>
                        <label for="time_limit_seconds" class="block text-sm font-medium text-gray-700 mb-1">เวลาจำกัดในการตอบ (วินาที)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hourglass-half text-gray-400"></i>
                            </div>
                            <input type="number" name="time_limit_seconds" id="time_limit_seconds" min="5" value="30" required
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="30">
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-md font-medium text-gray-800 mb-2">ตัวเลือกคำตอบ</h4>
                    
                    <div id="answers-container" class="space-y-3">
                        <!-- คำตอบเริ่มต้น 2 ข้อ -->
                        <div class="answer-item grid grid-cols-12 gap-2">
                            <div class="col-span-10">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-check-circle text-gray-400"></i>
                                    </div>
                                    <input type="text" name="answers[0][answer_text]" placeholder="ข้อความคำตอบ" required
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <input type="checkbox" name="answers[0][is_correct]" value="1" checked
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 mr-2 answer-correct">
                                <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                            </div>
                        </div>
                        
                        <div class="answer-item grid grid-cols-12 gap-2">
                            <div class="col-span-10">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-times-circle text-gray-400"></i>
                                    </div>
                                    <input type="text" name="answers[1