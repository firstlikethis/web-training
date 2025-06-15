<div class="video-player-container relative rounded-xl overflow-hidden shadow-2xl bg-gray-900 mb-8">
    @if($course->video_path)
        <video 
            id="training-video" 
            class="w-full aspect-video object-contain bg-black" 
            controls
            controlsList="nodownload"
            poster="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : '' }}"
            data-course-id="{{ $course->id }}"
            preload="metadata"
        >
            <source src="{{ asset('storage/' . $course->video_path) }}" type="video/mp4">
            เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ HTML5
        </video>
        
        <div class="absolute top-4 right-4 z-10">
            <div class="bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg backdrop-blur-sm">
                <i class="fas fa-clock mr-2"></i>
                {{ floor($course->duration_seconds / 60) }}:{{ str_pad($course->duration_seconds % 60, 2, '0', STR_PAD_LEFT) }}
            </div>
        </div>
        
        @if($userProgress && $userProgress->current_time > 0 && $userProgress->current_time < $course->duration_seconds - 10)
            <div class="p-4 bg-gray-50 rounded-b-xl border-t-4 border-blue-500" id="resume-controls">
                <div class="flex flex-col sm:flex-row gap-3">
                    <button id="resume-btn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                        <i class="fas fa-play-circle mr-2"></i>
                        <span>เล่นต่อจากจุดเดิม</span>
                        <span class="ml-2 px-2 py-1 bg-blue-500 rounded-lg text-xs">
                            {{ floor($userProgress->current_time / 60) }}:{{ str_pad($userProgress->current_time % 60, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </button>
                    <button id="restart-btn" class="flex-1 bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-700 px-4 py-3 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                        <i class="fas fa-redo-alt mr-2"></i>
                        <span>เริ่มใหม่ตั้งแต่ต้น</span>
                    </button>
                </div>
                
                @if($userProgress->progress_percentage > 0)
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>ความคืบหน้า</span>
                        <span>{{ $userProgress->progress_percentage }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full" style="width: {{ $userProgress->progress_percentage }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        @endif
    @endif
</div>

<style>
    /* สไตล์เพิ่มเติมสำหรับ video player */
    #training-video {
        max-height: 70vh;
    }
    
    #training-video:focus {
        outline: none;
    }
    
    /* Custom control styles */
    #training-video::-webkit-media-controls-panel {
        background-image: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
    }
    
    /* Animation for controls */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #resume-controls {
        animation: fadeInUp 0.5s ease-out;
    }
</style>