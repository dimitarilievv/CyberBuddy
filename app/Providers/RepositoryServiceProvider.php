<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\ModuleRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Repositories\Interfaces\ScenarioRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\QuestionAnswerRepositoryInterface;

use App\Repositories\ModuleRepository;
use App\Repositories\QuizRepository;
use App\Repositories\ScenarioRepository;
use App\Repositories\EnrollmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\QuestionAnswerRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->bind(QuizRepositoryInterface::class, QuizRepository::class);
        $this->app->bind(ScenarioRepositoryInterface::class, ScenarioRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(QuestionAnswerRepositoryInterface::class, QuestionAnswerRepository::class);
    }
}
