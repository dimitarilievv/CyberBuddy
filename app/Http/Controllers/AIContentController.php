<?php

namespace App\Http\Controllers;

use App\Services\AiContentCreatorService;
use Illuminate\Http\Request;

class AIContentController extends Controller
{
    public function __construct(
        private AiContentCreatorService $service
    ) {}

    public function generateLesson(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'topic' => 'required|string'
        ]);

        $lesson = $this->service->generateLesson(
            $request->module_id,
            $request->topic
        );

        return redirect()->back()->with('success', 'AI Lesson generated!');
    }

    public function generateQuiz(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'topic' => 'required|string'
        ]);

        $this->service->generateQuiz(
            $request->lesson_id,
            $request->topic
        );

        return back()->with('success', 'AI Quiz generated!');
    }

    public function generateScenario(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'topic' => 'required|string'
        ]);

        $this->service->generateScenario(
            $request->lesson_id,
            $request->topic
        );

        return back()->with('success', 'AI Scenario generated!');
    }
}
