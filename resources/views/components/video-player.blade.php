<div class="video-player-container relative">
    @if($course->video_path)
        <video 
            id="training-video" 
            class="w-full rounded-lg shadow-lg" 
            controls
            controlsList="nodownload"
            poster="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : '' }}"
            data-course-id="{{ $course->id }}"
        >
            <source src="{{ asset('storage/' . $course->video_path) }}" type="video/mp4">
            เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ HTML5
        </video>
    @endif
    
    @if(isset($userProgress) && $userProgress->current_time > 0 && !$userProgress->is_completed)
        <div class="bg-white text-gray-800 p-4 rounded-lg shadow-lg absolute top-4 left-4 z-10">
            <p class="font-medium">คุณดูวิดีโอนี้ไปแล้ว {{ $userProgress->getCompletionPercentage() }}%</p>
            <button id="resume-video" class="mt-2 bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700 text-sm">เล่นต่อจากครั้งที่แล้ว</button>
        </div>
    @endif
</div>

<!-- JavaScript สำหรับวิดีโอ -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseId = "{{ $course->id }}";
        const video = document.getElementById('training-video');
        const resumeButton = document.getElementById('resume-video');
        
        // ถ้ามีปุ่ม "เล่นต่อจากครั้งที่แล้ว" ให้ตั้งค่าเหตุการณ์คลิก
        if (resumeButton) {
            resumeButton.addEventListener('click', function() {
                video.currentTime = {{ $userProgress->current_time ?? 0 }};
                video.play();
                this.parentElement.style.display = 'none';
            });
        }
        
        // ตั้งค่าเหตุการณ์สำหรับวิดีโอ
        if (video) {
            // ตรวจจับการเล่นวิดีโอ
            video.addEventListener('play', () => {
                window.isWatching = true;
            });
            
            video.addEventListener('pause', () => {
                window.isWatching = false;
            });
            
            // ตรวจจับการอัปเดตเวลา
            let lastSavedTime = 0;
            let allowedTime = 0;
            
            video.addEventListener('timeupdate', () => {
                if (window.isWatching) {
                    // ตรวจสอบเวลาสำหรับคำถาม
                    const currentTime = Math.floor(video.currentTime);
                    if (typeof window.checkQuestionTime === 'function') {
                        window.checkQuestionTime(currentTime);
                    }
                    
                    // บันทึกความคืบหน้า
                    if (currentTime > lastSavedTime + 10 || video.ended) {
                        lastSavedTime = currentTime;
                        allowedTime = currentTime + 30; // อนุญาตให้ข้ามไปได้อีก 30 วินาที
                        
                        // ส่งข้อมูลความคืบหน้าไปยังเซิร์ฟเวอร์
                        fetch(`/course/${courseId}/progress`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                current_time: currentTime,
                                is_completed: video.ended
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Progress saved:', data);
                            
                            // ถ้าวิดีโอจบแล้ว ให้ redirect ไปหน้าสรุป
                            if (video.ended) {
                                window.location.href = `/course/${courseId}/summary`;
                            }
                        })
                        .catch(error => {
                            console.error('Error saving progress:', error);
                        });
                    }
                }
            });
            
            // ป้องกัน user กดข้ามด้วยการ seek
            video.addEventListener('seeking', () => {
                if (video.currentTime > allowedTime) {
                    video.currentTime = allowedTime;
                }
            });
            
            // เมื่อวิดีโอจบ
            video.addEventListener('ended', () => {
                // บันทึกว่าดูจบแล้ว
                fetch(`/course/${courseId}/progress`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        current_time: Math.floor(video.duration),
                        is_completed: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Video completed:', data);
                    // นำไปยังหน้าสรุป
                    window.location.href = `/course/${courseId}/summary`;
                })
                .catch(error => {
                    console.error('Error saving completion:', error);
                });
            });
        }
    });
</script>