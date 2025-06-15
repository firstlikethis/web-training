@extends('layouts.app')

@section('title', 'สรุปผลคะแนน - ' . $course->title)

@section('content')
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> กลับไปหน้าคอร์สทั้งหมด
            </a>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">สรุปผลคะแนน</h1>
                <p class="text-xl text-gray-600">{{ $course->title }}</p>
            </div>
            
            @if($score >= 80)
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-trophy text-yellow-500 text-xl mr-2"></i>
                    <span class="font-medium">ผ่านเกณฑ์การประเมิน</span>
                </div>
            @elseif($score >= 60)
                <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-check-circle text-xl mr-2"></i>
                    <span class="font-medium">ผ่านเกณฑ์ขั้นต่ำ</span>
                </div>
            @else
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle text-xl mr-2"></i>
                    <span class="font-medium">ไม่ผ่านเกณฑ์การประเมิน</span>
                </div>
            @endif
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full {{ $score >= 80 ? 'bg-green-100 text-green-600' : ($score >= 60 ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }} mb-4">
                <span class="text-3xl font-bold">{{ number_format($score, 1) }}%</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">คุณทำคะแนนได้ {{ $correctAnswers }} จาก {{ $totalQuestions }} ข้อ</h2>
            <p class="text-gray-600">
                @if($score >= 80)
                    ยอดเยี่ยม! คุณมีความเข้าใจในเนื้อหาเป็นอย่างดี
                @elseif($score >= 60)
                    ดี! คุณมีความเข้าใจในเนื้อหา แต่ยังมีบางจุดที่สามารถพัฒนาได้อีก
                @else
                    คุณควรทบทวนเนื้อหาอีกครั้งเพื่อเพิ่มความเข้าใจให้มากขึ้น
                @endif
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-xl p-6 text-center">
                <div class="text-blue-600 text-xl mb-2">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-1">จำนวนคำถามทั้งหมด</h3>
                <p class="text-3xl font-bold text-blue-700">{{ $totalQuestions }}</p>
            </div>
            
            <div class="bg-green-50 rounded-xl p-6 text-center">
                <div class="text-green-600 text-xl mb-2">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-1">ตอบถูก</h3>
                <p class="text-3xl font-bold text-green-700">{{ $correctAnswers }}</p>
            </div>
            
            <div class="bg-red-50 rounded-xl p-6 text-center">
                <div class="text-red-600 text-xl mb-2">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-1">ตอบผิด</h3>
                <p class="text-3xl font-bold text-red-700">{{ $totalQuestions - $correctAnswers }}</p>
            </div>
        </div>
        
        <div class="mt-8">
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
                <div class="overflow-hidden h-3 mb-4 text-xs flex rounded-full bg-gray-200">
                    <div style="width: {{ $score }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $score >= 80 ? 'bg-gradient-to-r from-green-400 to-green-600' : ($score >= 60 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : 'bg-gradient-to-r from-red-400 to-red-600') }}"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>0%</span>
                    <span>50%</span>
                    <span>100%</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-list-ul text-blue-600 mr-3"></i> รายละเอียดคำถามและคำตอบ
        </h2>
        
        <div class="space-y-8">
            @foreach($questions as $index => $question)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 p-5 border-b">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">{{ $question->question_text }}</h3>
                                <p class="text-sm text-gray-500 mt-1">แสดงที่เวลา {{ floor($question->time_to_show / 60) }}:{{ str_pad($question->time_to_show % 60, 2, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            
                            @if(isset($userAnswers[$question->id]))
                                <div class="ml-auto">
                                    @if($userAnswers[$question->id]->is_correct)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> ตอบถูก
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> ตอบผิด
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="ml-auto">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-minus-circle mr-1"></i> ไม่ได้ตอบ
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h4 class="font-medium text-gray-700 mb-3">ตัวเลือกคำตอบ:</h4>
                        
                        <div class="space-y-3">
                            @foreach($question->answers as $answer)
                                <div class="p-4 rounded-lg border 
                                    @if(isset($userAnswers[$question->id]) && $userAnswers[$question->id]->answer_id == $answer->id)
                                        @if($answer->is_correct)
                                            bg-green-50 border-green-300
                                        @else
                                            bg-red-50 border-red-300
                                        @endif
                                    @elseif($answer->is_correct)
                                        bg-blue-50 border-blue-300
                                    @else
                                        bg-gray-50 border-gray-200
                                    @endif
                                ">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-0.5">
                                            @if(isset($userAnswers[$question->id]) && $userAnswers[$question->id]->answer_id == $answer->id)
                                                @if($answer->is_correct)
                                                    <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </div>
                                                @endif
                                            @elseif($answer->is_correct)
                                                <div class="w-6 h-6 rounded-full bg-blue-500 text-white flex items-center justify-center">
                                                    <i class="fas fa-check text-xs"></i>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center">
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="ml-3 flex-1">
                                            <p class="text-gray-800">{{ $answer->answer_text }}</p>
                                            
                                            @if(isset($userAnswers[$question->id]) && $userAnswers[$question->id]->answer_id == $answer->id)
                                                <p class="text-sm {{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }} mt-1 font-medium">
                                                    {{ $answer->is_correct ? 'คุณตอบถูกต้อง!' : 'คุณตอบผิด' }}
                                                    @if(!$answer->is_correct)
                                                        - ควรเลือกคำตอบที่ถูกต้อง
                                                    @endif
                                                </p>
                                            @elseif($answer->is_correct)
                                                <p class="text-sm text-blue-600 mt-1 font-medium">นี่คือคำตอบที่ถูกต้อง</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(!isset($userAnswers[$question->id]))
                            <div class="mt-4 bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
                                <p class="text-yellow-700 text-sm flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    คุณไม่ได้ตอบคำถามนี้
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="bg-gray-50 rounded-xl shadow-md p-8 text-center">
        <h2 class="text-xl font-bold text-gray-800 mb-4">ต้องการเรียนรู้เพิ่มเติม?</h2>
        <p class="text-gray-600 mb-6">กลับไปที่หน้าแรกเพื่อดูคอร์สอื่นๆ ที่น่าสนใจ</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('course.show', $course) }}" class="inline-flex items-center justify-center px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                <i class="fas fa-redo-alt mr-2"></i> เรียนคอร์สนี้อีกครั้ง
            </a>
            
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-home mr-2"></i> กลับไปหน้าหลัก
            </a>
        </div>
    </div>
@endsection