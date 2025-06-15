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
        
        if (this.video) {
            this.initEvents();
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
            
            // อัปเดต allowedTime เมื่อเล่นวิดีโอปกติ
            if (this.isWatching && this.video.currentTime > this.allowedTime - 20) {
                this.allowedTime = this.video.currentTime + 30; // อนุญาตให้ข้ามไปได้อีก 30 วินาที
            }
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
            // ส่งค่า isCompleted เป็น true เพื่อให้ระบบรู้ว่าวิดีโอจบแล้ว
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
        }, 10000); // บันทึกทุก 10 วินาที (ลดเวลาลงจาก 20 วินาที)
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
        
        // ถ้าเป็นการบันทึกเมื่อวิดีโอจบหรือเมื่อมีการเปลี่ยนแปลงเวลาที่สำคัญ (มากกว่า 3 วินาที)
        if (isCompleted || Math.abs(currentTime - this.lastSavedTime) > 3 || currentTime === this.video.duration) {
            console.log('Saving progress, isCompleted:', isCompleted, 'currentTime:', currentTime);
            this.lastSavedTime = currentTime;
            
            // อัปเดต allowedTime เมื่อบันทึกความคืบหน้า
            if (currentTime > this.allowedTime - 20) {
                this.allowedTime = currentTime + 30; // อนุญาตให้ข้ามไปได้อีก 30 วินาที
            }
            
            if (this.progressCallback) {
                // ส่งข้อมูลเกี่ยวกับสถานะการเรียนจบไปด้วย
                this.progressCallback(currentTime, isCompleted || this.isCompleted || currentTime >= this.video.duration);
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
    
    /**
     * ตั้งค่าเวลาเริ่มต้นและเล่นวิดีโอ
     */
    setStartTimeAndPlay(time) {
        if (this.video) {
            // กำหนดค่า allowedTime ทันที
            this.allowedTime = time + 30;
            this.lastSavedTime = time;
            
            // ตั้งค่า currentTime และเล่นวิดีโอ
            console.log("Setting video time to:", time);
            this.video.currentTime = time;
            this.video.play();
        }
    }
}