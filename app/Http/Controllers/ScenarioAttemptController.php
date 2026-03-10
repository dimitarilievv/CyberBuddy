<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScenarioAttemptService;

class ScenarioAttemptController extends Controller
{
    private ScenarioAttemptService $attemptService;

    public function __construct(ScenarioAttemptService $attemptService)
    {
        $this->attemptService = $attemptService;
    }

    public function submit(Request $request, int $scenarioId)
    {
        $request->validate([
            'choice_id' => 'required|exists:scenario_choices,id',
            'time_spent_seconds' => 'nullable|integer',
        ]);

        if (!$this->attemptService->canAttempt($scenarioId, auth()->id())) {
            return back()->with('error', 'Го достигна максималниот број обиди.');
        }

        $attempt = $this->attemptService->submitAttempt(
            $scenarioId,
            auth()->id(),
            $request->choice_id,
            $request->time_spent_seconds ?? 0
        );

        return response()->json([
            'score' => $attempt->safety_score,
            'feedback' => $attempt->ai_feedback,
        ]);
    }

    public function history(int $scenarioId)
    {
        $attempts = $this->attemptService->getUserAttempts($scenarioId, auth()->id());

        return view('scenarios.history', compact('attempts'));
    }
}
