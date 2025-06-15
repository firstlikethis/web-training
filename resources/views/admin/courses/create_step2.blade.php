@extends('layouts.admin')

@section('title', 'รายละเอียดคอร์ส - ขั้นตอนที่ 2')

@section('page-title', 'กรอกรายละเอียดคอร์ส - Draft')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">รายละเอียดคอร์ส (สถานะ: Draft)</h2>
            <p class="text-gray-600 mb-4">คุณสามารถบันทึกร่างคอร์สไว้ก่อนและมาแก้ไขเพิ่มเติมในภายหลังได้ หรือกดเผยแพร่เมื่อกรอกข้อมูลครบถ้วน</p>
        </div>
        
        <!-- แสดงตัวอย่างวิดีโอ -->
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2">ตัวอย่างวิดีโอ</h3>
            <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden">
                @if($course->video_path)
                    <video 
                        class="w-full h-auto"
                        controls
                        controlsList="nodownload"
                    >
                        <source src="{{ asset('storage/' . $course->video_path) }}" type="video/mp4">
                        เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ HTML5
                    </video>
                    <p class="mt-2 text-sm text-gray-600">ความยาววิดีโอ: {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที</p>
                @endif
            </div>
        </div>
        
        <form action="{{ route('admin.courses.store_details', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
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
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    
                    @error('thumbnail')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">เปิดใช้งานคอร์ส</label>
                </div>
            </div>
            
            <!-- ส่วนของคำถาม -->
            <div class="mt-8 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">คำถามในคอร์ส</h2>
                
                <div id="questions-container">
                    <!-- คำถามจะถูกเพิ่มที่นี่ด้วย JavaScript -->
                    @if(old('questions'))
                        @foreach(old('questions') as $qIndex => $question)
                            <div class="question-item bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-medium">คำถามที่ {{ $qIndex + 1 }}</h3>
                                    <button type="button" class="remove-question text-red-600 hover:text-red-800">
                                        <span class="text-lg">×</span> ลบคำถามนี้
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำถาม</label>
                                        <textarea name="questions[{{ $qIndex }}][question_text]" rows="2" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>{{ $question['question_text'] }}</textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">เวลาที่แสดงคำถาม (วินาที)</label>
                                        <input type="number" name="questions[{{ $qIndex }}][time_to_show]" min="0" value="{{ $question['time_to_show'] }}" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">เวลาจำกัดในการตอบ (วินาที)</label>
                                        <input type="number" name="questions[{{ $qIndex }}][time_limit_seconds]" min="5" value="{{ $question['time_limit_seconds'] }}" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="text-md font-medium text-gray-800 mb-2">ตัวเลือกคำตอบ</h4>
                                    
                                    <div class="answers-container space-y-3">
                                        @if(isset($question['answers']))
                                            @foreach($question['answers'] as $aIndex => $answer)
                                                <div class="answer-item grid grid-cols-12 gap-2">
                                                    <div class="col-span-10">
                                                        <input type="text" name="questions[{{ $qIndex }}][answers][{{ $aIndex }}][answer_text]" 
                                                            value="{{ $answer['answer_text'] }}" placeholder="ข้อความคำตอบ" required
                                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                    </div>
                                                    <div class="col-span-2 flex items-center">
                                                        <input type="checkbox" name="questions[{{ $qIndex }}][answers][{{ $aIndex }}][is_correct]" 
                                                            value="1" {{ isset($answer['is_correct']) ? 'checked' : '' }}
                                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                                                        <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Default empty answers if no old input -->
                                            <div class="answer-item grid grid-cols-12 gap-2">
                                                <div class="col-span-10">
                                                    <input type="text" name="questions[{{ $qIndex }}][answers][0][answer_text]" placeholder="ข้อความคำตอบ" required
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                </div>
                                                <div class="col-span-2 flex items-center">
                                                    <input type="checkbox" name="questions[{{ $qIndex }}][answers][0][is_correct]" value="1" checked
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                                                    <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                                                </div>
                                            </div>
                                            <div class="answer-item grid grid-cols-12 gap-2">
                                                <div class="col-span-10">
                                                    <input type="text" name="questions[{{ $qIndex }}][answers][1][answer_text]" placeholder="ข้อความคำตอบ" required
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                </div>
                                                <div class="col-span-2 flex items-center">
                                                    <input type="checkbox" name="questions[{{ $qIndex }}][answers][1][is_correct]" value="1"
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                                                    <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <button type="button" class="add-answer mt-2 bg-gray-200 text-gray-700 py-1 px-3 rounded hover:bg-gray-300">
                                        + เพิ่มตัวเลือก
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <button type="button" id="add-question" class="mt-4 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                    + เพิ่มคำถามใหม่
                </button>
                
                @error('questions')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-end mt-8">
                <form method="POST" action="{{ route('admin.courses.cancel_draft', $course) }}" class="mr-4">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิก? วิดีโอและข้อมูลทั้งหมดจะถูกลบ')">ยกเลิก</button>
                </form>
                
                <div class="flex space-x-4">
                    <button type="submit" name="save_draft" value="1" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">บันทึกร่าง</button>
                    <button type="submit" name="publish" value="1" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">เผยแพร่คอร์ส</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Template สำหรับคำถามใหม่ (จะถูกซ่อนไว้) -->
    <template id="question-template">
        <div class="question-item bg-gray-50 p-4 rounded-lg mb-4">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-medium">คำถามใหม่</h3>
                <button type="button" class="remove-question text-red-600 hover:text-red-800">
                    <span class="text-lg">×</span> ลบคำถามนี้
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำถาม</label>
                    <textarea name="questions[INDEX][question_text]" rows="2" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เวลาที่แสดงคำถาม (วินาที)</label>
                    <input type="number" name="questions[INDEX][time_to_show]" min="0" value="0" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เวลาจำกัดในการตอบ (วินาที)</label>
                    <input type="number" name="questions[INDEX][time_limit_seconds]" min="5" value="30" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>
            </div>
            
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">ตัวเลือกคำตอบ</h4>
                
                <div class="answers-container space-y-3">
                    <!-- คำตอบเริ่มต้น 2 ข้อ -->
                    <div class="answer-item grid grid-cols-12 gap-2">
                        <div class="col-span-10">
                            <input type="text" name="questions[INDEX][answers][0][answer_text]" placeholder="ข้อความคำตอบ" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        <div class="col-span-2 flex items-center">
                            <input type="checkbox" name="questions[INDEX][answers][0][is_correct]" value="1" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                            <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                        </div>
                    </div>
                    
                    <div class="answer-item grid grid-cols-12 gap-2">
                        <div class="col-span-10">
                            <input type="text" name="questions[INDEX][answers][1][answer_text]" placeholder="ข้อความคำตอบ" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        <div class="col-span-2 flex items-center">
                            <input type="checkbox" name="questions[INDEX][answers][1][is_correct]" value="1"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                            <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="add-answer mt-2 bg-gray-200 text-gray-700 py-1 px-3 rounded hover:bg-gray-300">
                    + เพิ่มตัวเลือก
                </button>
            </div>
        </div>
    </template>
    
    <!-- Template สำหรับตัวเลือกคำตอบใหม่ -->
    <template id="answer-template">
        <div class="answer-item grid grid-cols-12 gap-2">
            <div class="col-span-10">
                <input type="text" name="questions[QUESTION_INDEX][answers][ANSWER_INDEX][answer_text]" placeholder="ข้อความคำตอบ" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="col-span-2 flex items-center">
                <input type="checkbox" name="questions[QUESTION_INDEX][answers][ANSWER_INDEX][is_correct]" value="1"
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2 answer-correct">
                <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
            </div>
        </div>
    </template>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // สำหรับการเพิ่มคำถาม
        const questionsContainer = document.getElementById('questions-container');
        const addQuestionButton = document.getElementById('add-question');
        const questionTemplate = document.getElementById('question-template');
        const answerTemplate = document.getElementById('answer-template');
        
        // นับจำนวนคำถามที่มีอยู่แล้ว (กรณี validation error)
        let questionIndex = document.querySelectorAll('.question-item').length;
        
        // เพิ่มคำถามใหม่
        addQuestionButton.addEventListener('click', function() {
            const questionItem = questionTemplate.content.cloneNode(true);
            
            // อัปเดต index ของคำถาม
            const questionFields = questionItem.querySelectorAll('[name^="questions[INDEX]"]');
            questionFields.forEach(field => {
                field.name = field.name.replace('INDEX', questionIndex);
            });
            
            // เพิ่ม event listener สำหรับปุ่มลบคำถาม
            const removeButton = questionItem.querySelector('.remove-question');
            removeButton.addEventListener('click', function() {
                this.closest('.question-item').remove();
                updateQuestionIndices();
            });
            
            // เพิ่ม event listener สำหรับปุ่มเพิ่มตัวเลือก
            const addAnswerButton = questionItem.querySelector('.add-answer');
            let answerIndex = 2; // เริ่มจาก 2 เพราะมีตัวเลือกเริ่มต้น 2 ข้อ
            
            addAnswerButton.addEventListener('click', function() {
                const answerItem = answerTemplate.content.cloneNode(true);
                const answerFields = answerItem.querySelectorAll('[name^="questions[QUESTION_INDEX]"]');
                
                answerFields.forEach(field => {
                    field.name = field.name.replace('QUESTION_INDEX', questionIndex).replace('ANSWER_INDEX', answerIndex);
                });
                
                const answerCorrectCheckbox = answerItem.querySelector('.answer-correct');
                answerCorrectCheckbox.addEventListener('change', function() {
                    handleCorrectAnswer(this);
                });
                
                this.previousElementSibling.appendChild(answerItem);
                answerIndex++;
            });
            
            // เพิ่ม event listener สำหรับ checkbox คำตอบที่ถูก
            const correctCheckboxes = questionItem.querySelectorAll('.answer-correct');
            correctCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    handleCorrectAnswer(this);
                });
            });
            
            questionsContainer.appendChild(questionItem);
            questionIndex++;
        });
        
        // ฟังก์ชันสำหรับจัดการเมื่อเลือกคำตอบที่ถูก
        function handleCorrectAnswer(checkbox) {
            if (checkbox.checked) {
                // ล้างการเลือกคำตอบที่ถูกอื่นๆ ในคำถามเดียวกัน
                const questionItem = checkbox.closest('.question-item');
                const otherCheckboxes = questionItem.querySelectorAll('.answer-correct');
                
                otherCheckboxes.forEach(otherCheckbox => {
                    if (otherCheckbox !== checkbox) {
                        otherCheckbox.checked = false;
                    }
                });
            }
        }
        
        // ฟังก์ชันสำหรับอัปเดต index ของคำถามหลังจากลบคำถาม
        function updateQuestionIndices() {
            const questionItems = document.querySelectorAll('.question-item');
            
            questionItems.forEach((item, index) => {
                const questionFields = item.querySelectorAll(`[name^="questions["]`);
                
                questionFields.forEach(field => {
                    // ใช้ regex เพื่อแทนที่ index ของคำถาม
                    field.name = field.name.replace(/questions\[\d+\]/, `questions[${index}]`);
                });
            });
            
            // อัปเดต questionIndex global
            questionIndex = questionItems.length;
        }
        
        // ตั้งค่า event handlers สำหรับปุ่มลบคำถามและเพิ่มตัวเลือกที่มีอยู่แล้ว (กรณี validation error)
        const existingRemoveButtons = document.querySelectorAll('.remove-question');
        existingRemoveButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.question-item').remove();
                updateQuestionIndices();
            });
        });
        
        const existingAddAnswerButtons = document.querySelectorAll('.add-answer');
        existingAddAnswerButtons.forEach((button, qIndex) => {
            // หาจำนวนคำตอบที่มีอยู่แล้วในคำถามนี้
            const answerCount = button.previousElementSibling.querySelectorAll('.answer-item').length;
            let nextAnswerIndex = answerCount;
            
            button.addEventListener('click', function() {
                const answerItem = answerTemplate.content.cloneNode(true);
                const answerFields = answerItem.querySelectorAll('[name^="questions[QUESTION_INDEX]"]');
                
                answerFields.forEach(field => {
                    field.name = field.name.replace('QUESTION_INDEX', qIndex).replace('ANSWER_INDEX', nextAnswerIndex);
                });
                
                const answerCorrectCheckbox = answerItem.querySelector('.answer-correct');
                answerCorrectCheckbox.addEventListener('change', function() {
                    handleCorrectAnswer(this);
                });
                
                this.previousElementSibling.appendChild(answerItem);
                nextAnswerIndex++;
            });
        });
        
        // ตั้งค่า event handlers สำหรับ checkboxes ที่มีอยู่แล้ว
        const existingCorrectCheckboxes = document.querySelectorAll('.answer-correct');
        existingCorrectCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                handleCorrectAnswer(this);
            });
        });
        
        // ตรวจสอบฟอร์มก่อนส่ง
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            const isPublishing = e.submitter && e.submitter.name === 'publish';
            
            if (isPublishing) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    alert('กรุณากรอกข้อมูลให้ครบถ้วนก่อนเผยแพร่คอร์ส');
                    return;
                }
                
                // ตรวจสอบชื่อคอร์ส
                const title = document.getElementById('title').value.trim();
                if (!title) {
                    e.preventDefault();
                    alert('กรุณากรอกชื่อคอร์สก่อนเผยแพร่');
                    return;
                }
            }
        });
    });
</script>
@endsection