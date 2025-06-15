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
        
        // สร้างชื่อกลุ่ม radio buttons ที่ไม่ซ้ำกัน
        const radioGroupName = `question-${this.currentQuestion.id}-answers`;
        
        // สุ่มลำดับของตัวเลือกคำตอบ
        const shuffledAnswers = [...this.currentQuestion.answers].sort(() => Math.random() - 0.5);
        
        shuffledAnswers.forEach(answer => {
            const answerDiv = document.createElement('div');
            answerDiv.className = 'quiz-answer';
            
            // สร้าง radio button
            const radioInput = document.createElement('input');
            radioInput.type = 'radio';
            radioInput.name = radioGroupName;
            radioInput.id = `answer-${answer.id}`;
            radioInput.value = answer.id;
            radioInput.dataset.answerId = answer.id;
            
            // เพิ่ม event listener ที่ radio button เพื่อส่งคำตอบอัตโนมัติเมื่อเลือก
            radioInput.addEventListener('change', () => {
                if (radioInput.checked) {
                    // ให้เวลาผู้ใช้ได้เห็นว่าเลือกแล้วซักครู่ก่อนที่จะเก็บคำตอบและซ่อน modal
                    setTimeout(() => {
                        this.submitAnswer(answer.id);
                    }, 500);
                }
            });
            
            // สร้าง label สำหรับ radio button
            const label = document.createElement('label');
            label.htmlFor = `answer-${answer.id}`;
            label.textContent = answer.answer_text;
            
            // เพิ่ม elements เข้าไปใน answer div
            answerDiv.appendChild(radioInput);
            answerDiv.appendChild(label);
            
            answersContainer.appendChild(answerDiv);
        });
        
        // แสดงเวลาที่เหลือ
        this.updateTimer(this.currentQuestion.time_limit_seconds);
        this.startTimer();
        
        // แสดง modal
        modal.classList.remove('hidden');
        
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
     * ส่งคำตอบอัตโนมัติเมื่อหมดเวลา
     */
    autoSubmit() {
        clearInterval(this.timerInterval);
        
        // ถ้าไม่มีการเลือกคำตอบ ให้เลือกแบบสุ่ม
        const radioGroupName = `question-${this.currentQuestion.id}-answers`;
        const selectedRadio = document.querySelector(`input[name="${radioGroupName}"]:checked`);
        
        if (!selectedRadio) {
            const allRadios = document.querySelectorAll(`input[name="${radioGroupName}"]`);
            if (allRadios.length > 0) {
                const randomIndex = Math.floor(Math.random() * allRadios.length);
                allRadios[randomIndex].checked = true;
                this.submitAnswer(allRadios[randomIndex].value);
                return;
            }
        }
        
        this.submitAnswer(selectedRadio ? selectedRadio.value : null);
    }
    
    /**
     * ส่งคำตอบ
     */
    submitAnswer(answerId) {
        clearInterval(this.timerInterval);
        
        if (!answerId) {
            // ถ้าไม่มี answerId ให้เลือกคำตอบแรก
            const radioGroupName = `question-${this.currentQuestion.id}-answers`;
            const firstRadio = document.querySelector(`input[name="${radioGroupName}"]`);
            if (firstRadio) {
                firstRadio.checked = true;
                answerId = firstRadio.value;
            } else {
                console.error('ไม่พบตัวเลือกคำตอบใด ๆ');
                // อาจจะให้ซ่อน modal และเล่นวิดีโอต่อในกรณีที่เกิดข้อผิดพลาด
                const modal = document.getElementById('quiz-modal');
                modal.classList.add('hidden');
                
                if (this.completedCallback) {
                    this.completedCallback();
                }
                return;
            }
        }
        
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