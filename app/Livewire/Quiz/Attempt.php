<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use App\Models\Module;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Livewire\Component;
use Livewire\Attributes\On;

class Attempt extends Component
{
    public Quiz $quiz;
    public ?Module $module = null;
    public $quizId;
    public $moduleId;
    public $currentQuestionIndex = 0;
    public $selectedAnswers = [];
    public $showHint = false;
    public bool $showReview = false;

    public ?int $attemptId = null;
    public $startedAt;

    #[On('quiz-timer-ended')]
    public function submitQuiz()
    {
        $this->submit();
    }

    public function mount($quizId = null, $moduleId = null)
    {
        if ($quizId) {
            $this->quizId = $quizId;
            $this->quiz = Quiz::with(['questions' => function ($q) {
                $q->orderBy('sort_order');
            }])->findOrFail($quizId);
        }

        if ($moduleId) {
            $this->moduleId = $moduleId;
            $this->module = Module::findOrFail($moduleId);
        }

        $this->selectedAnswers = [];
        $this->showHint = false;
        $this->showReview = false;
        $this->startedAt = now();
    }

    public function nextQuestion()
    {
        $questions = $this->quiz->questions;
        if ($this->currentQuestionIndex < count($questions) - 1) {
            $this->currentQuestionIndex++;
            $this->showHint = false;
            $this->dispatch('question-changed', $this->currentQuestionIndex + 1);
        }
    }

