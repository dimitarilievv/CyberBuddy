<?php

namespace App\Providers;

use App\Repositories\AiContentSuggestionRepository;
use App\Repositories\AiInteractionRepository;
use App\Repositories\Interfaces\AiContentSuggestionRepositoryInterface;
use App\Repositories\Interfaces\AiInteractionRepositoryInterface;
use App\Repositories\Interfaces\LeaderboardRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Repositories\Interfaces\UserBadgeRepositoryInterface;
use App\Repositories\Interfaces\UserProgressRepositoryInterface;
use App\Repositories\LeaderboardRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Repositories\UserBadgeRepository;
use App\Repositories\UserProgressRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\ModuleRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Repositories\Interfaces\ScenarioRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\QuestionAnswerRepositoryInterface;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Repositories\Interfaces\BadgeRepositoryInterface;

use App\Repositories\ModuleRepository;
use App\Repositories\QuizRepository;
use App\Repositories\ScenarioRepository;
use App\Repositories\EnrollmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\QuestionAnswerRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\BadgeRepository;
use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use App\Repositories\ActivityLogRepository;
use App\Repositories\Interfaces\ReportedContentRepositoryInterface;
use App\Repositories\ReportedContentRepository;
use App\Repositories\QuizAttemptRepository;
use App\Repositories\Interfaces\QuizAttemptRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->bind(QuizRepositoryInterface::class, QuizRepository::class);
        $this->app->bind(QuizAttemptRepositoryInterface::class, QuizAttemptRepository::class);
        $this->app->bind(ScenarioRepositoryInterface::class, ScenarioRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(QuestionAnswerRepositoryInterface::class, QuestionAnswerRepository::class);
        $this->app->bind(CertificateRepositoryInterface::class, CertificateRepository::class);
        $this->app->bind(BadgeRepositoryInterface::class, BadgeRepository::class);
        $this->app->bind(ResourceRepositoryInterface::class, ResourceRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(UserProgressRepositoryInterface::class, UserProgressRepository::class);
        $this->app->bind(UserBadgeRepositoryInterface::class, UserBadgeRepository::class);
        $this->app->bind(AiContentSuggestionRepositoryInterface::class, AiContentSuggestionRepository::class);
        $this->app->bind(LeaderboardRepositoryInterface::class, LeaderboardRepository::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
        $this->app->bind(AiInteractionRepositoryInterface::class, AiInteractionRepository::class);
        $this->app->bind(ReportedContentRepositoryInterface::class, ReportedContentRepository::class);
    }
}
