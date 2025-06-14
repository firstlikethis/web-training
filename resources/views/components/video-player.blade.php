<div class="video-player-container relative">
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
    
    @if(isset($userProgress) && $userProgress->current_time > 0 && !$userProgress->is_completed)
        <div class="bg-white text-gray-800 p-4 rounded-lg shadow-lg absolute top-4 left-4 z-10">
            <p class="font-medium">คุณดูวิดีโอนี้ไปแล้ว {{ $userProgress->getCompletionPercentage() }}%</p>
            <button id="resume-video" class="mt-2 bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700 text-sm">เล่นต่อจากครั้งที่แล้ว</button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>