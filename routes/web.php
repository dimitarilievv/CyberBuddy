<?php

use App\Http\Controllers\Admin\AdminAIContentController;
use App\Http\Controllers\AIContentController;
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
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\UserBadgeController;
use App\Http\Controllers\AiContentSuggestionController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AiInteractionController;
use App\Http\Controllers\ReportedContentController;
use App\Http\Controllers\ChildDashboardController;


Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard redirecting based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboards
    Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        // AI Content Generation
        Route::get('/ai/lesson', [\App\Http\Controllers\Admin\AdminAIContentController::class, 'showLessonForm'])->name('admin.ai.lesson.form');
        Route::post('/ai/lesson', [\App\Http\Controllers\Admin\AdminAIContentController::class, 'generateLesson'])->name('admin.ai.lesson.generate');
        Route::get('/ai/quiz', [\App\Http\Controllers\Admin\AdminAIContentController::class, 'showQuizForm'])->name('admin.ai.quiz.form');
        Route::post('/ai/quiz', [\App\Http\Controllers\Admin\AdminAIContentController::class, 'generateQuiz'])->name('admin.ai.quiz.generate');
        Route::get('/ai/scenario', [\App\Http\Controllers\Admin\AdminAIContentController::class, 'showScenarioForm'])->name('admin.ai.scenario.form');
        Route::post('/ai/scenario', [\App\Http\Controllers\Admin\AdminAIContentController::class, 'generateScenario'])->name('admin.ai.scenario.generate');
    });

    Route::middleware(['auth', 'verified', 'role:parent'])->prefix('parent')->group(function () {
        Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
    });
    Route::middleware(['web','auth','role:parent'])->group(function () {
        Route::post('/parent/add-child', [ParentDashboardController::class, 'addChild'])->name('parent.add-child');
    });
    Route::post('/parent/attach-child', [ParentDashboardController::class, 'attachChild'])->name('parent.attach-child');


    Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    });

//    Route::middleware(['auth', 'verified', 'role:child'])->prefix('child')->group(function () {
//        Route::view('/dashboard', 'child.dashboard')->name('child.dashboard');
//    });
    Route::get('/child/dashboard', [ChildDashboardController::class, 'index'])
        ->name('child.dashboard')
        ->middleware(['auth', 'verified', 'role:child']);


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



    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/lessons/{lesson}/resources', [ResourceController::class, 'index'])->name('resources.index');
        Route::get('/lessons/{lesson}/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/progress', [UserProgressController::class, 'index'])->name('user_progress.index');
        Route::get('/lessons/{lesson}/progress', [UserProgressController::class, 'show'])->name('user_progress.show');
    });



    // Modules
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{slug}', [ModuleController::class, 'show'])->name('modules.show');
    Route::post('/modules/{slug}/enroll', [ModuleController::class, 'enroll'])->name('modules.enroll');
    Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
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

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/my-enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('/my-completed', [EnrollmentController::class, 'completed'])->name('enrollments.completed');
        Route::post('/modules/{moduleId}/enroll', [EnrollmentController::class, 'enroll'])->name('modules.enroll');
        Route::get('/modules/{moduleId}/enrollments', [EnrollmentController::class, 'moduleEnrollments'])->name('enrollments.module');
        Route::get('/modules/{moduleId}/is-enrolled', [EnrollmentController::class, 'isEnrolled'])->name('enrollments.is_enrolled');
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

    Route::middleware(['auth'])->group(function () {
        Route::get('/my-badges', [UserBadgeController::class, 'myBadges'])->name('user_badges.index');
        Route::post('/award-badge', [UserBadgeController::class, 'award'])->name('user_badges.award');
        Route::get('/badge/{badgeId}/users', [UserBadgeController::class, 'usersWithBadge'])->name('user_badges.users');
    });


    // Leaderboard routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
        Route::get('/my-stats', [LeaderboardController::class, 'myStats'])->name('leaderboard.my_stats');
    });
    // Notifications
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');

        Route::get('/notifications/unread', [NotificationController::class, 'unread'])
            ->name('notifications.unread');

        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.read');

        Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.read_all');

        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
            ->name('notifications.destroy');

        Route::delete('/notifications', [NotificationController::class, 'destroyAll'])
            ->name('notifications.destroy_all');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        // Teacher review
        Route::middleware(['role:teacher'])->group(function () {
            Route::get('/ai-suggestions', [AiContentSuggestionController::class, 'index'])
                ->name('ai_suggestions.index');

            Route::patch('/ai-suggestions/{id}/approve', [AiContentSuggestionController::class, 'approve'])
                ->name('ai_suggestions.approve');

            Route::patch('/ai-suggestions/{id}/reject', [AiContentSuggestionController::class, 'reject'])
                ->name('ai_suggestions.reject');
        });

        // Optional: create (for testing) - you can restrict this too
        Route::get('/ai-suggestions/create', [AiContentSuggestionController::class, 'create'])
            ->name('ai_suggestions.create');

        Route::post('/ai-suggestions', [AiContentSuggestionController::class, 'store'])
            ->name('ai_suggestions.store');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
        Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity_logs.show');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/ai-interactions', [AiInteractionController::class, 'index'])->name('ai_interactions.index');
        Route::post('/ai-interactions', [AiInteractionController::class, 'store'])->name('ai_interactions.store');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/reported-contents', [ReportedContentController::class, 'index'])
            ->name('reported_contents.index');

        Route::get('/reported-contents/create', [ReportedContentController::class, 'create'])
            ->name('reported_contents.create');

        Route::post('/reported-contents', [ReportedContentController::class, 'store'])
            ->name('reported_contents.store');

        Route::get('/reported-contents/{id}', [ReportedContentController::class, 'show'])
            ->name('reported_contents.show');

        Route::patch('/reported-contents/{id}/review', [ReportedContentController::class, 'review'])
            ->name('reported_contents.review');
    });

    Route::prefix('admin/ai')->name('admin.ai.')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/lesson',    [AdminAIContentController::class, 'showLessonForm'])->name('lesson.form');
        Route::post('/lesson',   [AdminAIContentController::class, 'generateLesson'])->name('lesson.generate');

        Route::get('/quiz',      [AdminAIContentController::class, 'showQuizForm'])->name('quiz.form');
        Route::post('/quiz',     [AdminAIContentController::class, 'generateQuiz'])->name('quiz.generate');

        Route::get('/scenario',  [AdminAIContentController::class, 'showScenarioForm'])->name('scenario.form');
        Route::post('/scenario', [AdminAIContentController::class, 'generateScenario'])->name('scenario.generate');
    });

    // For Lessons
    Route::post('/teacher/lessons/{lesson}/approve', [\App\Http\Controllers\Teacher\TeacherDashboardController::class, 'approveLesson'])->name('teacher.lessons.approve');

