<?php
namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuestionAnswer;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizAttemptSeeder extends Seeder
{
    /**
     * Child-friendly AI feedback examples, cybersecurity themed.
     */
    private array $feedbackExamples = [
        'passed' => [
            "Brilliant work, cyber hero! You really know your stuff about password safety. Remember: a strong password is like a secret superpower - never share it with anyone, even your best friend! Keep it up and you'll be unstoppable!",
            "Wow, you nailed it! You clearly understand how to spot phishing emails. A top tip: always check the sender's address before clicking any links - sneaky hackers love to pretend to be someone you trust! You're a true CyberBuddy champion!",
            "Amazing score! You know exactly how to stay safe online. Did you know that turning on Two-Factor Authentication (2FA) is like putting a double lock on your account? Keep exploring and you'll be a cybersecurity superstar!",
            "Perfect! You're becoming a digital safety expert! Remember to share what you learned with your friends and family - together we can make the internet safer for everyone!",
            "Outstanding! You've mastered this topic. Keep practicing and soon you'll be ready for more advanced cybersecurity challenges!",
        ],
        'failed' => [
            "Great effort, keep going! Passwords can be tricky, but the secret is to make them long and mix letters, numbers, and symbols. Try again - you've got this, cyber explorer!",
            "Nice try! Phishing emails can fool even adults sometimes. The trick is to look for spelling mistakes and strange-looking links. Study those tips and give it another shot - we believe in you!",
            "Don't give up! Online safety is a superpower worth learning. Next time, remember: if something online feels weird or uncomfortable, always tell a trusted adult. You're getting better every day!",
            "Good attempt! Every cybersecurity expert started as a beginner. Review the lesson and try again - you'll do even better next time!",
            "Almost there! You're learning important skills that will help you stay safe online. Take a deep breath and give it another try!",
        ],
    ];

    public function run(): void
    {
        $users = User::whereIn('role', ['child', 'parent'])->get();
        $quizzes = Quiz::with('questions')->get();

        if ($users->isEmpty() || $quizzes->isEmpty()) {
            $this->command->warn('QuizAttemptSeeder: No users or quizzes found. Run UserSeeder and QuizSeeder first.');
            return;
        }

        $totalAttempts = 0;

        // Create attempts for all users (not just first 5)
        foreach ($users as $user) {
            // Each user attempts 2-4 random quizzes
            $randomQuizzes = $quizzes->random(min(rand(2, 4), $quizzes->count()));

            foreach ($randomQuizzes as $quiz) {
                $attemptsCreated = $this->seedAttemptsForUserOnQuiz($user, $quiz);
                $totalAttempts += $attemptsCreated;
            }
        }

        $this->command->info("QuizAttemptSeeder complete. Created {$totalAttempts} attempts.");
    }

    private function seedAttemptsForUserOnQuiz(User $user, Quiz $quiz): int
    {
        $maxAttempts = $quiz->max_attempts ?? 3;
        $numAttempts = rand(1, min(2, $maxAttempts));
        $attemptsCreated = 0;

        for ($i = 0; $i < $numAttempts; $i++) {
            $startedAt = now()->subDays(rand(1, 30))->subMinutes(rand(5, 45));
            $timeSpent = rand(120, min(($quiz->time_limit_minutes ?? 20) * 60 - 30, 600));
            $submittedAt = $startedAt->copy()->addSeconds($timeSpent);

            // 10% chance of having an in-progress attempt (only for the last attempt)
            $isInProgress = ($i === $numAttempts - 1) && rand(1, 10) === 1;

            $score = $isInProgress ? 0 : $this->randomScore();
            $passed = !$isInProgress && $score >= $quiz->passing_score;
            $status = $isInProgress ? 'in_progress' : ($passed ? 'completed' : 'completed');

            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => $user->id,
                'score' => $score,
                'time_spent_seconds' => $isInProgress ? null : $timeSpent,
                'status' => $status,
                'ai_feedback' => null,
                'started_at' => $startedAt,
                'completed_at' => $isInProgress ? null : $submittedAt,
                'created_at' => $startedAt,
                'updated_at' => $isInProgress ? $startedAt : $submittedAt,
            ]);

            $attemptsCreated++;

            if (!$isInProgress) {
                // Add AI feedback for completed attempts
                $feedbackKey = $passed ? 'passed' : 'failed';
                $feedback = $this->feedbackExamples[$feedbackKey][array_rand($this->feedbackExamples[$feedbackKey])];

                $attempt->update([
                    'ai_feedback' => $feedback,
                ]);

                $this->seedQuestionAnswers($attempt, $quiz);
            }
        }

        return $attemptsCreated;
    }

    private function seedQuestionAnswers(QuizAttempt $attempt, Quiz $quiz): void
    {
        foreach ($quiz->questions as $question) {
            // Determine answer correctness with weighted probability
            // Better scores = more correct answers
            $scorePercentage = $attempt->score;

            // Probability of correct answer increases with score
            $correctProbability = $scorePercentage / 100;
            $isCorrect = rand(1, 100) <= ($correctProbability * 100);

            // Get the correct answer (first item from the array)
            $correctAnswer = is_array($question->correct_answer)
                ? ($question->correct_answer[0] ?? 'A')
                : $question->correct_answer;

            // Generate a wrong answer from options if available
            $givenAnswer = $correctAnswer;

            if (!$isCorrect && !empty($question->options)) {
                // Get all options keys except the correct one
                $options = $question->options;
                $optionKeys = array_keys($options);
                $wrongAnswers = array_diff($optionKeys, [$correctAnswer]);

                if (!empty($wrongAnswers)) {
                    $givenAnswer = $wrongAnswers[array_rand($wrongAnswers)];
                } else {
                    $givenAnswer = $correctAnswer; // Fallback
                }
            }

            QuestionAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'given_answer' => $givenAnswer,
                'is_correct' => $isCorrect,
                'points_earned' => $isCorrect ? $question->points : 0,
            ]);
        }
    }

    private function randomScore(): int
    {
        // Weighted score distribution (more scores in the middle and higher ranges)
        $ranges = [
            [50, 69, 25],  // 25% chance
            [70, 84, 40],  // 40% chance
            [85, 100, 35], // 35% chance
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($ranges as [$min, $max, $weight]) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return rand($min, $max);
            }
        }

        return 75;
    }
}
