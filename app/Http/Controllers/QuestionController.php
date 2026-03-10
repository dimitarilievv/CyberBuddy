<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionService;

class QuestionController extends Controller
{
    private QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index(int $quizId)
    {
        $questions = $this->questionService->getQuizQuestions($quizId);

        return view('questions.index', compact('questions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
            'type' => 'required|string',
            'options' => 'nullable|array',
            'correct_answer' => 'required|array',
            'points' => 'required|integer|min:1',
        ]);

        $this->questionService->createQuestion($request->all());

        return back()->with('success', 'Question created successfully!');
    }

    public function checkAnswer(Request $request, int $questionId)
    {
        $request->validate([
            'answer' => 'required',
        ]);

        $isCorrect = $this->questionService->checkAnswer(
            $questionId,
            $request->answer
        );

        return response()->json([
            'correct' => $isCorrect
        ]);
    }
}
