/**
 * จัดการเล่นวิดีโอและการควบคุม
 */
class VideoPlayer {
    constructor(videoElement, questionCallback, progressCallback) {
        this.video = videoElement;
        this.questionCallback = questionCallback;
        this.progressCallback = progressCallback;
        this.questionTimes = [];
        this.lastSavedTime = 0;
        this.allowedTime = 0;
        this.isWatching = false;
        this.isCompleted = false;
        
        this.initEvents();
    }
    
    /**
     * กำหนด Event listeners
     */
    initEvents() {
        // ตรวจจับการเล่นวิดีโอ
        this.video.addEventListener('play', () => {
            this.isWatching = true;
        });
        
        this.video.addEventListener('pause', () => {
            this.isWatching = false;
        });
        
        // ตรวจจับการอัปเดตเวลา
        this.video.addEventListener('timeupdate', () => {
            this.checkQuestionTime();
            this.saveProgress();
        });
        
        // ป้องกัน user กดข้ามด้วยการ seek
        this.video.addEventListener('seeking', () => {
            if (this.video.currentTime > this.allowedTime) {
                this.video.currentTime = this.allowedTime;
            }
        });
        
        // เมื่อวิดีโอจบ
        this.video.addEventListener('ended', () => {
            this.isCompleted = true;
            this.saveProgress(true);
        });
    }
    
    /**
     * กำหนดเวลาที่จะแสดงคำถาม
     */
    setQuestionTimes(times) {
        this.questionTimes = times.sort((a, b) => a - b);
    }
    
    /**
     * ตรวจสอบว่าถึงเวลาแสดงคำถามหรือไม่
     */
    checkQuestionTime() {
        if (!this.isWatching || this.questionTimes.length === 0) {
            return;
        }
        
        const currentTime = Math.floor(this.video.currentTime);
        const nextQuestionTime = this.questionTimes[0];
        
        if (currentTime >= nextQuestionTime) {
            // แสดงคำถาม
            this.video.pause();
            this.questionCallback(nextQuestionTime);
            
            // ลบเวลาคำถามที่แสดงแล้วออกจาก array
            this.questionTimes.shift();
        }
    }
    
    /**
     * บันทึกความคืบหน้าในการดูวิดีโอ
     */
    saveProgress(isCompleted = false) {
        const currentTime = Math.floor(this.video.currentTime);
        
        // บันทึกทุก 10 วินาที หรือเมื่อจบวิดีโอ
        if (isCompleted || currentTime > this.lastSavedTime + 10) {
            this.lastSavedTime = currentTime;
            this.allowedTime = currentTime + 30; // อนุญาตให้ข้ามไปได้อีก 30 วินาที
            
            if (this.progressCallback) {
                this.progressCallback(currentTime, isCompleted || this.isCompleted);
            }
        }
    }
    
    /**
     * ดำเนินการเล่นวิดีโอต่อหลังจากตอบคำถามเสร็จ
     */
    continueAfterQuestion() {
        this.video.play();
    }
}