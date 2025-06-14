<div id="quiz-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full mx-4">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitButton = document.getElementById('submit-answer');
        
        if (submitButton) {
            submitButton.addEventListener('click', function() {
                // ฟังก์ชัน submitAnswer จะถูกกำหนดใน quiz-handler.js
                if (typeof quizHandler !== 'undefined') {
                    quizHandler.submitAnswer();
                }
            });
        }
    });
</script>