@extends('layouts.app')

@section('title', 'สรุปผลคะแนน - ' . $course->title)

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">สรุปผลคะแนน: {{ $course->title }}</h1>
        <p class="text-gray-600">คุณได้เรียนจบคอร์สนี้แล้ว ด้านล่างคือผลคะแนนของคุณ</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <h3 class="text-lg font-medium text-gray-700 mb-1">จำนวนคำถามทั้งหมด</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $totalQuestions }}</p>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <h3 class="text-lg font-medium text-gray-700 mb-1">ตอบถูก</h3>
                <p class="text-3xl font-bold text-green-600">{{ $correctAnswers }}</p>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <h3 class="text-lg font-medium text-gray-700 mb-1">คะแนนรวม</h3>
                <p class="text-3xl font-bold text-purple-600">{{ number_format($score, 1) }}%</p>
            </div>
        </div>
        
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $score >= 80 ? 'bg-green-200 text-green-800' : ($score >= 60 ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                                คะแนนของคุณ
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block {{ $score >= 80 ? 'text-green-600' : ($score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ number_format($score, 1) }}%
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                        <div style="width: {{ $score }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $score >= 80 ? 'bg-green-500' : ($score >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">รายละเอียดคำถามและคำตอบ</h2>
        
        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-50 p-4 border-b">
                        <h3 class="font-bold text-gray-800">คำถามที่ {{ $index + 1 }}: {{ $question->question_text }}</h3>
                        <p class="text-sm text-gray-500 mt-1">แสดงที่เวลา {{ floor($question->time_to_show / 60) }}:{{ str_pad($question->time_to_show % 60, 2, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    
                    <div class="p-4">
                        <p class="font-medium text-gray-700 mb-2">ตัวเลือกคำตอบ:</p>
                        
                        <div class="space-y-2">
                            @foreach($question->answers as $answer)
                                <div class="p-3 rounded-lg {{ isset($userAnswers[$question->id]) && $userAnswers[$question->id]->answer_id == $answer->id ? ($answer->is_correct ? 'bg-green-100 border border-green-400' : 'bg-red-100 border border-red-400') : ($answer->is_correct ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50 border border-gray-200') }}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-0.5">
                                            @if(isset($userAnswers[$question->id]) && $userAnswers[$question->id]->answer_id == $answer->id)
                                                <span class="inline-block w-5 h-5 rounded-full {{ $answer->is_correct ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }} flex items-center justify-center text-xs">
                                                    {{ $answer->is_correct ? '✓' : '✗' }}
                                                </span>
                                            @elseif($answer->is_correct)
                                                <span class="inline-block w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs">
                                                    ✓
                                                </span>
                                            @else
                                                <span class="inline-block w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                                                    
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="ml-3">
                                            <p class="text-gray-800">{{ $answer->answer_text }}</p>
                                            
                                            @if(isset($userAnswers[$question->id]) && $userAnswers[$question->id]->answer_id == $answer->id)
                                                <p class="text-sm {{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }} mt-1">
                                                    {{ $answer->is_correct ? 'คุณตอบถูก!' : 'คุณตอบผิด' }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(!isset($userAnswers[$question->id]))
                            <p class="text-sm text-yellow-600 mt-3">คุณไม่ได้ตอบคำถามนี้</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="mt-8 text-center">
        <a href="{{ route('home') }}" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 inline-block">กลับไปหน้าหลัก</a>
    </div>
@endsection