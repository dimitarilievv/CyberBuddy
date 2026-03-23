<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            BadgeSeeder::class,
            UserSeeder::class,
            ModuleSeeder::class,
            LessonSeeder::class,
            ScenarioSeeder::class,
            QuizSeeder::class,
            CertificateSeeder::class,
            ScenarioAttemptSeeder::class,
            QuestionAnswerSeeder::class,
            MediaFileSeeder::class,
            QuestionSeeder::class,
            QuizAttemptSeeder::class,
            ScenarioChoiceSeeder::class,
            EnrollmentSeeder::class,
            ResourceSeeder::class,
            NotificationSeeder::class,
            ResourceSeeder::class,
            UserProgressSeeder::class,
            UserBadgeSeeder::class

        ]);
    }
}
