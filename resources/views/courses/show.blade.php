@extends('layouts.app')

@section('title', $course->title . ' - ระบบฝึกอบรมออนไลน์')

@section('content')
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> กลับไปหน้าคอร์สทั้งหมด
            </a>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>
                
                <div class="flex flex-wrap items-center text-gray-600 mb-4 gap-4">
                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full text-sm">
                        <i class="far fa-clock mr-2 text-blue-600"></i>
                        {{ floor($course->duration_seconds / 60) }} นาที {{ $course->duration_seconds % 60 }} วินาที
                    </span>
                    
                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full text-sm">
                        <i class="far fa-question-circle mr-2 text-indigo-600"></i>
                        {{ $questions->count() ?? 0 }} คำถาม
                    </span>
                    
                    @if($course->category)
                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 rounded-full text-sm text-blue-800">
                            <i class="far fa-folder mr-2"></i>
                            {{ $course->category->name }}
                        </span>
                    @endif
                </div>
            </div>
            
            @if($userProgress && $userProgress->progress_percentage > 0)
                <div class="bg-white rounded-lg shadow-md p-4 min-w-[200px]">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">ความคืบหน้าของคุณ</h3>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2.5 rounded-full" style="width: {{ $userProgress->progress_percentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>{{ $userProgress->progress_percentage }}% เสร็จสิ้น</span>
                        @if($userProgress->is_completed)
                            <span class="text-green-600 font-medium">เรียนจบแล้ว</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Video Player Component -->
                @include('components.video-player', ['course' => $course, 'userProgress' => $userProgress])
            </div>
        </div>
        
        <div>
            @if($course->description)
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i> รายละเอียดคอร์ส
                    </h2>
                    <div class="text-gray-600 prose max-w-none">
                        {{ $course->description }}
                    </div>
                </div>
            @endif
            
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-list-ul text-indigo-600 mr-2"></i> รายละเอียดแบบทดสอบ
                </h2>
                
                @if($questions->count() > 0)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                            <span class="font-medium text-blue-800">จำนวนคำถามทั้งหมด</span>
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">{{ $questions->count() }}</span>
                        </div>
                        
                        <p class="text-gray-600 text-sm">
                            คำถามจะปรากฏระหว่างการดูวิดีโอตามเวลาที่กำหนด เมื่อถึงเวลาวิดีโอจะหยุดและคำถามจะปรากฏขึ้น
                            คุณต้องตอบคำถามก่อนจึงจะสามารถดูวิดีโอต่อได้
                        </p>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="font-medium text-gray-800 mb-2">ตัวอย่างเวลาที่จะปรากฏคำถาม:</h3>
                            <ul class="space-y-2 text-sm">
                                @foreach($questions->take(3) as $index => $question)
                                    <li class="flex items-center bg-gray-50 p-2 rounded">
                                        <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-medium mr-2">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-gray-600">
                                            {{ floor($question->time_to_show / 60) }}:{{ str_pad($question->time_to_show % 60, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </li>
                                @endforeach
                                
                                @if($questions->count() > 3)
                                    <li class="text-center text-gray-500 italic">
                                        ...และอีก {{ $questions->count() - 3 }} คำถาม
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-yellow-700">ไม่มีคำถามในคอร์สนี้</p>
                    </div>
                @endif
            </div>
        </div>
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
                window.showQuizModal(); // ใช้ฟังก์ชันจาก component
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