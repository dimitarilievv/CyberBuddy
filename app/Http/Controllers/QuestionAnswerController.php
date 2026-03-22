<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionAnswerService;

class QuestionAnswerController extends Controller
{
    private QuestionAnswerService $service;

    public function __construct(QuestionAnswerService $service)
    {
        $this->service = $service;
    }

    public function index(int $attemptId)
    {
        $result = $this->service->evaluateAttempt($attemptId);
        return view('quiz.attempt', compact('result'));
    }

    public function submit(Request $request, int $attemptId)
    {
        $request->validate([
            'question_id' => 'required|integer|exists:questions,id',
            'given_answer' => 'required'
        ]);

        $this->service->submitAnswer($attemptId, $request->question_id, $request->given_answer);

        return response()->json(['message' => 'Answer submitted successfully']);
    }

    public function evaluate(int $attemptId)
    {
        $result = $this->service->evaluateAttempt($attemptId);
        return response()->json($result);
    }
}
