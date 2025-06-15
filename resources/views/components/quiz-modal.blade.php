<div id="quiz-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-xl w-full mx-4 transform transition-all duration-300 ease-out scale-95 opacity-0" id="quiz-modal-content">
        <div class="border-b pb-4 mb-5">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">คำถามระหว่างการเรียน</h3>
                <div id="quiz-timer" class="px-4 py-1 bg-blue-50 text-blue-600 rounded-full font-medium text-sm"></div>
            </div>
        </div>
        
        <div class="mb-6">
            <div class="mb-5">
                <p id="question-text" class="text-lg font-medium text-gray-700 leading-relaxed"></p>
            </div>
            
            <div id="answers-container" class="space-y-3">
                <!-- คำตอบจะถูกเพิ่มโดย JavaScript -->
            </div>
        </div>
    </div>
</div>

<style>
    /* สไตล์สำหรับ quiz-modal */
    #quiz-modal.active {
        opacity: 1;
    }
    
    #quiz-modal.active #quiz-modal-content {
        transform: scale(1);
        opacity: 1;
    }
    
    .quiz-answer {
        @apply border border-gray-200 rounded-xl p-4 mb-3 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 flex items-center;
    }
    
    .quiz-answer:hover {
        @apply shadow-md;
        transform: translateY(-2px);
    }
    
    .quiz-answer input[type="radio"] {
        @apply mr-3 h-5 w-5 text-blue-600;
    }
    
    .quiz-answer input[type="radio"]:checked + label {
        @apply font-medium text-blue-700;
    }
    
    .quiz-answer:has(input[type="radio"]:checked) {
        @apply bg-blue-50 border-blue-400 shadow-md;
    }
    
    /* อนิเมชัน Timer */
    @keyframes pulse-warning {
        0%, 100% { color: #ef4444; }
        50% { color: #b91c1c; }
    }
    
    .timer-warning {
        animation: pulse-warning 1s ease-in-out infinite;
        font-weight: bold;
    }
</style>

<script>
    // เพิ่ม script นี้เพื่อทำให้ animation ทำงาน
    document.addEventListener('DOMContentLoaded', function() {
        // อ้างอิงองค์ประกอบ
        const quizModal = document.getElementById('quiz-modal');
        const quizModalContent = document.getElementById('quiz-modal-content');
        
        // สร้างฟังก์ชันเปิด/ปิด modal ด้วย animation
        window.showQuizModal = function() {
            quizModal.classList.remove('hidden');
            setTimeout(() => {
                quizModal.classList.add('active');
            }, 10);
        };
        
        window.hideQuizModal = function() {
            quizModal.classList.remove('active');
            setTimeout(() => {
                quizModal.classList.add('hidden');
            }, 300);
        };
        
        // เพิ่มฟังก์ชันสำหรับทำ timer เป็นสีแดงเมื่อเวลาเหลือน้อย
        window.updateTimerDisplay = function(seconds) {
            const timerElement = document.getElementById('quiz-timer');
            timerElement.textContent = `เวลา: ${seconds} วินาที`;
            
            if (seconds <= 5) {
                timerElement.classList.add('timer-warning');
                timerElement.classList.remove('bg-blue-50', 'text-blue-600');
                timerElement.classList.add('bg-red-50', 'text-red-600');
            } else {
                timerElement.classList.remove('timer-warning');
                timerElement.classList.remove('bg-red-50', 'text-red-600');
                timerElement.classList.add('bg-blue-50', 'text-blue-600');
            }
        };
    });
</script>