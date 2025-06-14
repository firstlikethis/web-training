<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/home', [CourseController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard-x7cA1v', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/dashboard-x7cA1v', [AuthController::class, 'adminLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User routes
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
    Route::post('/course/{course}/progress', [CourseController::class, 'saveProgress'])->name('course.progress');
    Route::post('/course/{course}/answer', [CourseController::class, 'submitAnswer'])->name('course.answer');
    Route::get('/course/{course}/summary', [CourseController::class, 'summary'])->name('course.summary');
});

Route::prefix('dashboard-x7cA1v')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Users management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/delete', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
    
    // Courses management
    Route::get('/courses', [CourseController::class, 'adminIndex'])->name('admin.courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::post('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::post('/courses/{course}/delete', [CourseController::class, 'destroy'])->name('admin.courses.destroy');
    
    // Questions management
    Route::get('/questions', [QuestionController::class, 'index'])->name('admin.questions.index');
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::post('/questions/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::post('/questions/{question}/delete', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');
    
    // Answers management for questions
    Route::post('/questions/{question}/answers', [QuestionController::class, 'storeAnswer'])->name('admin.questions.answers.store');
    Route::post('/questions/answers/{answer}', [QuestionController::class, 'updateAnswer'])->name('admin.questions.answers.update');
    Route::post('/questions/answers/{answer}/delete', [QuestionController::class, 'destroyAnswer'])->name('admin.questions.answers.destroy');
});