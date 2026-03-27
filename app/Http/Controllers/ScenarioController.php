<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Services\BadgeService;
use App\Services\ScenarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ScenarioController extends Controller
{
    public function __construct(
        private BadgeService $badgeService,
        private ScenarioService $scenarioService
    ) {}

    // 🔴 Листа на сценарија
    public function index(): View
    {
        $query = Scenario::with('module', 'choices')->where('is_published', true);

        if (Schema::hasColumn('scenarios', 'sort_order')) {
            $query = $query->orderBy('sort_order');
        } else {
            $query = $query->orderBy('id');
        }

        $scenarios = $query->paginate(12);

        return view('scenarios.index', compact('scenarios'));
    }

    // Листа на сценарија за лекција
    public function lessonIndex(int $lessonId): View
    {
        $scenarios = $this->scenarioService->getByLesson($lessonId);

        return view('scenarios.lesson_index', compact('scenarios', 'lessonId'));
    }

    // 🔴 Детали на сценариј
    public function show($scenario): View
    {
        $scenarioId = $scenario instanceof Scenario ? $scenario->id : (int) $scenario;

        $scenario = $this->scenarioService->getScenarioWithChoices($scenarioId);

        return view('scenarios.show', compact('scenario'));
    }

    // 🔴 Submit (ако го користиш)
    public function submit(Request $request, $scenario)
    {
        $scenarioId = $scenario instanceof Scenario ? $scenario->id : (int) $scenario;

        $request->validate(['choice_id' => 'required|integer|exists:scenario_choices,id']);

        $attempt = $this->scenarioService->submitChoice(
            $scenarioId,
            auth()->id(),
            $request->input('choice_id')
        );

        $newBadges = $this->badgeService->checkAndAward(auth()->user());

        return redirect()->route('scenario.result', $attempt->id);
    }
}
