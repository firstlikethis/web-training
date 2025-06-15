/**
 * จัดการแสดงคำถามและรับคำตอบ
 */
class QuizHandler {
    constructor(courseId, submitCallback, completedCallback) {
        this.courseId = courseId;
        this.submitCallback = submitCallback;
        this.completedCallback = completedCallback;
        this.questions = {}; // เก็บคำถามในรูปแบบ { time_to_show: question }
        this.currentQuestion = null;
        this.startTime = null;
        this.timerInterval = null;
    }
    
    /**
     * กำหนดคำถามทั้งหมด
     */
    setQuestions(questions) {
        this.questions = {};
        questions.forEach(question => {
            this.questions[question.time_to_show] = question;
        });
    }
    
    /**
     * แสดงคำถาม
     */
    showQuestion(timeToShow) {
        // ตรวจสอบว่ามีคำถามที่เวลานี้หรือไม่
        if (!this.questions[timeToShow]) {
            console.error(`ไม่พบคำถามที่เวลา ${timeToShow}`);
            return false;
        }
        
        this.currentQuestion = this.questions[timeToShow];
        this.startTime = Date.now();
        
        // แสดง modal คำถาม
        const modal = document.getElementById('quiz-modal');
        const questionText = document.getElementById('question-text');
        const answersContainer = document.getElementById('answers-container');
        const timerElement = document.getElementById('quiz-timer');
        
        // กำหนดเนื้อหาคำถาม
        questionText.textContent = this.currentQuestion.question_text;
        
        // สร้างตัวเลือกคำตอบ
        answersContainer.innerHTML = '';
        
        // สุ่มลำดับของตัวเลือกคำตอบ
        const shuffledAnswers = [...this.currentQuestion.answers].sort(() => Math.random() - 0.5);
        
        shuffledAnswers.forEach(answer => {
            const answerDiv = document.createElement('div');
            answerDiv.className = 'quiz-answer';
            answerDiv.dataset.answerId = answer.id;
            answerDiv.textContent = answer.answer_text;
            answerDiv.addEventListener('click', () => this.selectAnswer(answer.id));
            answersContainer.appendChild(answerDiv);
        });
        
        // แสดงเวลาที่เหลือ
        this.updateTimer(this.currentQuestion.time_limit_seconds);
        this.startTimer();
        
        // แสดง modal
        modal.classList.remove('hidden');
        
        // เพิ่ม event listener สำหรับปุ่มตอบคำถาม
        const submitButton = document.getElementById('submit-answer');
        if (submitButton) {
            submitButton.onclick = () => this.submitAnswer();
        }
        
        return true;
    }
    
    /**
     * เริ่มตัวนับเวลา
     */
    startTimer() {
        const timeLimit = this.currentQuestion.time_limit_seconds;
        let timeLeft = timeLimit;
        const timerElement = document.getElementById('quiz-timer');
        
        // ล้าง interval เดิมถ้ามี
        clearInterval(this.timerInterval);
        
        this.timerInterval = setInterval(() => {
            timeLeft--;
            this.updateTimer(timeLeft);
            
            if (timeLeft <= 0) {
                this.autoSubmit();
            }
        }, 1000);
    }
    
    /**
     * อัปเดตการแสดงเวลา
     */
    updateTimer(seconds) {
        const timerElement = document.getElementById('quiz-timer');
        timerElement.textContent = `เวลา: ${seconds} วินาที`;
        
        // เปลี่ยนสีเมื่อเวลาเหลือน้อย
        if (seconds <= 5) {
            timerElement.classList.add('text-red-500');
        } else {
            timerElement.classList.remove('text-red-500');
        }
    }
    
    /**
     * เลือกคำตอบ
     */
    selectAnswer(answerId) {
        // ล้างการเลือกเดิม
        const answers = document.querySelectorAll('.quiz-answer');
        answers.forEach(answer => {
            answer.classList.remove('selected');
        });
        
        // เลือกคำตอบใหม่
        const selectedAnswer = document.querySelector(`.quiz-answer[data-answer-id="${answerId}"]`);
        if (selectedAnswer) {
            selectedAnswer.classList.add('selected');
        }
    }
    
    /**
     * ส่งคำตอบอัตโนมัติเมื่อหมดเวลา
     */
    autoSubmit() {
        clearInterval(this.timerInterval);
        
        // ถ้าไม่มีการเลือกคำตอบ ให้เลือกแบบสุ่ม
        const selectedAnswer = document.querySelector('.quiz-answer.selected');
        if (!selectedAnswer) {
            const answers = document.querySelectorAll('.quiz-answer');
            if (answers.length > 0) {
                const randomIndex = Math.floor(Math.random() * answers.length);
                this.selectAnswer(answers[randomIndex].dataset.answerId);
            }
        }
        
        this.submitAnswer();
    }
    
    /**
     * ส่งคำตอบ
     */
    submitAnswer() {
        clearInterval(this.timerInterval);
        
        const selectedAnswer = document.querySelector('.quiz-answer.selected');
        if (!selectedAnswer) {
            alert('กรุณาเลือกคำตอบ');
            this.startTimer(); // เริ่มนับเวลาใหม่
            return;
        }
        
        const answerId = selectedAnswer.dataset.answerId;
        const answerTime = Math.round((Date.now() - this.startTime) / 1000);
        
        // ซ่อน modal
        const modal = document.getElementById('quiz-modal');
        modal.classList.add('hidden');
        
        // เรียก callback
        if (this.submitCallback) {
            this.submitCallback(this.currentQuestion.id, answerId, answerTime);
        }
        
        // ลบคำถามนี้ออกจาก this.questions
        delete this.questions[this.currentQuestion.time_to_show];
        this.currentQuestion = null;
        
        // ตรวจสอบว่าตอบคำถามครบทุกข้อแล้วหรือไม่
        if (Object.keys(this.questions).length === 0) {
            if (this.completedCallback) {
                this.completedCallback();
            }
        }
    }
}