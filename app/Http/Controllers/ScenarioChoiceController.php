<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScenarioChoiceService;

class ScenarioChoiceController extends Controller
{
    private ScenarioChoiceService $choiceService;

    public function __construct(ScenarioChoiceService $choiceService)
    {
        $this->choiceService = $choiceService;
    }

    public function index(int $scenarioId)
    {
        $choices = $this->choiceService->getScenarioChoices($scenarioId);

        return view('scenarios.choices', compact('choices'));
    }

    public function evaluate(int $choiceId)
    {
        $result = $this->choiceService->evaluateChoice($choiceId);

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'scenario_id' => 'required|exists:scenarios,id',
            'choice_text' => 'required|string',
            'consequence' => 'required|string',
            'safety_score' => 'required|integer|min:0|max:100',
            'ai_explanation' => 'required|string',
        ]);

        $this->choiceService->createChoice($request->all());

        return back()->with('success', 'Choice created successfully!');
    }
}
