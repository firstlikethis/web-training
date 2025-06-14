<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserProgress;
use App\Models\UserAnswer;
use App\Services\VideoProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use getID3;

class CourseController extends Controller
{
    protected $videoProgressService;

    public function __construct(VideoProgressService $videoProgressService)
    {
        $this->videoProgressService = $videoProgressService;
    }

    /**
     * แสดงรายการคอร์สทั้งหมดบนหน้าแรก (สำหรับ Public)
     */
    public function index()
    {
        $courses = Course::active()->orderBy('title')->get();
        return view('courses.index', compact('courses'));
    }

    /**
     * แสดงรายละเอียดคอร์สและวิดีโอพร้อมคำถาม
     */
    public function show(Course $course)
    {
        // ตรวจสอบว่าคอร์สยังใช้งานได้อยู่หรือไม่
        if (!$course->is_active) {
            return redirect()->route('home')->with('error', 'คอร์สนี้ไม่สามารถเข้าถึงได้');
        }

        // ตรวจสอบว่าผู้ใช้สามารถเข้าถึงคอร์สนี้ได้หรือไม่
        if (!$this->videoProgressService->canAccessCourse(auth()->user(), $course)) {
            return redirect()->route('home')->with('error', 'คุณไม่สามารถเข้าถึงคอร์สนี้ได้');
        }

        // ดึงข้อมูลความคืบหน้าของผู้ใช้
        $userProgress = UserProgress::firstOrNew([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
        ]);

        // ดึงคำถามทั้งหมดของคอร์สนี้
        $questions = $course->questions()
            ->with('answers')
            ->where('is_active', true)
            ->orderBy('time_to_show')
            ->get();

        return view('courses.show', compact('course', 'questions', 'userProgress'));
    }

    /**
     * บันทึกความคืบหน้าในการดูวิดีโอ
     */
    public function saveProgress(Request $request, Course $course)
    {
        $request->validate([
            'current_time' => 'required|integer|min:0',
            'is_completed' => 'boolean',
        ]);

        $progress = $this->videoProgressService->saveProgress(
            auth()->user(),
            $course,
            $request->current_time,
            $request->is_completed ?? false
        );

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }

