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
    
    <!-- Hidden Questions Data -->
    <div id="questions-data" class="hidden" data-questions="{{ $questions->count() > 0 ? json_encode($questions) : '[]' }}"></div>
@endsection

@section('scripts')
    <!-- Include necessary JavaScript files -->
    <script src="{{ asset('js/video-player.js') }}"></script>
    <script src="{{ asset('js/quiz-handler.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get video element
            const videoElement = document.getElementById('training-video');
            const courseId = "{{ $course->id }}";
            const isYouTube = {{ $course->video_url ? 'true' : 'false' }};
            
            // Get questions data
            const questionsData = JSON.parse(document.getElementById('questions-data').dataset.questions || '[]');
            
            // Initialize VideoPlayer
            const videoPlayer = new VideoPlayer(videoElement, showQuestion, saveProgress);
            
            // Initialize question handling only if there are questions
            if (questionsData && questionsData.length > 0) {
                // Initialize question times array
                const questionTimes = questionsData.map(q => q.time_to_show);
                
                // Initialize QuizHandler
                const quizHandler = new QuizHandler(courseId, submitAnswer, completeQuiz);
                quizHandler.setQuestions(questionsData);
                
                // Set question times
                videoPlayer.setQuestionTimes(questionTimes);
            } else {
                // No questions, set empty array
                videoPlayer.setQuestionTimes([]);
            }
            
            // Function to show question at specific time
            function showQuestion(time) {
                if (questionsData && questionsData.length > 0) {
                    quizHandler.showQuestion(time);
                }
            }
            
            // Function to save video progress
            function saveProgress(currentTime, isCompleted) {
                fetch(`/course/${courseId}/progress`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                        window.location.href = `/course/${courseId}/summary`;
                    }
                })
                .catch(error => {
                    console.error('Error saving progress:', error);
                });
            }
            
            // Function to submit answer
            function submitAnswer(questionId, answerId, answerTime) {
                fetch(`/course/${courseId}/answer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            
            // Function called when all questions are answered
            function completeQuiz() {
                console.log('All questions answered');
            }
            
            // Setup submit button for quiz
            const submitButton = document.getElementById('submit-answer');
            if (submitButton) {
                submitButton.addEventListener('click', function() {
                    if (typeof quizHandler !== 'undefined') {
                        quizHandler.submitAnswer();
                    }
                });
            }
        });
    </script>
@endsection