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
                                    <input type="text" name="answers[1][answer_text]" placeholder="ข้อความคำตอบ" required
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <input type="checkbox" name="answers[1][is_correct]" value="1"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 mr-2 answer-correct">
                                <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-answer-btn" class="mt-3 px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-plus mr-1"></i> เพิ่มตัวเลือกคำตอบ
                    </button>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" id="close-modal-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> บันทึกคำถาม
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal สำหรับแก้ไขคำถาม -->
    <div id="edit-question-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-2xl w-full mx-4">
            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-800">แก้ไขคำถาม</h3>
                <p class="text-gray-500 text-sm mt-1">แก้ไขข้อมูลคำถามและตัวเลือกคำตอบ</p>
            </div>
            
            <form id="edit-question-form" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label for="edit_question_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำถาม</label>
                        <textarea name="question_text" id="edit_question_text" rows="2" required
                                  class="w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="กรอกคำถามที่นี่..."></textarea>
                    </div>
                    
                    <div>
                        <label for="edit_time_to_show" class="block text-sm font-medium text-gray-700 mb-1">เวลาที่แสดงคำถาม (วินาที)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <input type="number" name="time_to_show" id="edit_time_to_show" min="0" required
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_time_limit_seconds" class="block text-sm font-medium text-gray-700 mb-1">เวลาจำกัดในการตอบ (วินาที)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hourglass-half text-gray-400"></i>
                            </div>
                            <input type="number" name="time_limit_seconds" id="edit_time_limit_seconds" min="5" required
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-md font-medium text-gray-800 mb-2">ตัวเลือกคำตอบ</h4>
                    
                    <div id="edit-answers-container" class="space-y-3">
                        <!-- ตัวเลือกคำตอบจะถูกเพิ่มด้วย JavaScript -->
                    </div>
                    
                    <button type="button" id="edit-add-answer-btn" class="mt-3 px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-plus mr-1"></i> เพิ่มตัวเลือกคำตอบ
                    </button>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" id="close-edit-modal-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // -- เริ่มส่วนของการจัดการ Modal เพิ่มคำถามใหม่ --
        const questionModal = document.getElementById('question-modal');
        const addQuestionBtn = document.getElementById('add-question-btn');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const addAnswerBtn = document.getElementById('add-answer-btn');
        const answersContainer = document.getElementById('answers-container');
        
        // เปิด Modal เพิ่มคำถามใหม่
        addQuestionBtn.addEventListener('click', function() {
            questionModal.classList.remove('hidden');
        });
        
        // ปิด Modal
        closeModalBtn.addEventListener('click', function() {
            questionModal.classList.add('hidden');
        });
        
        // ปิด Modal เมื่อคลิกพื้นหลัง
        questionModal.addEventListener('click', function(e) {
            if (e.target === questionModal) {
                questionModal.classList.add('hidden');
            }
        });
        
        // เพิ่มตัวเลือกคำตอบใหม่
        let answerCount = 2; // เริ่มจาก 2 เพราะมีตัวเลือกเริ่มต้น 2 ข้อ
        
        addAnswerBtn.addEventListener('click', function() {
            const newAnswerItem = document.createElement('div');
            newAnswerItem.className = 'answer-item grid grid-cols-12 gap-2';
            
            newAnswerItem.innerHTML = `
                <div class="col-span-10">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-times-circle text-gray-400"></i>
                        </div>
                        <input type="text" name="answers[${answerCount}][answer_text]" placeholder="ข้อความคำตอบ" required
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
                <div class="col-span-2 flex items-center">
                    <input type="checkbox" name="answers[${answerCount}][is_correct]" value="1"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 mr-2 answer-correct">
                    <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                    
                    <button type="button" class="ml-auto text-red-500 hover:text-red-700 remove-answer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            answersContainer.appendChild(newAnswerItem);
            
            // เพิ่ม event listener สำหรับปุ่มลบคำตอบ
            const removeBtn = newAnswerItem.querySelector('.remove-answer');
            removeBtn.addEventListener('click', function() {
                newAnswerItem.remove();
            });
            
            // เพิ่ม event listener สำหรับ checkbox คำตอบที่ถูก
            const correctCheckbox = newAnswerItem.querySelector('.answer-correct');
            correctCheckbox.addEventListener('change', function() {
                handleCorrectAnswer(correctCheckbox);
            });
            
            answerCount++;
        });
        
        // ฟังก์ชันจัดการเมื่อเลือกคำตอบที่ถูก
        function handleCorrectAnswer(checkbox) {
            if (checkbox.checked) {
                // ล้างการเลือกคำตอบที่ถูกอื่นๆ
                const allCheckboxes = document.querySelectorAll('.answer-correct');
                allCheckboxes.forEach(function(cb) {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }
        
        // เพิ่ม event listener สำหรับ checkbox คำตอบที่ถูกที่มีอยู่แล้ว
        document.querySelectorAll('.answer-correct').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                handleCorrectAnswer(checkbox);
            });
        });
        
        // -- จบส่วนของการจัดการ Modal เพิ่มคำถามใหม่ --
        
        // -- เริ่มส่วนของการจัดการ Modal แก้ไขคำถาม --
        const editQuestionModal = document.getElementById('edit-question-modal');
        const editQuestionBtns = document.querySelectorAll('.edit-question');
        const closeEditModalBtn = document.getElementById('close-edit-modal-btn');
        const editAddAnswerBtn = document.getElementById('edit-add-answer-btn');
        const editAnswersContainer = document.getElementById('edit-answers-container');
        const editQuestionForm = document.getElementById('edit-question-form');
        
        // รายการคำถามและคำตอบจาก PHP
        const questions = @json($course->questions);
        
        // เปิด Modal แก้ไขคำถาม
        editQuestionBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const questionId = this.dataset.id;
                const question = questions.find(q => q.id == questionId);
                
                if (!question) return;
                
                // กำหนดค่าเริ่มต้นให้กับฟอร์ม
                document.getElementById('edit_question_text').value = question.question_text;
                document.getElementById('edit_time_to_show').value = question.time_to_show;
                document.getElementById('edit_time_limit_seconds').value = question.time_limit_seconds;
                
                // กำหนด action ของฟอร์ม
                editQuestionForm.action = `/admin/questions/${questionId}`;
                
                // เคลียร์ตัวเลือกคำตอบเก่า
                editAnswersContainer.innerHTML = '';
                
                // เพิ่มตัวเลือกคำตอบ
                question.answers.forEach(function(answer, index) {
                    const answerItem = document.createElement('div');
                    answerItem.className = 'answer-item grid grid-cols-12 gap-2';
                    
                    answerItem.innerHTML = `
                        <div class="col-span-10">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-${answer.is_correct ? 'check' : 'times'}-circle text-gray-400"></i>
                                </div>
                                <input type="text" name="edit_answers[${index}][answer_text]" value="${answer.answer_text}" required
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <input type="hidden" name="edit_answers[${index}][id]" value="${answer.id}">
                            </div>
                        </div>
                        <div class="col-span-2 flex items-center">
                            <input type="checkbox" name="edit_answers[${index}][is_correct]" value="1" ${answer.is_correct ? 'checked' : ''}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 mr-2 edit-answer-correct">
                            <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                            
                            ${index > 1 ? `
                                <button type="button" class="ml-auto text-red-500 hover:text-red-700 remove-edit-answer">
                                    <i class="fas fa-times"></i>
                                </button>
                            ` : ''}
                        </div>
                    `;
                    
                    editAnswersContainer.appendChild(answerItem);
                    
                    // เพิ่ม event listener สำหรับปุ่มลบคำตอบ
                    const removeBtn = answerItem.querySelector('.remove-edit-answer');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            answerItem.remove();
                        });
                    }
                    
                    // เพิ่ม event listener สำหรับ checkbox คำตอบที่ถูก
                    const correctCheckbox = answerItem.querySelector('.edit-answer-correct');
                    correctCheckbox.addEventListener('change', function() {
                        handleEditCorrectAnswer(correctCheckbox);
                    });
                });
                
                // แสดง Modal
                editQuestionModal.classList.remove('hidden');
            });
        });
        
        // ปิด Modal แก้ไขคำถาม
        closeEditModalBtn.addEventListener('click', function() {
            editQuestionModal.classList.add('hidden');
        });
        
        // ปิด Modal เมื่อคลิกพื้นหลัง
        editQuestionModal.addEventListener('click', function(e) {
            if (e.target === editQuestionModal) {
                editQuestionModal.classList.add('hidden');
            }
        });
        
        // เพิ่มตัวเลือกคำตอบใหม่ในหน้าแก้ไข
        let editAnswerCount = 0;
        
        editAddAnswerBtn.addEventListener('click', function() {
            // นับจำนวนตัวเลือกคำตอบปัจจุบัน
            editAnswerCount = editAnswersContainer.querySelectorAll('.answer-item').length;
            
            const newAnswerItem = document.createElement('div');
            newAnswerItem.className = 'answer-item grid grid-cols-12 gap-2';
            
            newAnswerItem.innerHTML = `
                <div class="col-span-10">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-times-circle text-gray-400"></i>
                        </div>
                        <input type="text" name="edit_answers[${editAnswerCount}][answer_text]" placeholder="ข้อความคำตอบใหม่" required
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <input type="hidden" name="edit_answers[${editAnswerCount}][id]" value="new">
                    </div>
                </div>
                <div class="col-span-2 flex items-center">
                    <input type="checkbox" name="edit_answers[${editAnswerCount}][is_correct]" value="1"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 mr-2 edit-answer-correct">
                    <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                    
                    <button type="button" class="ml-auto text-red-500 hover:text-red-700 remove-edit-answer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            editAnswersContainer.appendChild(newAnswerItem);
            
            // เพิ่ม event listener สำหรับปุ่มลบคำตอบ
            const removeBtn = newAnswerItem.querySelector('.remove-edit-answer');
            removeBtn.addEventListener('click', function() {
                newAnswerItem.remove();
            });
            
            // เพิ่ม event listener สำหรับ checkbox คำตอบที่ถูก
            const correctCheckbox = newAnswerItem.querySelector('.edit-answer-correct');
            correctCheckbox.addEventListener('change', function() {
                handleEditCorrectAnswer(correctCheckbox);
            });
        });
        
        // ฟังก์ชันจัดการเมื่อเลือกคำตอบที่ถูกในหน้าแก้ไข
        function handleEditCorrectAnswer(checkbox) {
            if (checkbox.checked) {
                // ล้างการเลือกคำตอบที่ถูกอื่นๆ
                const allCheckboxes = document.querySelectorAll('.edit-answer-correct');
                allCheckboxes.forEach(function(cb) {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }
        
        // -- จบส่วนของการจัดการ Modal แก้ไขคำถาม --
        
        // ตรวจสอบฟอร์มก่อนส่ง
        const addQuestionForm = document.getElementById('add-question-form');
        
        addQuestionForm.addEventListener('submit', function(e) {
            // ตรวจสอบว่ามีคำตอบที่ถูกอย่างน้อย 1 ข้อ
            const correctAnswers = document.querySelectorAll('.answer-correct:checked');
            
            if (correctAnswers.length === 0) {
                e.preventDefault();
                alert('กรุณาเลือกคำตอบที่ถูกอย่างน้อย 1 ข้อ');
                return;
            }
            
            // ตรวจสอบว่ามีตัวเลือกคำตอบอย่างน้อย 2 ข้อ
            const answers = document.querySelectorAll('#answers-container .answer-item');
            
            if (answers.length < 2) {
                e.preventDefault();
                alert('กรุณาเพิ่มตัวเลือกคำตอบอย่างน้อย 2 ข้อ');
                return;
            }
        });
        
        editQuestionForm.addEventListener('submit', function(e) {
            // ตรวจสอบว่ามีคำตอบที่ถูกอย่างน้อย 1 ข้อ
            const correctAnswers = document.querySelectorAll('.edit-answer-correct:checked');
            
            if (correctAnswers.length === 0) {
                e.preventDefault();
                alert('กรุณาเลือกคำตอบที่ถูกอย่างน้อย 1 ข้อ');
                return;
            }
            
            // ตรวจสอบว่ามีตัวเลือกคำตอบอย่างน้อย 2 ข้อ
            const answers = document.querySelectorAll('#edit-answers-container .answer-item');
            
            if (answers.length < 2) {
                e.preventDefault();
                alert('กรุณาเพิ่มตัวเลือกคำตอบอย่างน้อย 2 ข้อ');
                return;
            }
        });
        
        // แสดงตัวอย่างรูปภาพที่อัปโหลด
        const thumbnailInput = document.getElementById('thumbnail');
        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewContainer = document.createElement('div');
                        previewContainer.className = 'mt-2';
                        
                        const previewImage = document.createElement('img');
                        previewImage.src = e.target.result;
                        previewImage.className = 'h-32 w-auto object-cover rounded-lg';
                        
                        const previewText = document.createElement('p');
                        previewText.className = 'text-sm text-gray-500 mt-1';
                        previewText.textContent = 'รูปภาพใหม่ที่จะอัปโหลด';
                        
                        previewContainer.appendChild(previewImage);
                        previewContainer.appendChild(previewText);
                        
                        // ลบตัวอย่างเก่า (ถ้ามี)
                        const oldPreview = thumbnailInput.nextElementSibling;
                        if (oldPreview && oldPreview.classList.contains('mt-2')) {
                            oldPreview.remove();
                        }
                        
                        thumbnailInput.parentNode.appendChild(previewContainer);
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection