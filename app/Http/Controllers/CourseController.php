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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use getID3;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;

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
        
        // Load user progress for each course if authenticated
        if (auth()->check()) {
            $userId = auth()->id();
            $courses->load(['userProgress' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }
        
        return view('courses.index', compact('courses'));
    }

    /**
     * แสดงรายละเอียดคอร์สและวิดีโอพร้อมคำถาม
     */
    public function show(Course $course)
    {
        // ตรวจสอบว่าคอร์สยังใช้งานได้อยู่หรือไม่
        if (!$course->is_active || $course->status !== 'published') {
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

        // ดึงคำถามทั้งหมดของคอร์สนี้ (ถ้ามี)
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
        $courses = Course::where('status', 'published')
            ->withCount('questions')
            ->orderBy('title')
            ->paginate(10);

        $draftCourses = Course::where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // ดึงข้อมูลหมวดหมู่
        $categories = Category::orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'draftCourses', 'categories'));
    }

    /**
     * แสดงหน้าสร้างคอร์สใหม่ (ขั้นตอนที่ 1 - อัปโหลดวิดีโอ)
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * บันทึกวิดีโอและนำไปสู่การสร้างคอร์ส (ขั้นตอนที่ 1)
     */
    public function storeVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,ogg|max:102400',
        ]);

        try {
            DB::beginTransaction();
            
            // สร้างคอร์สว่างๆ ก่อน (สถานะ draft)
            $course = new Course();
            $course->title = 'Draft Course ' . now()->format('Y-m-d H:i:s');
            $course->status = 'draft';
            $course->is_active = false;
            
            // จัดการวิดีโอที่อัปโหลด
            try {
                // ตรวจสอบขนาดไฟล์ก่อนอัปโหลด
                $videoFile = $request->file('video');
                $fileSize = $videoFile->getSize();
                $maxSize = min((int) ini_get('upload_max_filesize'), (int) ini_get('post_max_size')) * 1024 * 1024;
                
                if ($fileSize > $maxSize) {
                    throw new \Exception("ไฟล์มีขนาดใหญ่เกินไป (สูงสุด: " . ($maxSize / (1024 * 1024)) . "MB)");
                }
                
                // ตรวจสอบสิทธิ์การเขียนไฟล์
                $storagePath = storage_path('app/public/videos');
                if (!is_dir($storagePath)) {
                    if (!mkdir($storagePath, 0755, true)) {
                        throw new \Exception("ไม่สามารถสร้างโฟลเดอร์ videos ได้ กรุณาตรวจสอบสิทธิ์");
                    }
                }
                
                if (!is_writable($storagePath)) {
                    throw new \Exception("ไม่สามารถเขียนไฟล์ลงในโฟลเดอร์ videos ได้ กรุณาตรวจสอบสิทธิ์");
                }
                
                // อัปโหลดไฟล์
                $videoPath = $videoFile->store('videos', 'public');
                
                if (!$videoPath) {
                    throw new \Exception("ไม่สามารถอัปโหลดไฟล์ได้ โปรดลองอีกครั้ง");
                }
                
                // คำนวณความยาววิดีโอโดยใช้ getID3
                $durationSeconds = $this->getVideoDuration($videoPath);
                
                $course->video_path = $videoPath;
                $course->duration_seconds = $durationSeconds;
            } catch (\Exception $e) {
                throw new \Exception("อัปโหลดวิดีโอล้มเหลว: " . $e->getMessage());
            }
            
            $course->save();
            
            DB::commit();
            
            // นำไปสู่ขั้นตอนที่ 2
            return redirect()->route('admin.courses.edit_details', $course)
                ->with('success', 'บันทึกข้อมูลวิดีโอเรียบร้อยแล้ว กรุณากรอกรายละเอียดคอร์ส');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }
    
    /**
     * แสดงหน้าแก้ไขรายละเอียดคอร์ส (ขั้นตอนที่ 2)
     */
    public function editDetails(Course $course)
    {
        // ตรวจสอบว่าเป็น draft หรือไม่
        if ($course->status !== 'draft') {
            return redirect()->route('admin.courses.edit', $course);
        }
        
        // ดึงข้อมูลหมวดหมู่
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.courses.create_step2', compact('course', 'categories'));
    }
    
    /**
     * บันทึกรายละเอียดคอร์สและเผยแพร่ (ขั้นตอนที่ 2)
     */
    public function storeDetails(Request $request, Course $course)
    {
        // ตรวจสอบข้อมูลสำหรับคอร์สที่จะเผยแพร่
        if ($request->has('publish')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'thumbnail' => 'nullable|image|max:2048',
                'category_id' => 'nullable|exists:categories,id',
                'is_active' => 'boolean',
                'questions' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            
            // ตรวจสอบเพิ่มเติมถ้ามีคำถาม
            if ($request->has('questions') && is_array($request->questions)) {
                foreach ($request->questions as $index => $questionData) {
                    // ตรวจสอบว่าคำถามมีข้อมูลครบถ้วนหรือไม่
                    if (!isset($questionData['question_text']) || 
                        !isset($questionData['time_to_show']) || 
                        !isset($questionData['time_limit_seconds']) ||
                        !isset($questionData['answers']) || 
                        !is_array($questionData['answers'])) {
                        continue; // ข้ามคำถามที่ข้อมูลไม่ครบถ้วน
                    }
                    
                    // ตรวจสอบรูปแบบข้อมูลของคำถาม
                    $questionValidator = Validator::make($questionData, [
                        'question_text' => 'required|string',
                        'time_to_show' => 'required|integer|min:0',
                        'time_limit_seconds' => 'required|integer|min:5',
                        'answers' => 'required|array|min:2',
                    ]);
                    
                    if ($questionValidator->fails()) {
                        return back()->withErrors($questionValidator)->withInput();
                    }
                    
                    // ตรวจสอบคำตอบ
                    if (isset($questionData['answers']) && is_array($questionData['answers'])) {
                        foreach ($questionData['answers'] as $aIndex => $answerData) {
                            if (!isset($answerData['answer_text'])) {
                                continue; // ข้ามคำตอบที่ไม่มีข้อความ
                            }
                            
                            $answerValidator = Validator::make($answerData, [
                                'answer_text' => 'required|string',
                            ]);
                            
                            if ($answerValidator->fails()) {
                                return back()->withErrors($answerValidator)->withInput();
                            }
                        }
                    }
                }
            }
        } else {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'thumbnail' => 'nullable|image|max:2048',
                'category_id' => 'nullable|exists:categories,id',
                'is_active' => 'boolean',
            ]);
            
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
        }

         DB::beginTransaction();
        try {
            // อัปโหลดไฟล์รูปภาพ
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $course->thumbnail = $thumbnailPath;
            }
            
            // อัปเดตข้อมูลคอร์ส
            if ($request->has('title') && !empty($request->title)) {
                $course->title = $request->title;
            }
            $course->description = $request->description;
            $course->is_active = $request->has('is_active');
            $course->category_id = $request->category_id; 
            
            if ($request->has('publish')) {
                $course->status = 'published';
            }
            
            $course->save();
            
            // บันทึกคำถามและคำตอบ (ถ้ามี)
            if ($request->has('questions') && is_array($request->questions)) {
                foreach ($request->questions as $questionData) {
                    if (!isset($questionData['question_text']) || empty($questionData['question_text'])) {
                        continue; // ข้ามคำถามที่ไม่มีข้อความ
                    }
                    
                    $question = new Question([
                        'question_text' => $questionData['question_text'],
                        'time_to_show' => $questionData['time_to_show'] ?? 0,
                        'time_limit_seconds' => $questionData['time_limit_seconds'] ?? 30,
                        'is_active' => true,
                    ]);
                    
                    $course->questions()->save($question);
                    
                    // ตรวจสอบว่ามีคำตอบที่ถูกอย่างน้อย 1 ข้อ
                    $hasCorrectAnswer = false;
                    
                    // บันทึกคำตอบ
                    if (isset($questionData['answers']) && is_array($questionData['answers'])) {
                        foreach ($questionData['answers'] as $answerData) {
                            if (!isset($answerData['answer_text']) || empty($answerData['answer_text'])) {
                                continue; // ข้ามคำตอบที่ไม่มีข้อความ
                            }
                            
                            $isCorrect = isset($answerData['is_correct']) && $answerData['is_correct'];
                            
                            if ($isCorrect) {
                                $hasCorrectAnswer = true;
                            }
                            
                            $answer = new Answer([
                                'answer_text' => $answerData['answer_text'],
                                'is_correct' => $isCorrect,
                            ]);
                            
                            $question->answers()->save($answer);
                        }
                    }
                    
                    // ถ้าไม่มีคำตอบที่ถูก ให้ตั้งคำตอบแรกเป็นคำตอบที่ถูก
                    if (!$hasCorrectAnswer && $question->answers()->count() > 0) {
                        $firstAnswer = $question->answers()->first();
                        $firstAnswer->is_correct = true;
                        $firstAnswer->save();
                    }
                }
            }
            
            DB::commit();
            
            if ($request->has('publish')) {
                return redirect()->route('admin.courses.index')
                    ->with('success', 'สร้างคอร์ส ' . $course->title . ' เรียบร้อยแล้ว');
            } else {
                return redirect()->route('admin.courses.edit_details', $course)
                    ->with('success', 'บันทึกร่างคอร์สเรียบร้อยแล้ว');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }
    
    /**
     * ยกเลิกการสร้างคอร์ส (ลบ draft)
     */
    public function cancelDraft(Course $course)
    {
        // ตรวจสอบว่าเป็น draft หรือไม่
        if ($course->status !== 'draft') {
            return redirect()->route('admin.courses.index')
                ->with('error', 'ไม่สามารถยกเลิกคอร์สที่เผยแพร่แล้ว');
        }
        
        try {
            DB::beginTransaction();
            
            // ลบไฟล์ที่เกี่ยวข้อง
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            if ($course->video_path) {
                Storage::disk('public')->delete($course->video_path);
            }
            
            // ลบคอร์ส
            $course->delete();
            
            DB::commit();
            return redirect()->route('admin.courses.index')
                ->with('success', 'ยกเลิกการสร้างคอร์สเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.courses.index')
                ->with('error', 'เกิดข้อผิดพลาดในการยกเลิกคอร์ส: ' . $e->getMessage());
        }
    }

    /**
     * แสดงหน้าแก้ไขข้อมูลคอร์ส
     */
    public function edit(Course $course)
    {
        // ตรวจสอบว่าคอร์สเผยแพร่แล้วหรือไม่
        if ($course->status !== 'published') {
            return redirect()->route('admin.courses.edit_details', $course);
        }
        
        $course->load('questions.answers');
        
        $categories = Category::active()->orderBy('name')->get();
        
        return view('admin.courses.edit', compact('course', 'categories'));
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
            'category_id' => 'nullable|exists:categories,id',
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

            $course->title = $request->title;
            $course->description = $request->description;
            $course->is_active = $request->has('is_active');
            $course->category_id = $request->category_id; 
            $course->save();

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
   
    /**
     * คำนวณความยาววิดีโอในวินาทีโดยใช้ PHP-FFmpeg หรือ fallback method
     * 
     * @param string $videoPath
     * @return int
     */
    private function getVideoDuration($videoPath)
    {
        try {
            $fullPath = storage_path('app/public/' . $videoPath);
            
            if (!file_exists($fullPath)) {
                Log::error("Video file not found: " . $fullPath);
                throw new \Exception("Video file not found");
            }
            
            // ใช้ FFProbe จาก Service Container
            $ffprobe = app('ffprobe');
            
            // ดึงความยาววิดีโอ
            $duration = $ffprobe->format($fullPath)->get('duration');
            
            if (is_numeric($duration)) {
                $durationSeconds = (int) $duration;
                Log::info('Video duration detected with PHP-FFmpeg: ' . $durationSeconds . ' seconds');
                return $durationSeconds;
            } else {
                throw new \Exception("Could not determine video duration with PHP-FFmpeg");
            }
        } catch (\Exception $e) {
            Log::warning("Using fallback method to estimate video duration: " . $e->getMessage());
            
            // ใช้การประมาณจากขนาดไฟล์เป็นทางเลือกสุดท้าย
            $fileSize = Storage::disk('public')->size($videoPath);
            $fileSizeInMB = $fileSize / (1024 * 1024);
            $estimatedDuration = round($fileSizeInMB * 10); // ประมาณ 10 วินาทีต่อ 1MB
            
            Log::info('Estimated video duration from file size: ' . max(30, $estimatedDuration) . ' seconds');
            return max(30, $estimatedDuration);
        }
    }
}