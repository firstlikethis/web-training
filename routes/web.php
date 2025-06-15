<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

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
Route::middleware(['auth', \App\Http\Middleware\UserMiddleware::class])->group(function () {
    Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
    Route::post('/course/{course}/progress', [CourseController::class, 'saveProgress'])->name('course.progress');
    Route::post('/course/{course}/answer', [CourseController::class, 'submitAnswer'])->name('course.answer');
    Route::get('/course/{course}/summary', [CourseController::class, 'summary'])->name('course.summary');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Users management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/delete', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
    
    // Categories management
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::post('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::post('/categories/{category}/delete', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
    // Courses management
    Route::get('/courses', [CourseController::class, 'adminIndex'])->name('admin.courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/courses/upload-video', [CourseController::class, 'storeVideo'])->name('admin.courses.store_video');
    Route::get('/courses/{course}/edit-details', [CourseController::class, 'editDetails'])->name('admin.courses.edit_details');
    Route::post('/courses/{course}/store-details', [CourseController::class, 'storeDetails'])->name('admin.courses.store_details');
    Route::post('/courses/{course}/cancel-draft', [CourseController::class, 'cancelDraft'])->name('admin.courses.cancel_draft');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::post('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::post('/courses/{course}/delete', [CourseController::class, 'destroy'])->name('admin.courses.destroy');
    
    Route::post('/courses/{course}/questions', [CourseController::class, 'addQuestion'])->name('admin.courses.questions.add');
    Route::post('/questions/{question}/delete', [CourseController::class, 'deleteQuestion'])->name('admin.courses.questions.delete');
});