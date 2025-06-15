@extends('layouts.app')

@section('title', $course->title . ' - ระบบฝึกอบรมออนไลน์')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>
        
        <div class="flex items-center text-gray-600 mb-4">
            <span class="mr-4">
                <i class="far fa-clock mr-1"></i>
                {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที
            </span>
            
            <span>
                <i class="far fa-question-circle mr-1"></i>
                {{ $questions->count() ?? 0 }} คำถาม
            </span>
        </div>
        
        @if($course->description)
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">รายละเอียดคอร์ส</h2>
                <p class="text-gray-600">{{ $course->description }}</p>
            </div>
        @endif
    </div>
    
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <!-- Video Player Component -->
        @include('components.video-player', ['course' => $course, 'userProgress' => $userProgress])
    </div>
    
    <!-- Quiz Modal Component -->
    @include('components.quiz-modal')
@endsection

@section('scripts')
    <script src="{{ asset('js/video-player.js') }}"></script>
    <script src="{{ asset('js/quiz-handler.js') }}"></script>

    <script>
        // เราจะกำหนดค่าให้กับตัวแปร JavaScript โดยตรง
        const courseQuestions = @json($questions);
        
        document.addEventListener('DOMContentLoaded', function() {
            // Get video element
            const videoElement = document.getElementById('training-video');
            const courseId = "{{ $course->id }}";
            const resumeTime = {{ $userProgress ? $userProgress->current_time : 0 }};
            const videoDuration = {{ $course->duration_seconds }};
            
            // ตรวจสอบว่ามี videoElement หรือไม่
            if (!videoElement) {
                console.error('Video element not found');
                return;
            }
            
            console.log('Resume time:', resumeTime);
            console.log('Video duration:', videoDuration);
            
            // เรียกใช้ CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // บันทึกความคืบหน้าในการดูวิดีโอ
            function saveProgress(currentTime, isCompleted) {
                console.log('Saving progress, currentTime:', currentTime, 'isCompleted:', isCompleted);
                
                fetch(`/course/${courseId}/progress`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        current_time: currentTime,
                        is_completed: isCompleted
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Progress saved:', data);
                    
                    // If video is completed, redirect to summary page
                    if (isCompleted) {
                        console.log('Video completed, redirecting to summary page');
                        window.location.href = `/course/${courseId}/summary`;
                    }
                })
                .catch(error => {
                    console.error('Error saving progress:', error);
                });
            }
            
            // บันทึกคำตอบ
            function submitAnswer(questionId, answerId, answerTime) {
                console.log('Submitting answer:', questionId, answerId, answerTime);
                
                fetch(`/course/${courseId}/answer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        answer_id: answerId,
                        answer_time: answerTime
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Answer submitted:', data);
                    // Continue video playback
                    videoPlayer.continueAfterQuestion();
                })
                .catch(error => {
                    console.error('Error submitting answer:', error);
                    // Continue video playback even if there's an error
                    videoPlayer.continueAfterQuestion();
                });
            }
            
            // เมื่อตอบคำถามครบทุกข้อ
            function completeQuiz() {
                console.log('ตอบคำถามครบทุกข้อแล้ว');
                
                // ตรวจสอบว่าวิดีโอจบหรือยัง
                if (videoElement.ended || videoElement.currentTime >= videoElement.duration - 1) {
                    // บันทึกความคืบหน้าว่าเรียนจบแล้ว
                    saveProgress(videoElement.currentTime, true);
                }
            }
            
            // ฟังก์ชันที่จะถูกเรียกเมื่อถึงเวลาแสดงคำถาม
            function showQuestion(time) {
                console.log(`แสดงคำถามที่เวลา ${time} วินาที`);
                const result = quizHandler.showQuestion(time);
                console.log('Show question result:', result);
            }
            
            // Initialize QuizHandler ก่อน เพื่อให้พร้อมรับคำถาม
            const quizHandler = new QuizHandler(courseId, submitAnswer, completeQuiz);
            
            // กำหนดคำถามให้ QuizHandler
            if (courseQuestions && courseQuestions.length > 0) {
                quizHandler.setQuestions(courseQuestions);
                console.log('Questions loaded:', courseQuestions.length);
            }
            
            // Initialize VideoPlayer
            const videoPlayer = new VideoPlayer(videoElement, showQuestion, saveProgress);
            
            // กำหนดเวลาที่จะแสดงคำถาม
            if (courseQuestions && courseQuestions.length > 0) {
                const questionTimes = courseQuestions.map(q => parseInt(q.time_to_show));
                videoPlayer.setQuestionTimes(questionTimes);
            }
            
            // กำหนด event listeners สำหรับปุ่ม
            const resumeBtn = document.getElementById('resume-btn');
            const restartBtn = document.getElementById('restart-btn');
            const resumeControls = document.getElementById('resume-controls');
            
            if (resumeBtn) {
                resumeBtn.addEventListener('click', function() {
                    videoPlayer.setStartTimeAndPlay(resumeTime);
                    
                    // ซ่อนปุ่มเล่นต่อ
                    if (resumeControls) {
                        resumeControls.style.display = 'none';
                    }
                });
            }
            
            if (restartBtn) {
                restartBtn.addEventListener('click', function() {
                    videoPlayer.setStartTimeAndPlay(0);
                    
                    // ซ่อนปุ่มเล่นต่อ
                    if (resumeControls) {
                        resumeControls.style.display = 'none';
                    }
                });
            }
            
            // สำหรับคำถามที่ควรแสดงไปแล้ว (ถ้ามีการเล่นต่อ)
            if (resumeTime > 0) {
                courseQuestions.forEach(question => {
                    if (question.time_to_show < resumeTime) {
                        videoPlayer.questionsProcessed[question.time_to_show] = true;
                    }
                });
            }
            
            // ตรวจสอบว่าถ้าเรียนจบแล้วให้บันทึกความคืบหน้าเป็น 100% ทันที
            if ({{ $userProgress && $userProgress->is_completed ? 'true' : 'false' }}) {
                console.log('Course already completed, updating progress to 100%');
                // บันทึกความคืบหน้าเป็น 100%
                saveProgress(videoDuration, true);
            }
        });
    </script>
@endsection