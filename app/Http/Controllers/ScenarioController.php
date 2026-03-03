<?php

namespace App\Http\Controllers;

use App\Services\ScenarioService;
use App\Services\BadgeService;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    private ScenarioService $scenarioService;
    private BadgeService $badgeService;

    public function __construct(
        ScenarioService $scenarioService,
        BadgeService $badgeService,
    ) {
        $this->badgeService = $badgeService;
        $this->scenarioService = $scenarioService;
    }

    public function show(int $scenarioId)
    {
        $scenario = $this->scenarioService->getScenarioWithChoices($scenarioId);

        return view('scenarios.show', compact('scenario'));
    }

    public function submit(Request $request, int $scenarioId)
    {
        $request->validate(['choice_id' => 'required|integer|exists:scenario_choices,id']);

        $attempt = $this->scenarioService->submitChoice(
            $scenarioId,
            auth()->id(),
            $request->input('choice_id')
        );

        $newBadges = $this->badgeService->checkAndAward(auth()->user());

        return view('scenarios.result', compact('attempt', 'newBadges'));
    }
}
