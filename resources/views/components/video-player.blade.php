<div class="video-player-container relative">
    @if($course->video_path)
        <video 
            id="training-video" 
            class="w-full rounded-lg shadow-lg" 
            controls
            controlsList="nodownload"
            poster="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : '' }}"
            data-course-id="{{ $course->id }}"
            preload="metadata"
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
            <div class="mt-2 mb-4" id="resume-controls">
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