    /**
     * บันทึกคำตอบของผู้ใช้
     */
    public function submitAnswer(Request $request, Course $course)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required|exists:answers,id',
            'answer_time' => 'nullable|integer|min:0',
        ]);

        // ตรวจสอบว่าคำถามนี้อยู่ในคอร์สนี้หรือไม่
        $question = Question::where('id', $request->question_id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // ดึงข้อมูลคำตอบที่ถูก
        $selectedAnswer = Answer::findOrFail($request->answer_id);
        $isCorrect = $selectedAnswer->is_correct;

        // บันทึกคำตอบของผู้ใช้
        $userAnswer = UserAnswer::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $course->id,
                'question_id' => $question->id,
                'attempt_number' => UserProgress::where('user_id', auth()->id())
                    ->where('course_id', $course->id)
                    ->value('attempt_count') ?? 1,
            ],
            [
                'answer_id' => $selectedAnswer->id,
                'is_correct' => $isCorrect,
                'answer_time_seconds' => $request->answer_time,
            ]
        );

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'user_answer' => $userAnswer,
        ]);
    }

    /**
     * แสดงสรุปผลคะแนนหลังจากดูวิดีโอและตอบคำถามครบแล้ว
     */
    public function summary(Course $course)
    {
        // ตรวจสอบว่าผู้ใช้ได้ดูวิดีโอจนจบหรือไม่
        $userProgress = UserProgress::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        if (!$userProgress || !$userProgress->is_completed) {
            return redirect()->route('course.show', $course)
                ->with('error', 'คุณต้องดูวิดีโอให้จบก่อนจึงจะดูสรุปคะแนนได้');
        }

        // ดึงข้อมูลคำถามและคำตอบของผู้ใช้
        $questions = $course->questions()
            ->with('answers')
            ->where('is_active', true)
            ->get();

        $userAnswers = UserAnswer::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('attempt_number', $userProgress->attempt_count)
            ->with('question', 'answer')
            ->get()
            ->keyBy('question_id');

        // คำนวณคะแนน
        $totalQuestions = $questions->count();
        $correctAnswers = $userAnswers->where('is_correct', true)->count();
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        return view('courses.summary', compact('course', 'questions', 'userAnswers', 'totalQuestions', 'correctAnswers', 'score'));
    }

    /**
     * แสดงรายการคอร์สทั้งหมดในหน้า Admin
     */
    public function adminIndex()
    {
        $courses = Course::withCount('questions')
            ->orderBy('title')
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * แสดงหน้าสร้างคอร์สใหม่
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * คำนวณความยาววิดีโอในวินาที
     */
    private function getVideoDuration($videoPath)
    {
        $getID3 = new getID3;
        $fileInfo = $getID3->analyze(storage_path('app/public/' . $videoPath));
        
        if (isset($fileInfo['playtime_seconds'])) {
            return (int) $fileInfo['playtime_seconds'];
        }
        
        return 0;
    }

    /**
     * บันทึกข้อมูลคอร์สใหม่พร้อมคำถาม
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'video_type' => 'required|in:upload,url',
            'video' => 'nullable|file|mimes:mp4,webm,ogg|max:102400',
            'video_url' => 'nullable|url',
            'duration_seconds' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            // คำถามและคำตอบ
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.time_to_show' => 'required|integer|min:0',
            'questions.*.time_limit_seconds' => 'required|integer|min:5',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.answer_text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // อัปโหลดไฟล์รูปภาพ
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            // จัดการวิดีโอ
            $videoPath = null;
            $videoUrl = null;
            $durationSeconds = $request->duration_seconds ?? 0;

            if ($request->video_type == 'upload' && $request->hasFile('video')) {
                $videoPath = $request->file('video')->store('videos', 'public');
                // พยายามคำนวณความยาววิดีโอถ้ามี getID3
                try {
                    if (class_exists('getID3')) {
                        $durationSeconds = $this->getVideoDuration($videoPath);
                    }
                } catch (\Exception $e) {
                    // ถ้าไม่สามารถคำนวณได้ ใช้ค่าที่ผู้ใช้กรอก
                }
            } elseif ($request->video_type == 'url' && $request->video_url) {
                $videoUrl = $request->video_url;
            }

            // สร้างคอร์ส
            $course = Course::create([
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail' => $thumbnailPath,
                'video_path' => $videoPath,
                'video_url' => $videoUrl,
                'duration_seconds' => $durationSeconds,
                'is_active' => $request->has('is_active'),
            ]);

            // บันทึกคำถามและคำตอบ (ถ้ามี)
            if ($request->has('questions') && is_array($request->questions)) {
                // โค้ดการบันทึกคำถามยังคงเหมือนเดิม
            }

            DB::commit();
            return redirect()->route('admin.courses.index')
                ->with('success', 'สร้างคอร์ส ' . $course->title . ' พร้อมคำถามเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    /**
     * แสดงหน้าแก้ไขข้อมูลคอร์ส
     */
    public function edit(Course $course)
    {
        // โหลดคำถามและคำตอบ
        $course->load('questions.answers');
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * อัปเดตข้อมูลคอร์ส
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'video' => 'nullable|file|mimes:mp4,webm,ogg|max:102400',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // อัปเดตรูปภาพถ้ามีการอัปโหลดใหม่
            if ($request->hasFile('thumbnail')) {
                // ลบรูปภาพเก่า
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }

                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $course->thumbnail = $thumbnailPath;
            }

            // อัปเดตวิดีโอถ้ามีการอัปโหลดใหม่
            if ($request->hasFile('video')) {
                // ลบวิดีโอเก่า
                if ($course->video_path) {
                    Storage::disk('public')->delete($course->video_path);
                }

                $videoPath = $request->file('video')->store('videos', 'public');
                $course->video_path = $videoPath;
                
                // คำนวณความยาววิดีโอใหม่
                $course->duration_seconds = $this->getVideoDuration($videoPath);
            }

            $course->update([
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();
            return redirect()->route('admin.courses.index')
                ->with('success', 'อัปเดตคอร์ส ' . $course->title . ' เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    /**
     * ลบคอร์ส
     */
    public function destroy(Course $course)
    {
        try {
            DB::beginTransaction();
            
            // ลบไฟล์ที่เกี่ยวข้อง
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            if ($course->video_path) {
                Storage::disk('public')->delete($course->video_path);
            }

            $courseTitle = $course->title;
            $course->delete();
            
            DB::commit();
            return redirect()->route('admin.courses.index')
                ->with('success', 'ลบคอร์ส ' . $courseTitle . ' เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage()]);
        }
    }
    
    /**
     * เพิ่มคำถามใหม่ในคอร์ส
     */
    public function addQuestion(Request $request, Course $course)
    {
        $request->validate([
            'question_text' => 'required|string',
            'time_to_show' => 'required|integer|min:0',
            'time_limit_seconds' => 'required|integer|min:5',
            'answers' => 'required|array|min:2',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // ตรวจสอบว่ามีคำตอบที่ถูกอย่างน้อย 1 ข้อ
            $hasCorrectAnswer = false;
            foreach ($request->answers as $answer) {
                if (isset($answer['is_correct']) && $answer['is_correct']) {
                    $hasCorrectAnswer = true;
                    break;
                }
            }

            if (!$hasCorrectAnswer) {
                throw new \Exception('ต้องมีคำตอบที่ถูกอย่างน้อย 1 ข้อ');
            }

            // สร้างคำถาม
            $question = Question::create([
                'course_id' => $course->id,
                'question_text' => $request->question_text,
                'time_to_show' => $request->time_to_show,
                'time_limit_seconds' => $request->time_limit_seconds,
                'is_active' => true,
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
            return redirect()->route('admin.courses.edit', $course)
                ->with('success', 'เพิ่มคำถามเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    /**
     * ลบคำถาม
     */
    public function deleteQuestion(Question $question)
    {
        try {
            $courseId = $question->course_id;
            $question->delete();

            return redirect()->route('admin.courses.edit', ['course' => $courseId])
                ->with('success', 'ลบคำถามเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage()]);
        }
    }
}