    public function goNextOrReview()
    {
        $questions  = $this->quiz->questions;
        $lastIndex  = count($questions) - 1;

        if ($this->currentQuestionIndex < $lastIndex) {
            $this->nextQuestion();
            return;
        }

        // On last question -> show review screen
        $this->showReview = true;
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->showHint = false;
            $this->dispatch('question-changed', $this->currentQuestionIndex + 1);
        }
    }

    public function toggleHint()
    {
        $this->showHint = ! $this->showHint;
    }

    public function canProceed()
    {
        $currentQuestion = $this->quiz->questions[$this->currentQuestionIndex] ?? null;
        if (! $currentQuestion) {
            return false;
        }

        $answer = $this->selectedAnswers[$currentQuestion->id] ?? null;

        if ($currentQuestion->type === 'multiple_choice') {
            if (! is_array($answer)) {
                return false;
            }

            return collect($answer)->filter(fn ($v) => (bool) $v)->isNotEmpty();
        }

        return $answer !== null && $answer !== '';
    }

    public function submit()
    {
        $questions = $this->quiz->questions;

        // Ensure we at least warn if some questions are unanswered, but do not block submission
        $unanswered = 0;
        foreach ($questions as $q) {
            $answer = $this->selectedAnswers[$q->id] ?? null;

            if ($q->type === 'multiple_choice') {
                if (! is_array($answer) || ! collect($answer)->filter(fn ($v) => (bool) $v)->isNotEmpty()) {
                    $unanswered++;
                }
            } else {
                if ($answer === null || $answer === '') {
                    $unanswered++;
                }
            }
        }

        if ($unanswered > 0) {
            session()->flash('error', "You have {$unanswered} unanswered question(s). They will count as 0 points.");
        }

        // Proceed with scoring and saving attempt regardless of unanswered questions
        $calculation = $this->calculateScore();
        $rawPoints   = $calculation['score'] ?? 0;
        $perQuestion = $calculation['details'] ?? [];

        $totalPossiblePoints = $this->quiz->questions()->sum('points') ?: 0;

        if ($totalPossiblePoints > 0) {
            $percentage = round(($rawPoints / $totalPossiblePoints) * 100, 2);
        } else {
            $percentage = 0.0;
        }

        $score       = $percentage;
        $totalPoints = 100.0;

        $passingScore = $this->quiz->passing_score ?? 0;
        $passed       = $percentage >= $passingScore;

        $startedAt   = $this->startedAt ? \Carbon\Carbon::parse($this->startedAt) : now();
        $completedAt = now();

        // Ensure a safe, non-negative integer for time_spent_seconds
        $timeSpentSeconds = max(0, (int) $completedAt->diffInSeconds($startedAt));

        try {
            $attempt = QuizAttempt::create([
                'quiz_id'           => $this->quizId,
                'user_id'           => auth()->id(),
                'score'             => $score,
                'total_points'      => $totalPoints,
                'percentage'        => $percentage,
                'passed'            => $passed,
                'status'            => $passed ? 'passed' : 'failed',
                'started_at'        => $startedAt,
                'submitted_at'      => $completedAt,
                'completed_at'      => $completedAt,
                'time_spent_seconds' => $timeSpentSeconds,
            ]);

            foreach ($perQuestion as $questionId => $info) {
                $selectedOptionId = $info['selected_option_id'] ?? null;
                $rawGiven         = $info['given_answer'] ?? null;
                $isCorrect        = $info['is_correct'] ?? false;
                $pointsEarned     = $info['points_earned'] ?? 0;
                $aiExplanation    = $info['ai_explanation'] ?? null;

                $givenAnswer = $this->normalizeGivenAnswerToLetters($rawGiven);

                QuizAttemptAnswer::create([
                    'quiz_attempt_id'    => $attempt->id,
                    'question_id'        => $questionId,
                    'selected_option_id' => $selectedOptionId,
                    'given_answer'       => $givenAnswer,
                    'is_correct'         => $isCorrect,
                    'points_earned'      => $pointsEarned,
                    'ai_explanation'     => $aiExplanation,
                ]);
            }
        } catch (\Throwable $e) {
            session()->flash('error', 'There was a problem saving your quiz attempt.');
            return;
        }

        return redirect()->route('quiz_attempts.show', $attempt->id);
    }

    /**
     * Calculate raw score and per-question details.
     * score = sum of question.points for correct questions.
     */
    private function calculateScore(): array
    {
        $score   = 0;
        $details = [];

        foreach ($this->selectedAnswers as $questionId => $userAnswer) {
            $question = $this->quiz->questions()->find($questionId);
            if (! $question) {
                continue;
            }

            // correct_answer is already an array via model casts
            $correctAnswers = $question->correct_answer ?? [];

            $optionsArr  = $question->options ?? [];
            $optionKeys  = array_keys($optionsArr);
            $keyToLetter = [];

            foreach ($optionKeys as $index => $optKey) {
                $keyToLetter[(string) $optKey] = chr(65 + $index);
            }

            $toLetter = function ($v) use ($keyToLetter) {
                $s = strtoupper((string) $v);

                if (strlen($s) === 1 && ctype_alpha($s)) {
                    return $s;
                }

                if (array_key_exists((string) $v, $keyToLetter)) {
                    return $keyToLetter[(string) $v];
                }

                if (ctype_digit((string) $v)) {
                    $n = (int) $v;
                    if ($n >= 0 && $n < 26) {
                        return chr(65 + $n);
                    }
                }

                return $s;
            };

            $isCorrect        = false;
            $points           = $question->points ?? 1;
            $selectedOptionId = null;
            $givenAnswerValue = null;

            if ($question->type === 'multiple_choice') {
                if (is_array($userAnswer)) {
                    $userValues = array_values(array_filter(
                        is_int(array_key_first($userAnswer))
                            ? $userAnswer
                            : array_keys(array_filter($userAnswer, fn ($v) => (bool) $v))
                    ));
                } else {
                    $userValues = [$userAnswer];
                }

                $userLetters = array_values(array_unique(array_map($toLetter, $userValues)));

                $correctValues  = is_array($correctAnswers) ? $correctAnswers : [$correctAnswers];
                $correctLetters = array_values(array_unique(array_map($toLetter, $correctValues)));

                sort($userLetters);
                sort($correctLetters);

                if ($userLetters === $correctLetters) {
                    $isCorrect = true;
                    $score += $points;
                }

                $givenAnswerValue = $userLetters;
            } else {
                $rawValue         = is_array($userAnswer) ? ($userAnswer[0] ?? null) : $userAnswer;
                $givenAnswerValue = $rawValue === null ? null : $toLetter($rawValue);

                $correctValues  = is_array($correctAnswers) ? $correctAnswers : [$correctAnswers];
                $correctLetters = array_values(array_unique(array_map($toLetter, $correctValues)));

                if ($givenAnswerValue !== null && in_array($givenAnswerValue, $correctLetters, true)) {
                    $isCorrect = true;
                    $score += $points;
                }
            }

            $aiExplanation = null;

            $details[$questionId] = [
                'selected_option_id' => $selectedOptionId,
                'given_answer'       => $givenAnswerValue,
                'is_correct'         => $isCorrect,
                'points_earned'      => $isCorrect ? $points : 0,
                'ai_explanation'     => $aiExplanation,
            ];
        }

        return [
            'score'   => $score,
            'details' => $details,
        ];
    }

    private function normalizeGivenAnswerToLetters($value)
    {
        $mapOne = function ($v) {
            if (is_string($v) && strlen($v) === 1 && ctype_alpha($v)) {
                return strtoupper($v);
            }

            if (is_int($v) || (is_string($v) && ctype_digit($v))) {
                $idx = (int) $v;
                if ($idx >= 0 && $idx < 26) {
                    return chr(65 + $idx);
                }
            }

            return (string) $v;
        };

        if (is_array($value)) {
            return array_map($mapOne, $value);
        }

        if ($value === null) {
            return null;
        }

        return $mapOne($value);
    }

    public function render()
    {
        $questions       = $this->quiz->questions ?? collect();
        $currentQuestion = $questions[$this->currentQuestionIndex] ?? null;

        $currentAnswer = null;

        if ($currentQuestion && isset($currentQuestion->id)) {
            $raw = $this->selectedAnswers[$currentQuestion->id] ?? null;

            if ($currentQuestion->type === 'multiple_choice') {
                if ($raw === null) {
                    $currentAnswer = [];
                } elseif (is_array($raw)) {
                    $currentAnswer = array_keys(array_filter($raw, fn ($v) => (bool) $v));
                } else {
                    $currentAnswer = [$raw];
                }
            } else {
                $currentAnswer = is_array($raw) ? ($raw[0] ?? null) : $raw;
            }
        }

        $options = [];
        if ($currentQuestion && $currentQuestion->options) {
            $options = $currentQuestion->options ?? [];
        }

        return view('livewire.quiz.attempt', [
            'currentQuestion' => $currentQuestion,
            'currentAnswer'   => $currentAnswer,
            'questionNumber'  => $this->currentQuestionIndex + 1,
            'totalQuestions'  => count($questions),
            'options'         => $options,
            'module'          => $this->module,
        ]);
    }
}
