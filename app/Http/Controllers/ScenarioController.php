<?php

namespace App\Http\Controllers;

use App\Services\ScenarioService;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

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

    #[OA\Get(
        path: '/scenarios/{scenario}',
        summary: 'Show a scenario',
        tags: ['Scenarios'],
        parameters: [
            new OA\Parameter(
                name: 'scenario',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
        ]
    )]
    public function show(int $scenarioId)
    {
        $scenario = $this->scenarioService->getScenarioWithChoices($scenarioId);

        return view('scenarios.show', compact('scenario'));
    }

    #[OA\Post(
        path: '/scenarios/{scenario}/submit',
        summary: 'Submit a scenario choice',
        tags: ['Scenarios'],
        parameters: [
            new OA\Parameter(
                name: 'scenario',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
        ]
        // you can skip requestBody if you don't care about documenting it now
    )]
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
