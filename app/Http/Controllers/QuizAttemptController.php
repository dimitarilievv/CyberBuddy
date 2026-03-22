<?php

namespace App\Http\Controllers;

use App\Services\QuizAttemptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    private QuizAttemptService $attemptService;

    public function __construct(QuizAttemptService $attemptService)
    {
        $this->attemptService = $attemptService;
    }

    public function index()
    {
        $attempts = $this->attemptService->getAttemptsForUser(Auth::id(), []);

        return view('quiz_attempts.index', compact('attempts'));
    }

    public function show(int $id)
    {
        $attempt = $this->attemptService->getAttemptById($id);
        $this->authorizeAttemptAccess($attempt);

        $attempt->load(['quiz', 'answers.question', 'answers.selectedOption']);

        return view('quiz_attempts.show', compact('attempt'));
    }

    public function start(int $quizId)
    {
        $attempt = $this->attemptService->startAttempt($quizId);

        return redirect()->route('quiz_attempts.show', $attempt->id)
            ->with('success', 'Quiz attempt started! Good luck! 🚀');
    }

    public function submit(Request $request, int $id)
    {
        $request->validate([
            'answers' => ['required', 'array', 'min:1'],
            'answers.*' => ['required', 'integer', 'exists:question_options,id'],
        ]);

        $attempt = $this->attemptService->submitAttempt($id, $request->answers);
        $passed = $attempt->status === 'passed';

        return view('quiz_attempts.result', compact('attempt', 'passed'));
    }

    public function myAttempts(int $quizId)
    {
        $attempts = $this->attemptService->getUserAttemptsForQuiz(Auth::id(), $quizId);

        return view('quiz_attempts.my_attempts', compact('attempts', 'quizId'));
    }

    private function authorizeAttemptAccess($attempt): void
    {
        $user = Auth::user();
        if ($attempt->user_id !== Auth::id() && !($user && $user->is_admin)) {
            abort(403, 'You are not allowed to view this attempt.');
        }
    }
}
