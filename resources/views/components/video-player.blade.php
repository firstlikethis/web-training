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
        
        <div class="absolute bottom-4 right-4 z-10">
            <div class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-medium">
                ความยาว: {{ floor($course->duration_seconds / 60) }}:{{ str_pad($course->duration_seconds % 60, 2, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('training-video');
        
        if (!video) return;
        
        // ดึงค่าเวลาที่เคยดูล่าสุดจาก PHP
        const lastWatchedTime = {{ $userProgress ? $userProgress->current_time : 0 }};
        
        // เมื่อโหลดข้อมูลวิดีโอเสร็จ ให้ตั้งค่า currentTime และเล่นวิดีโอเลย
        video.addEventListener('loadedmetadata', function() {
            if (lastWatchedTime > 0) {
                console.log('Setting video position to:', lastWatchedTime);
                video.currentTime = lastWatchedTime;
                
                // ถ้าต้องการให้เล่นทันที ให้เปิดบรรทัดนี้
                // video.play();
            }
        });
    });
</script>