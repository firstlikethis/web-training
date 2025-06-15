@extends('layouts.admin')

@section('title', 'แก้ไขคำถาม - Admin Dashboard')

@section('page-title', 'แก้ไขคำถาม')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">แก้ไขคำถาม</h2>
            <p class="text-gray-600">แก้ไขข้อมูลคำถามและตัวเลือกคำตอบ</p>
        </div>
        
        <form action="{{ route('admin.questions.update', $question) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">คอร์ส</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-book text-gray-400"></i>
                        </div>
                        <select name="course_id" id="course_id" 
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $question->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    @error('course_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำถาม</label>
                    <textarea name="question_text" id="question_text" rows="3" 
                              class="w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('question_text', $question->question_text) }}</textarea>
                    
                    @error('question_text')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="time_to_show" class="block text-sm font-medium text-gray-700 mb-1">เวลาที่แสดงคำถาม (วินาที)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-clock text-gray-400"></i>
                        </div>
                        <input type="number" name="time_to_show" id="time_to_show" min="0" value="{{ old('time_to_show', $question->time_to_show) }}" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    @error('time_to_show')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="time_limit_seconds" class="block text-sm font-medium text-gray-700 mb-1">เวลาจำกัดในการตอบ (วินาที)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-hourglass-half text-gray-400"></i>
                        </div>
                        <input type="number" name="time_limit_seconds" id="time_limit_seconds" min="5" value="{{ old('time_limit_seconds', $question->time_limit_seconds) }}" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    @error('time_limit_seconds')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $question->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">เปิดใช้งานคำถาม</label>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    ยกเลิก
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>
    
    <!-- ส่วนของการจัดการคำตอบ -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">ตัวเลือกคำตอบ</h2>
            <p class="text-gray-600">จัดการตัวเลือกคำตอบสำหรับคำถามนี้</p>
        </div>
        
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ข้อความคำตอบ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คำตอบที่ถูก</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($question->answers as $answer)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $answer->answer_text }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $answer->is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $answer->is_correct ? 'ถูก' : 'ผิด' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <button type="button" class="text-blue-600 hover:text-blue-900 edit-answer" 
                                            data-id="{{ $answer->id }}" 
                                            data-text="{{ $answer->answer_text }}" 
                                            data-correct="{{ $answer->is_correct ? '1' : '0' }}">
                                        <i class="fas fa-edit"></i>
                                        <span class="ml-1 hidden md:inline">แก้ไข</span>
                                    </button>
                                    
                                    <form method="POST" action="{{ route('admin.questions.answers.destroy', $answer) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคำตอบนี้?')">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="ml-1 hidden md:inline">ลบ</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">ยังไม่มีตัวเลือกคำตอบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- เพิ่มคำตอบใหม่ -->
        <div class="bg-gray-50 p-6 rounded-xl">
            <h3 class="text-lg font-medium text-gray-800 mb-4">เพิ่มคำตอบใหม่</h3>
            
            <form action="{{ route('admin.questions.answers.store', $question) }}" method="POST" id="add-answer-form">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label for="answer_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำตอบใหม่</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-comment-dots text-gray-400"></i>
                            </div>
                            <input type="text" name="answer_text" id="answer_text" 
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="ข้อความคำตอบใหม่">
                        </div>
                        
                        @error('answer_text')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_correct" id="is_correct" value="1"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <label for="is_correct" class="ml-2 block text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                    </div>
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> เพิ่มคำตอบ
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Modal แก้ไขคำตอบ -->
        <div id="edit-answer-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full mx-4">
                <div class="mb-4">
                    <h3 class="text-xl font-medium text-gray-800">แก้ไขคำตอบ</h3>
                    <p class="text-gray-500 text-sm mt-1">แก้ไขข้อความและสถานะของคำตอบ</p>
                </div>
                
                <form id="edit-answer-form" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="edit_answer_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความคำตอบ</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-comment-dots text-gray-400"></i>
                            </div>
                            <input type="text" name="answer_text" id="edit_answer_text" 
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_correct" id="edit_is_correct" value="1"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <label for="edit_is_correct" class="ml-2 block text-sm font-medium text-gray-700">คำตอบที่ถูก</label>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" id="close-modal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-answer');
        const editModal = document.getElementById('edit-answer-modal');
        const closeModalButton = document.getElementById('close-modal');
        const editForm = document.getElementById('edit-answer-form');
        const editAnswerText = document.getElementById('edit_answer_text');
        const editIsCorrect = document.getElementById('edit_is_correct');
        
        // เปิด Modal แก้ไขคำตอบ
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const text = this.dataset.text;
                const correct = this.dataset.correct === '1';
                
                editAnswerText.value = text;
                editIsCorrect.checked = correct;
                editForm.action = "{{ route('admin.questions.answers.update', '') }}/" + id;
                
                editModal.classList.remove('hidden');
            });
        });
        
        // ปิด Modal
        closeModalButton.addEventListener('click', function() {
            editModal.classList.add('hidden');
        });
        
        // ปิด Modal เมื่อคลิกพื้นหลัง
        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                editModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection