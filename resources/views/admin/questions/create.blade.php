@extends('layouts.admin')

@section('title', 'สร้างคำถามใหม่ - Admin Dashboard')

@section('page-title', 'สร้างคำถามใหม่')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.questions.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">คอร์ส</label>
                    <select name="course_id" id="course_id" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                    
                    @error('course_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำถาม</label>
                    <textarea name="question_text" id="question_text" rows="3" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('question_text') }}</textarea>
                    
                    @error('question_text')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="time_to_show" class="block text-sm font-medium text-gray-700 mb-1">เวลาที่แสดงคำถาม (วินาที)</label>
                    <input type="number" name="time_to_show" id="time_to_show" min="0" value="{{ old('time_to_show', 0) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    
                    @error('time_to_show')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="time_limit_seconds" class="block text-sm font-medium text-gray-700 mb-1">เวลาจำกัดในการตอบ (วินาที)</label>
                    <input type="number" name="time_limit_seconds" id="time_limit_seconds" min="5" value="{{ old('time_limit_seconds', 30) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    
                    @error('time_limit_seconds')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">เปิดใช้งานคำถาม</label>
                </div>
            </div>
            
            <!-- ตัวเลือกคำตอบ -->
            <div class="mt-8 mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">ตัวเลือกคำตอบ</h3>
                
                <div id="answers-container">
                    @if(old('answers'))
                        @foreach(old('answers') as $index => $answer)
                            <div class="answer-item grid grid-cols-12 gap-2 mb-3">
                                <div class="col-span-10">
                                    <input type="text" name="answers[{{ $index }}][answer_text]" value="{{ $answer['answer_text'] ?? '' }}" 
                                           placeholder="ข้อความคำตอบ" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <input type="checkbox" name="answers[{{ $index }}][is_correct]" value="1" {{ isset($answer['is_correct']) && $answer['is_correct'] ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                                    <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- เพิ่มตัวเลือกเริ่มต้น 2 ตัวเลือก -->
                        <div class="answer-item grid grid-cols-12 gap-2 mb-3">
                            <div class="col-span-10">
                                <input type="text" name="answers[0][answer_text]" placeholder="ข้อความคำตอบ" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <input type="checkbox" name="answers[0][is_correct]" value="1" checked
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                                <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                            </div>
                        </div>
                        
                        <div class="answer-item grid grid-cols-12 gap-2 mb-3">
                            <div class="col-span-10">
                                <input type="text" name="answers[1][answer_text]" placeholder="ข้อความคำตอบ" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <input type="checkbox" name="answers[1][is_correct]" value="1"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                                <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                            </div>
                        </div>
                    @endif
                </div>
                
                <button type="button" id="add-answer" class="mt-2 bg-gray-200 text-gray-700 py-1 px-3 rounded hover:bg-gray-300">
                    + เพิ่มตัวเลือก
                </button>
                
                @error('answers')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-end">
                <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">ยกเลิก</a>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">สร้างคำถาม</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addAnswerButton = document.getElementById('add-answer');
        const answersContainer = document.getElementById('answers-container');
        let answerIndex = document.querySelectorAll('.answer-item').length;
        
        addAnswerButton.addEventListener('click', function() {
            const answerItem = document.createElement('div');
            answerItem.className = 'answer-item grid grid-cols-12 gap-2 mb-3';
            answerItem.innerHTML = `
                <div class="col-span-10">
                    <input type="text" name="answers[${answerIndex}][answer_text]" placeholder="ข้อความคำตอบ" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div class="col-span-2 flex items-center">
                    <input type="checkbox" name="answers[${answerIndex}][is_correct]" value="1"
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                    <label class="text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                </div>
            `;
            
            answersContainer.appendChild(answerItem);
            answerIndex++;
        });
        
        // ป้องกันการเลือกคำตอบที่ถูกมากกว่า 1 ข้อ
        answersContainer.addEventListener('change', function(e) {
            if (e.target.type === 'checkbox' && e.target.checked) {
                const checkboxes = answersContainer.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    if (checkbox !== e.target) {
                        checkbox.checked = false;
                    }
                });
            }
        });
    });
</script>
@endsection