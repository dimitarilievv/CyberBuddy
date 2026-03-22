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
        ],
        'failed' => [
            "Great effort, keep going! Passwords can be tricky, but the secret is to make them long and mix letters, numbers, and symbols. Try again - you've got this, cyber explorer!",
            "Nice try! Phishing emails can fool even adults sometimes. The trick is to look for spelling mistakes and strange-looking links. Study those tips and give it another shot - we believe in you!",
            "Don't give up! Online safety is a superpower worth learning. Next time, remember: if something online feels weird or uncomfortable, always tell a trusted adult. You're getting better every day!",
        ],
    ];
    public function run(): void
    {
        $users   = User::all();
        $quizzes = Quiz::with('questions')->get();
        if ($users->isEmpty() || $quizzes->isEmpty()) {
            $this->command->warn('QuizAttemptSeeder: No users or quizzes found. Run UserSeeder and QuizSeeder first.');
            return;
        }
        foreach ($users->take(5) as $user) {
            foreach ($quizzes->take(3) as $quiz) {
                $this->seedAttemptsForUserOnQuiz($user, $quiz);
            }
        }
        $this->command->info('QuizAttemptSeeder complete.');
    }
    private function seedAttemptsForUserOnQuiz(User $user, Quiz $quiz): void
    {
        $maxAttempts = $quiz->max_attempts ?? 3;
        $numAttempts = rand(1, min(2, $maxAttempts));
        for ($i = 0; $i < $numAttempts; $i++) {
            $startedAt   = now()->subDays(rand(1, 30))->subMinutes(rand(5, 45));
            $timeSpent   = rand(120, ($quiz->time_limit_minutes ?? 20) * 60 - 30);
            $submittedAt = $startedAt->copy()->addSeconds($timeSpent);
            $isInProgress = ($i === $numAttempts - 1) && rand(0, 4) === 0;
            $attempt = QuizAttempt::create([
                'quiz_id'            => $quiz->id,
                'user_id'            => $user->id,
                'score'              => $isInProgress ? 0 : (int) $this->randomScore(),
                'time_spent_seconds' => $isInProgress ? null : $timeSpent,
                'status'             => $isInProgress ? 'in_progress' : 'completed',
                'ai_feedback'        => null,
                'started_at'         => $startedAt,
                'completed_at'       => $isInProgress ? null : $submittedAt,
                'created_at'         => $startedAt,
                'updated_at'         => $isInProgress ? $startedAt : $submittedAt,
            ]);
            if (! $isInProgress) {
                $passed   = $attempt->score >= $quiz->passing_score;
                $status   = $passed ? 'passed' : 'failed';
                $feedback = $this->feedbackExamples[$passed ? 'passed' : 'failed'][array_rand($this->feedbackExamples[$passed ? 'passed' : 'failed'])];
                $attempt->update([
                    'status'      => $status,
                    'ai_feedback' => $feedback,
                ]);
                $this->seedQuestionAnswers($attempt, $quiz);
            }
        }
    }
    private function seedQuestionAnswers(QuizAttempt $attempt, Quiz $quiz): void
    {
        foreach ($quiz->questions as $question) {
            // Determine answer correctness (~60-90% accurate)
            $isCorrect  = rand(1, 10) <= 7;
            $answerText = $isCorrect ? $question->correct_answer : 'Wrong answer example';
            QuestionAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id'     => $question->id,
                'given_answer'    => $answerText,
                'is_correct'      => $isCorrect,
                'points_earned'   => $isCorrect ? $question->points : 0,
            ]);
        }
    }
    private function randomScore(): int
    {
        $ranges = [
            [50, 69, 20],
            [70, 84, 45],
            [85, 100, 35],
        ];

        $rand       = rand(1, 100);
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
