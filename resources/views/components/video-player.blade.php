<div class="video-player-container relative">
    @if($course->video_path)
        <!-- สำหรับวิดีโอที่อัปโหลดเอง -->
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
    @elseif($course->video_url)
        <!-- สำหรับวิดีโอจาก YouTube -->
        <div class="aspect-w-16 aspect-h-9 bg-black rounded-lg shadow-lg">
            <div id="youtube-player" class="w-full h-full" data-course-id="{{ $course->id }}" data-video-url="{{ $course->video_url }}"></div>
        </div>
    @endif
    
    @if(isset($userProgress) && $userProgress->current_time > 0 && !$userProgress->is_completed)
        <div class="bg-white text-gray-800 p-4 rounded-lg shadow-lg absolute top-4 left-4 z-10">
            <p class="font-medium">คุณดูวิดีโอนี้ไปแล้ว {{ $userProgress->getCompletionPercentage() }}%</p>
            <button id="resume-video" class="mt-2 bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700 text-sm">เล่นต่อจากครั้งที่แล้ว</button>
        </div>
    @endif
</div>

<!-- JavaScript สำหรับทั้ง video และ YouTube -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseId = "{{ $course->id }}";
        const isYouTube = {{ $course->video_url ? 'true' : 'false' }};
        
        if (!isYouTube) {
            // สำหรับวิดีโอที่อัปโหลดเอง
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
        }
    });
</script>

<!-- JavaScript สำหรับ YouTube Embed API -->
@if($course->video_url)
<script>
    // โหลด YouTube API
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    
    var player;
    var videoUrl = document.getElementById('youtube-player').dataset.videoUrl;
    var courseId = document.getElementById('youtube-player').dataset.courseId;
    var videoId = '';
    var lastSavedTime = 0;
    var isVideoEnded = false;
    
    // ดึง video ID จาก embed URL
    if (videoUrl.includes('youtube.com/embed/')) {
        videoId = videoUrl.split('/').pop().split('?')[0];
    }
    
    function onYouTubeIframeAPIReady() {
        if (!videoId) return;
        
        player = new YT.Player('youtube-player', {
            videoId: videoId,
            playerVars: {
                'playsinline': 1,
                'rel': 0,
                'modestbranding': 1
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }
    
    function onPlayerReady(event) {
        // พร้อมเล่นวิดีโอ
        console.log('YouTube player is ready');
        
        // ถ้ามีการบันทึกการดูค้างไว้ จะแสดงปุ่มเล่นต่อ
        const resumeButton = document.getElementById('resume-video');
        if (resumeButton) {
            resumeButton.addEventListener('click', function() {
                const currentTime = {{ $userProgress->current_time ?? 0 }};
                player.seekTo(currentTime);
                player.playVideo();
                this.parentElement.style.display = 'none';
            });
        }
        
        // เช็คว่าควรจะหยุดที่คำถามหรือไม่
        window.checkYouTubeQuestions = function() {
            const currentTime = Math.floor(player.getCurrentTime());
            window.checkQuestionTime(currentTime);
        };
        
        // เพิ่ม interval เพื่อเช็คคำถาม
        setInterval(window.checkYouTubeQuestions, 500);
    }
    
    function onPlayerStateChange(event) {
        // เมื่อสถานะของ player เปลี่ยน (เล่น, หยุด, จบ, ฯลฯ)
        
        // ถ้าวิดีโอกำลังเล่น
        if (event.data == YT.PlayerState.PLAYING) {
            // บันทึกความคืบหน้าเป็นระยะ
            window.youtubeProgressInterval = setInterval(function() {
                const currentTime = Math.floor(player.getCurrentTime());
                if (currentTime > lastSavedTime + 10) {
                    saveProgress(currentTime, false);
                    lastSavedTime = currentTime;
                }
            }, 10000);
        } else {
            // ถ้าไม่ได้เล่น ให้หยุด interval
            if (window.youtubeProgressInterval) {
                clearInterval(window.youtubeProgressInterval);
            }
        }
        
        // ถ้าวิดีโอจบ
        if (event.data == YT.PlayerState.ENDED && !isVideoEnded) {
            isVideoEnded = true;
            // ส่งข้อมูลว่าดูจบแล้ว
            saveProgress(player.getDuration(), true);
        }
    }
    
    // บันทึกความคืบหน้า
    function saveProgress(currentTime, isCompleted) {
        fetch(`/course/${courseId}/progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                current_time: Math.floor(currentTime),
                is_completed: isCompleted
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Progress saved:', data);
            
            // ถ้าวิดีโอจบแล้ว ให้ redirect ไปหน้าสรุป
            if (isCompleted) {
                window.location.href = `/course/${courseId}/summary`;
            }
        })
        .catch(error => {
            console.error('Error saving progress:', error);
        });
    }
    
    // ฟังก์ชันเพื่อหยุดวิดีโอสำหรับคำถาม
    window.pauseYoutubeVideo = function() {
        if (player && typeof player.pauseVideo === 'function') {
            player.pauseVideo();
        }
    };
    
    // ฟังก์ชันเพื่อเล่นวิดีโอต่อหลังตอบคำถาม
    window.playYoutubeVideo = function() {
        if (player && typeof player.playVideo === 'function') {
            player.playVideo();
        }
    };
</script>
@endif