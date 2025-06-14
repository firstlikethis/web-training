<div id="quiz-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full mx-4 animate__animated animate__fadeIn">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">คำถาม</h3>
            <div id="quiz-timer" class="text-right font-medium text-gray-700"></div>
        </div>
        
        <div class="mb-6">
            <p id="question-text" class="text-lg font-medium mb-4"></p>
            
            <div id="answers-container" class="space-y-3">
                <!-- คำตอบจะถูกเพิ่มโดย JavaScript -->
            </div>
        </div>
        
        <div class="flex justify-end">
            <button id="submit-answer" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">ตอบคำถาม</button>
        </div>
    </div>
</div>

<style>
    /* สไตล์สำหรับ quiz-modal */
    .quiz-answer {
        @apply border border-gray-300 rounded-lg p-4 mb-2 cursor-pointer hover:bg-gray-100 transition-colors;
    }
    .quiz-answer.selected {
        @apply bg-blue-100 border-blue-500 border-2;
    }
    
    /* อนิเมชัน */
    .animate__animated {
        animation-duration: 0.5s;
    }
    .animate__fadeIn {
        animation-name: fadeIn;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>