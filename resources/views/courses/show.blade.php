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
                {{ $questions->count() }} คำถาม
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
    <div id="questions-data" class="hidden" data-questions="{{ json_encode($questions) }}"></div>
@endsection

@section('scripts')
    <!-- Include necessary JavaScript files -->
    <script src="{{ asset('js/video-player.js') }}"></script>
    <script src="{{ asset('js/quiz-handler.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get video element
            const video = document.getElementById('training-video');
            const courseId = video.dataset.courseId;
            
            // Get questions data
            const questionsData = JSON.parse(document.getElementById('questions-data').dataset.questions);
            
            // Initialize question times array
            const questionTimes = questionsData.map(q => q.time_to_show);
            
            // Initialize QuizHandler
            const quizHandler = new QuizHandler(courseId, submitAnswer, completeQuiz);
            quizHandler.setQuestions(questionsData);
            
            // Initialize VideoPlayer
            const videoPlayer = new VideoPlayer(video, showQuestion, saveProgress);
            videoPlayer.setQuestionTimes(questionTimes);
            
            // Function to show question at specific time
            function showQuestion(time) {
                quizHandler.showQuestion(time);
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
        });
    </script>
@endsection