// For Quizzes
    Route::post('/teacher/quizzes/{quiz}/approve', [\App\Http\Controllers\Teacher\TeacherDashboardController::class, 'approveQuiz'])->name('teacher.quizzes.approve');

// For Scenarios
    Route::post('/teacher/scenarios/{scenario}/approve', [\App\Http\Controllers\Teacher\TeacherDashboardController::class, 'approveScenario'])->name('teacher.scenarios.approve');
    Route::prefix('teacher/ai')->middleware(['auth','role:teacher'])->group(function () {
        // Forms
        Route::get('/lesson',   [\App\Http\Controllers\Teacher\TeacherAIContentController::class, 'showLessonForm'])->name('teacher.ai.lesson.form');
        Route::post('/lesson',  [\App\Http\Controllers\Teacher\TeacherAIContentController::class, 'generateLesson'])->name('teacher.ai.lesson.generate');
        Route::get('/quiz',     [\App\Http\Controllers\Teacher\TeacherAIContentController::class, 'showQuizForm'])->name('teacher.ai.quiz.form');
        Route::post('/quiz',    [\App\Http\Controllers\Teacher\TeacherAIContentController::class, 'generateQuiz'])->name('teacher.ai.quiz.generate');
        Route::get('/scenario', [\App\Http\Controllers\Teacher\TeacherAIContentController::class, 'showScenarioForm'])->name('teacher.ai.scenario.form');
        Route::post('/scenario',[\App\Http\Controllers\Teacher\TeacherAIContentController::class, 'generateScenario'])->name('teacher.ai.scenario.generate');
    });


    // You may already have these:
    Route::post('/teacher/modules', [TeacherDashboardController::class, 'storeModule'])->name('teacher.modules.store');
    Route::put('/teacher/modules/{module}', [TeacherDashboardController::class, 'updateModule'])->name('teacher.modules.update');
    Route::delete('/teacher/modules/{module}', [TeacherDashboardController::class, 'destroyModule'])->name('teacher.modules.destroy');
    Route::put('/teacher/modules/{module}/publish', [TeacherDashboardController::class, 'publishModule'])->name('teacher.modules.publish');
    Route::post('/modules', [TeacherDashboardController::class, 'storeModule'])->name('teacher.modules.store');
    Route::put('/modules/{module}', [TeacherDashboardController::class, 'updateModule'])->name('teacher.modules.update');
    Route::delete('/modules/{module}', [TeacherDashboardController::class, 'destroyModule'])->name('teacher.modules.destroy');

    Route::prefix('teacher')->middleware(['auth','verified','role:teacher'])->group(function () {
        Route::post('/modules/{module}/lessons', [TeacherDashboardController::class, 'assignLessons'])->name('teacher.modules.assignLessons');
        // ...other teacher module routes...
    });
});


require __DIR__ . '/auth.php';
