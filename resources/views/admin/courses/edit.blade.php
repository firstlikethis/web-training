@extends('layouts.admin')

@section('title', 'แก้ไขคอร์ส - Admin Dashboard')

@section('page-title', 'แก้ไขคอร์ส')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
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
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                    <select name="category_id" id="category_id" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">-- ไม่มีหมวดหมู่ --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    @error('category_id')
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
                            <p class="text-sm text-gray-700">ความยาว: {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที</p>
                        </div>
                    @endif
                    
                    <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">อัปโหลดเฉพาะเมื่อต้องการเปลี่ยนวิดีโอ (ระบบจะคำนวณความยาววิดีโอใหม่โดยอัตโนมัติด้วย getID3)</p>
                    
                    @error('video')
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
    
    <!-- ส่วนของการจัดการคำถาม -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">คำถามทั้งหมดในคอร์สนี้</h2>
            <button type="button" id="add-question-btn" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                + เพิ่มคำถามใหม่
            </button>
        </div>
        
        <!-- แสดงคำถามที่มีอยู่แล้ว -->
        <div class="space-y-4 mb-6">
            @forelse($course->questions as $question)
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-50 p-4 border-b flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">{{ $question->question_text }}</h3>
                        <div class="flex space-x-2">
                            <button type="button" class="edit-question text-blue-600 hover:text-blue-800" data-id="{{ $question->id }}">แก้ไข</button>
                            <form method="POST" action="{{ route('admin.courses.questions.delete', $question) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคำถามนี้?')">ลบ</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <p class="text-sm text-gray-500">แสดงที่เวลา: {{ floor($question->time_to_show / 60) }}:{{ str_pad($question->time_to_show % 60, 2, '0', STR_PAD_LEFT) }} นาที</p>
                        <p class="text-sm text-gray-500">เวลาจำกัดในการตอบ: {{ $question->time_limit_seconds }} วินาที</p>
                        
                        <div class="mt-3">
                            <p class="font-medium text-gray-700 mb-2">ตัวเลือกคำตอบ:</p>
                            <ul class="space-y-2">
                                @foreach($question->answers as $answer)
                                    <li class="p-2 rounded {{ $answer->is_correct ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
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
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">ยังไม่มีคำถามในคอร์สนี้</p>
                    <p class="text-sm text-gray-500 mt-1">คลิกที่ปุ่ม "เพิ่มคำถามใหม่" เพื่อเริ่มสร้างคำถาม</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Modal สำหรับเพิ่มคำถามใหม่ -->
    <div id="question-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-2xl w-full mx-4">
            <h3 class="text-xl font-medium text-gray-800 mb-4">เพิ่มคำถามใหม่</h3>
            
            <form id="add-question-form" method="POST" action="{{ route('admin.courses.questions.add', $course) }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำถาม</label>
                        <textarea name="question_text" id="question_text" rows="2" required
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    </div>
                    
                    <div>
                        <label for="time_to_show" class="block text-sm font-medium text-gray-700 mb-1">เวลาที่แสดงคำถาม (วินาที)</label>
                        <input type="number" name="time_to_show" id="time_to_show" min="0" value="0" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="time_limit_seconds" class="block text-sm font-medium text-gray-700 mb-1">เวลาจำกัดในการตอบ (วินาที)</label>
                        <input type="number" name="time_limit_seconds" id="time_limit_seconds" min="5" value="30" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-md font-medium text-gray-800 mb-2">ตัวเลือกคำตอบ</h4>
                    
                    <div id="answers-container" class="space-y-3">
                        <!-- คำตอบเริ่มต้น 2 ข้อ -->
                        <div class="answer-item grid grid-cols-12 gap-2">
                            <div class="col-span-10">
                                <input type="text" name="answers[0][answer_text]" placeholder="ข้อความคำตอบ" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <input type="checkbox" name="answers[0][is_correct]" value="1" checked
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                                <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                            </div>
                        </div>
                        
                        <div class="answer-item grid grid-cols-12 gap-2">
                            <div class="col-span-10">
                                <input type="text" name="answers[1][answer_text]" placeholder="ข้อความคำตอบ" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <input type="checkbox" name="answers[1][is_correct]" value="1"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                                <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-answer" class="mt-2 bg-gray-200 text-gray-700 py-1 px-3 rounded hover:bg-gray-300">
                        + เพิ่มตัวเลือก
                    </button>
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="button" id="close-modal" class="text-gray-600 hover:text-gray-800 mr-4">ยกเลิก</button>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">บันทึกคำถาม</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('question-modal');
        const addQuestionBtn = document.getElementById('add-question-btn');
        const closeModalBtn = document.getElementById('close-modal');
        const addAnswerBtn = document.getElementById('add-answer');
        const answersContainer = document.getElementById('answers-container');
        
        let answerIndex = 2; // เริ่มจาก 2 เพราะมีตัวเลือกเริ่มต้น 2 ข้อ
        
        // เปิด Modal
        addQuestionBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });
        
        // ปิด Modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // ปิด Modal เมื่อคลิกพื้นหลัง
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
        
        // เพิ่มตัวเลือกคำตอบ
        addAnswerBtn.addEventListener('click', function() {
            const answerItem = document.createElement('div');
            answerItem.className = 'answer-item grid grid-cols-12 gap-2';
            answerItem.innerHTML = `
                <div class="col-span-10">
                    <input type="text" name="answers[${answerIndex}][answer_text]" placeholder="ข้อความคำตอบ" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div class="col-span-2 flex items-center">
                    <input type="checkbox" name="answers[${answerIndex}][is_correct]" value="1"
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                    <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                </div>
            `;
            
            // เพิ่ม event listener สำหรับ checkbox
            const checkbox = answerItem.querySelector('.answer-correct');
            checkbox.addEventListener('change', function() {
                handleCorrectAnswer(this);
            });
            
            answersContainer.appendChild(answerItem);
            answerIndex++;
        });
        
        // เพิ่ม event listener สำหรับ checkbox คำตอบที่ถูกที่มีอยู่แล้ว
        const correctCheckboxes = document.querySelectorAll('.answer-correct');
        correctCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                handleCorrectAnswer(this);
            });
        });
        
        // ฟังก์ชันสำหรับจัดการเมื่อเลือกคำตอบที่ถูก
        function handleCorrectAnswer(checkbox) {
            if (checkbox.checked) {
                // ล้างการเลือกคำตอบที่ถูกอื่นๆ
                const otherCheckboxes = document.querySelectorAll('.answer-correct');
                
                otherCheckboxes.forEach(otherCheckbox => {
                    if (otherCheckbox !== checkbox) {
                        otherCheckbox.checked = false;
                    }
                });
            }
        }
    });
</script>
@endsection