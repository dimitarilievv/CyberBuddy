<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\Teacher\TeacherDashboardController;

// Јавни страни
Route::view('/', 'welcome');

// Сè што бара логин
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — пренасочува по улога
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Модули
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{slug}', [ModuleController::class, 'show'])->name('modules.show');
    Route::post('/modules/{slug}/enroll', [ModuleController::class, 'enroll'])->name('modules.enroll');


    // Квизови
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    // Сценарија
    Route::get('/scenarios/{scenario}', [ScenarioController::class, 'show'])->name('scenarios.show');
    Route::post('/scenarios/{scenario}/submit', [ScenarioController::class, 'submit'])->name('scenarios.submit');
// routes/web.php or routes/api.php

    Route::get('/modules/{module}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/modules/{module}/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    // Профил
    Route::view('profile', 'profile')->name('profile');
});

// === ДЕТЕ Dashboard ===
Route::middleware(['auth', 'verified', 'role:child'])->prefix('child')->group(function () {
    Route::view('/dashboard', 'child.dashboard')->name('child.dashboard');
});

// === РОДИТЕЛ Dashboard ===
Route::middleware(['auth', 'verified', 'role:parent'])->prefix('parent')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
});

// === НАСТАВНИК Dashboard ===
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
});

// === АДМИН Dashboard ===
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';
