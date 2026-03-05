<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use App\Services\BadgeService;
use Illuminate\Http\Request;


class QuizController extends Controller
{
    private QuizService $quizService;
    private BadgeService $badgeService;

    public function __construct(
        QuizService $quizService,
        BadgeService $badgeService,
    ) {
        $this->badgeService = $badgeService;
        $this->quizService = $quizService;
    }

    public function show(int $quizId)
    {
        $quiz = $this->quizService->getQuizWithQuestions($quizId);
        $canAttempt = $this->quizService->canAttempt($quizId, auth()->id());

        return view('quizzes.show', compact('quiz', 'canAttempt'));
    }

    public function submit(Request $request, int $quizId)
    {
        if (!$this->quizService->canAttempt($quizId, auth()->id())) {
            return back()->with('error', 'Го искористи максималниот број обиди!');
        }

        $answers = $request->input('answers', []);
        $attempt = $this->quizService->submitQuiz($quizId, auth()->id(), $answers);

        // Провери за нови беџови
        $newBadges = $this->badgeService->checkAndAward(auth()->user());

        return view('quizzes.result', compact('attempt', 'newBadges'));
    }
}
