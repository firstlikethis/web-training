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
        this.questionsProcessed = {}; // เก็บประวัติคำถามที่แสดงไปแล้ว
        this.saveProgressInterval = null; // เพิ่มตัวแปรสำหรับเก็บ interval
        
        // ตรวจสอบค่า resumeTime ที่อาจถูกส่งมา
        this.resumeTime = this.video ? parseInt(this.video.dataset.resumeTime || 0) : 0;
        
        if (this.video) {
            this.initEvents();
            
            // ตั้งค่า lastSavedTime เริ่มต้นจาก resumeTime
            if (this.resumeTime > 0) {
                this.lastSavedTime = this.resumeTime;
                this.allowedTime = this.resumeTime + 30; // อนุญาตให้ข้ามไปได้อีก 30 วินาที
                console.log('VideoPlayer initialized with resumeTime:', this.resumeTime);
            }
        }
    }

    /**
    * กำหนด Event listeners สำหรับวิดีโอ
    */
    initEvents() {
        if (!this.video) return;
        
        // ตรวจจับการเล่นวิดีโอ
        this.video.addEventListener('play', () => {
            this.isWatching = true;
            // เริ่มบันทึกความคืบหน้าทุก 20 วินาที
            this.startProgressInterval();
        });
        
        this.video.addEventListener('pause', () => {
            this.isWatching = false;
            // หยุดบันทึกความคืบหน้าเมื่อวิดีโอถูกหยุด
            this.stopProgressInterval();
            // บันทึกความคืบหน้าทันทีเมื่อหยุดวิดีโอ
            this.saveProgress();
        });
        
        // ตรวจจับการอัปเดตเวลา
        this.video.addEventListener('timeupdate', () => {
            this.checkQuestionTime();
        });
        
        // ป้องกัน user กดข้ามด้วยการ seek
        this.video.addEventListener('seeking', () => {
            if (this.video.currentTime > this.allowedTime) {
                console.log('ป้องกันการข้าม: พยายามข้ามไป', this.video.currentTime, 'แต่ allowedTime คือ', this.allowedTime);
                this.video.currentTime = this.allowedTime;
            }
        });
        
        // เมื่อวิดีโอจบ
        this.video.addEventListener('ended', () => {
            console.log('Video ended event triggered!');
            this.isCompleted = true;
            this.saveProgress(true);
            this.stopProgressInterval();
        });
        
        // บันทึกความคืบหน้าเมื่อผู้ใช้ออกจากหน้าเว็บ
        window.addEventListener('beforeunload', () => {
            this.saveProgress();
        });
    }
    
    /**
     * เริ่มการบันทึกความคืบหน้าตามช่วงเวลา
     */
    startProgressInterval() {
        // ล้าง interval เดิมถ้ามี
        this.stopProgressInterval();
        
        // สร้าง interval ใหม่
        this.saveProgressInterval = setInterval(() => {
            this.saveProgress();
        }, 20000); // บันทึกทุก 20 วินาที
    }
    
    /**
     * หยุดการบันทึกความคืบหน้าตามช่วงเวลา
     */
    stopProgressInterval() {
        if (this.saveProgressInterval) {
            clearInterval(this.saveProgressInterval);
            this.saveProgressInterval = null;
        }
    }

    /**
    * กำหนดเวลาที่จะแสดงคำถาม
    */
    setQuestionTimes(times) {
        this.questionTimes = times.sort((a, b) => a - b);
        
        // ถ้า resumeTime > 0 ให้ทำการกรองคำถามที่ผ่านไปแล้ว
        if (this.resumeTime > 0) {
            // คำถามที่ผ่านไปแล้ว ให้ทำเครื่องหมายว่าได้แสดงไปแล้ว
            this.questionTimes.forEach(time => {
                if (time < this.resumeTime) {
                    this.questionsProcessed[time] = true;
                }
            });
            
            // กรองคำถามที่ยังไม่ได้แสดง
            this.questionTimes = this.questionTimes.filter(time => time >= this.resumeTime);
        }
    }
    
    /**
     * ตรวจสอบว่าถึงเวลาแสดงคำถามหรือไม่
     */
    checkQuestionTime() {
        if (!this.isWatching || this.questionTimes.length === 0) {
            return;
        }
        
        const currentTime = Math.floor(this.video.currentTime);
        
        // ตรวจสอบแต่ละเวลาที่ต้องแสดงคำถาม
        for (let i = 0; i < this.questionTimes.length; i++) {
            const questionTime = this.questionTimes[i];
            
            // ถ้าเวลาปัจจุบันมากกว่าหรือเท่ากับเวลาที่จะแสดงคำถาม และยังไม่เคยแสดงคำถามนี้
            if (currentTime >= questionTime && !this.questionsProcessed[questionTime]) {
                // หยุดวิดีโอ
                this.video.pause();
                
                // บันทึกว่าได้แสดงคำถามนี้ไปแล้ว
                this.questionsProcessed[questionTime] = true;
                
                // เรียกใช้ callback เพื่อแสดงคำถาม
                if (this.questionCallback) {
                    this.questionCallback(questionTime);
                }
                
                // ลบเวลานี้ออกจาก questionTimes
                this.questionTimes.splice(i, 1);
                
                // ออกจากลูป เพราะเราแสดงแค่คำถามเดียวต่อครั้ง
                break;
            }
        }
    }
    
    /**
     * บันทึกความคืบหน้าในการดูวิดีโอ
     */
    saveProgress(isCompleted = false) {
        const currentTime = Math.floor(this.video.currentTime);
        
        // ถ้าเป็นการบันทึกเมื่อวิดีโอจบหรือเมื่อมีการเปลี่ยนแปลงเวลาที่สำคัญ (มากกว่า 5 วินาที)
        if (isCompleted || Math.abs(currentTime - this.lastSavedTime) > 5) {
            console.log('Saving progress, isCompleted:', isCompleted, 'currentTime:', currentTime);
            this.lastSavedTime = currentTime;
            this.allowedTime = currentTime + 30; // อนุญาตให้ข้ามไปได้อีก 30 วินาที
            
            if (this.progressCallback) {
                this.progressCallback(currentTime, isCompleted);
            }
        }
    }
    
    /**
     * ดำเนินการเล่นวิดีโอต่อหลังจากตอบคำถามเสร็จ
     */
    continueAfterQuestion() {
        if (this.video) {
            this.video.play();
        }
    }
}