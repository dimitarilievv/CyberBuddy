<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\MediaFileController;
use App\Http\Controllers\QuestionAnswerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\ScenarioAttemptController;
use App\Http\Controllers\ScenarioChoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\Teacher\TeacherDashboardController;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard redirecting based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboards
    Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    Route::middleware(['auth', 'verified', 'role:parent'])->prefix('parent')->group(function () {
        Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
    });

    Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    });

    Route::middleware(['auth', 'verified', 'role:child'])->prefix('child')->group(function () {
        Route::view('/dashboard', 'child.dashboard')->name('child.dashboard');
    });


    // Certificates
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
        Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
        Route::post('/enrollments/{enrollment}/certificate', [CertificateController::class, 'generate'])->name('certificates.generate');
    });

    // Lessons
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/modules/{module}/lessons/{lesson}', [LessonController::class, 'show'])
            ->name('lessons.show');

        Route::post('/modules/{module}/lessons/{lesson}/complete', [LessonController::class, 'complete'])
            ->name('lessons.complete');
    });

    //Media file
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/lessons/{lesson}/media', [MediaFileController::class, 'index'])->name('media.index');
        Route::post('/lessons/{lesson}/media', [MediaFileController::class, 'store'])->name('media.store');
        Route::delete('/media/{media}', [MediaFileController::class, 'destroy'])->name('media.destroy');
    });

    // Modules
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{slug}', [ModuleController::class, 'show'])->name('modules.show');
    Route::post('/modules/{slug}/enroll', [ModuleController::class, 'enroll'])->name('modules.enroll');

    //Questions
    Route::get('/quizzes/{quizId}/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions/store', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/questions/{questionId}/check', [QuestionController::class, 'checkAnswer'])->name('questions.checkAnswer');

    //QuestionAnswers
    Route::middleware(['auth'])->group(function () {
        Route::get('/quiz/attempt/{attemptId}', [QuestionAnswerController::class, 'index'])->name('quiz.attempt.show');
        Route::post('/quiz/attempt/{attemptId}/submit', [QuestionAnswerController::class, 'submit'])->name('quiz.attempt.submit');
        Route::get('/quiz/attempt/{attemptId}/evaluate', [QuestionAnswerController::class, 'evaluate'])->name('quiz.attempt.evaluate');
    });

    // Quizzes
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    // QuizAttempts
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/quiz-attempts', [QuizAttemptController::class, 'index'])->name('quiz_attempts.index');
        Route::get('/quiz-attempts/{id}', [QuizAttemptController::class, 'show'])->name('quiz_attempts.show');
        Route::post('/quizzes/{quizId}/attempts', [QuizAttemptController::class, 'start'])->name('quiz_attempts.start');
        Route::post('/quiz-attempts/{id}/submit', [QuizAttemptController::class, 'submit'])->name('quiz_attempts.submit');
        Route::get('/quizzes/{quizId}/attempts/my', [QuizAttemptController::class, 'myAttempts'])->name('quiz_attempts.my_attempts');
    });

    // Scenarios
    Route::middleware(['auth'])->group(function () {
        Route::get('/scenarios/{scenario}', [ScenarioController::class, 'show'])->name('scenarios.show');
        Route::post('/scenarios/{scenario}/submit', [ScenarioController::class, 'submit'])->name('scenarios.submit');
    });

    //Scenario attempts
    Route::prefix('scenarios/{scenario}')->group(function () {
        Route::get('/history', [ScenarioAttemptController::class, 'history'])->name('scenarios.history');
        Route::post('/attempts/submit', [ScenarioAttemptController::class, 'submit'])->name('scenarios.attempts.submit');
    });

    //Scenario choices
    Route::prefix('scenarios/{scenario}/choices')->group(function () {
        Route::get('/', [ScenarioChoiceController::class, 'index'])->name('scenarios.choices.index');
        Route::post('/store', [ScenarioChoiceController::class, 'store'])->name('scenarios.choices.store');
        Route::get('/scenario-choice/{choice}/evaluate', [ScenarioChoiceController::class, 'evaluate'])->name('scenarios.choices.evaluate');
    });

    // Badges
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');
        Route::post('/badges/check', [BadgeController::class, 'checkAndAward'])->name('badges.check');
    });

    // Profile
    Route::view('profile', 'profile')->name('profile');
});


require __DIR__ . '/auth.php';
