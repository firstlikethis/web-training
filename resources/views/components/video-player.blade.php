<div class="video-player-container relative">
    @if($course->video_path)
        <video 
            id="training-video" 
            class="w-full rounded-lg shadow-lg" 
            controls
            controlsList="nodownload"
            poster="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : '' }}"
            data-course-id="{{ $course->id }}"
            data-resume-time="{{ $userProgress ? $userProgress->current_time : 0 }}"
        >
            <source src="{{ asset('storage/' . $course->video_path) }}" type="video/mp4">
            เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ HTML5
        </video>
        
        <div class="absolute bottom-4 right-4 z-10">
            <div class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-medium">
                ความยาว: {{ floor($course->duration_seconds / 60) }}:{{ str_pad($course->duration_seconds % 60, 2, '0', STR_PAD_LEFT) }}
            </div>
        </div>
        
        @if($userProgress && $userProgress->current_time > 0 && $userProgress->current_time < $course->duration_seconds - 10)
            <div class="mt-2 mb-4">
                <button id="resume-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    เล่นต่อจากจุดเดิม ({{ floor($userProgress->current_time / 60) }}:{{ str_pad($userProgress->current_time % 60, 2, '0', STR_PAD_LEFT) }})
                </button>
                <button id="restart-btn" class="ml-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                    เริ่มใหม่ตั้งแต่ต้น
                </button>
            </div>
        @endif
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('training-video');
        const resumeBtn = document.getElementById('resume-btn');
        const restartBtn = document.getElementById('restart-btn');
        
        if (!video) return;
        
        // ดึงค่าเวลาที่เคยดูล่าสุดจาก data attribute
        const lastWatchedTime = parseInt(video.dataset.resumeTime || 0);
        
        // ตั้งค่าปุ่มเล่นต่อ
        if (resumeBtn) {
            resumeBtn.addEventListener('click', function() {
                video.currentTime = lastWatchedTime;
                video.play();
            });
        }
        
        // ตั้งค่าปุ่มเริ่มใหม่
        if (restartBtn) {
            restartBtn.addEventListener('click', function() {
                video.currentTime = 0;
                video.play();
            });
        }
        
        // เมื่อโหลดข้อมูลวิดีโอเสร็จ ให้ตั้งค่า currentTime
        video.addEventListener('loadedmetadata', function() {
            if (lastWatchedTime > 0) {
                console.log('Setting video position to:', lastWatchedTime);
                video.currentTime = lastWatchedTime;
                
                // ไม่เล่นทันที แต่ให้ผู้ใช้กดปุ่มเล่นเอง
                // video.play();
            }
        });
    });
</script>