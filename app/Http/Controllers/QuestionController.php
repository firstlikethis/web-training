<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * แสดงรายการคำถามทั้งหมด
     */
    public function index(Request $request)
    {
        $query = Question::with(['course', 'answers']);
        
        // กรองตาม course_id ถ้ามีการระบุ
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }
        
        $questions = $query->orderBy('course_id')
            ->orderBy('time_to_show')
            ->paginate(10);
        
        $courses = Course::orderBy('title')->get();
        
        return view('admin.questions.index', compact('questions', 'courses'));
    }

    /**
     * แสดงหน้าสร้างคำถามใหม่
     */
    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.questions.create', compact('courses'));
    }

    /**
     * บันทึกข้อมูลคำถามใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'question_text' => 'required|string',
            'time_to_show' => 'required|integer|min:0',
            'time_limit_seconds' => 'required|integer|min:5',
            'is_active' => 'boolean',
            'answers' => 'required|array|min:2',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        // ตรวจสอบว่ามีคำตอบที่ถูกอย่างน้อย 1 ข้อ
        $hasCorrectAnswer = false;
        foreach ($request->answers as $answer) {
            if (isset($answer['is_correct']) && $answer['is_correct']) {
                $hasCorrectAnswer = true;
                break;
            }
        }

        if (!$hasCorrectAnswer) {
            return back()->withInput()->withErrors(['answers' => 'ต้องมีคำตอบที่ถูกอย่างน้อย 1 ข้อ']);
        }

        DB::beginTransaction();
        try {
            // สร้างคำถาม
            $question = Question::create([
                'course_id' => $request->course_id,
                'question_text' => $request->question_text,
                'time_to_show' => $request->time_to_show,
                'time_limit_seconds' => $request->time_limit_seconds,
                'is_active' => $request->has('is_active'),
            ]);

            // สร้างคำตอบ
            foreach ($request->answers as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => isset($answerData['is_correct']) && $answerData['is_correct'] ? true : false,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.questions.index', ['course_id' => $request->course_id])
                ->with('success', 'สร้างคำถามเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    /**
     * แสดงหน้าแก้ไขข้อมูลคำถาม
     */
    public function edit(Question $question)
    {
        $courses = Course::orderBy('title')->get();
        $question->load('answers');
        
        return view('admin.questions.edit', compact('question', 'courses'));
    }

    /**
     * อัปเดตข้อมูลคำถาม
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'question_text' => 'required|string',
            'time_to_show' => 'required|integer|min:0',
            'time_limit_seconds' => 'required|integer|min:5',
            'is_active' => 'boolean',
        ]);

        $question->update([
            'course_id' => $request->course_id,
            'question_text' => $request->question_text,
            'time_to_show' => $request->time_to_show,
            'time_limit_seconds' => $request->time_limit_seconds,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.questions.index', ['course_id' => $question->course_id])
            ->with('success', 'อัปเดตคำถามเรียบร้อยแล้ว');
    }

    /**
     * ลบคำถาม
     */
    public function destroy(Question $question)
    {
        $courseId = $question->course_id;
        $question->delete();

        return redirect()->route('admin.questions.index', ['course_id' => $courseId])
            ->with('success', 'ลบคำถามเรียบร้อยแล้ว');
    }

    /**
     * บันทึกคำตอบใหม่สำหรับคำถาม
     */
    public function storeAnswer(Request $request, Question $question)
    {
        $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        // ถ้าคำตอบนี้ถูก ให้เปลี่ยนคำตอบอื่นๆ เป็นผิด
        if ($request->has('is_correct') && $request->is_correct) {
            $question->answers()->update(['is_correct' => false]);
        }

        $answer = Answer::create([
            'question_id' => $question->id,
            'answer_text' => $request->answer_text,
            'is_correct' => $request->has('is_correct'),
        ]);

        return redirect()->route('admin.questions.edit', $question)
            ->with('success', 'เพิ่มคำตอบเรียบร้อยแล้ว');
    }

    /**
     * อัปเดตข้อมูลคำตอบ
     */
    public function updateAnswer(Request $request, Answer $answer)
    {
        $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        $question = $answer->question;

        // ถ้าคำตอบนี้ถูก ให้เปลี่ยนคำตอบอื่นๆ เป็นผิด
        if ($request->has('is_correct') && $request->is_correct) {
            $question->answers()->where('id', '!=', $answer->id)->update(['is_correct' => false]);
        }

        $answer->update([
            'answer_text' => $request->answer_text,
            'is_correct' => $request->has('is_correct'),
        ]);

        return redirect()->route('admin.questions.edit', $question)
            ->with('success', 'อัปเดตคำตอบเรียบร้อยแล้ว');
    }

    /**
     * ลบคำตอบ
     */
    public function destroyAnswer(Answer $answer)
    {
        $question = $answer->question;
        $answer->delete();

        // ตรวจสอบว่ายังมีคำตอบที่ถูกเหลืออยู่หรือไม่
        $hasCorrectAnswer = $question->answers()->where('is_correct', true)->exists();

        // ถ้าไม่มีคำตอบที่ถูกเหลืออยู่ ให้กำหนดคำตอบแรกเป็นคำตอบที่ถูก
        if (!$hasCorrectAnswer && $question->answers()->count() > 0) {
            $question->answers()->first()->update(['is_correct' => true]);
        }

        return redirect()->route('admin.questions.edit', $question)
            ->with('success', 'ลบคำตอบเรียบร้อยแล้ว');
    }
}