<?php

namespace App\Http\Controllers;

use App\Services\AiInteractionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiInteractionController extends Controller
{
    public function __construct(private AiInteractionService $service) {}

    // Show recent interactions for logged-in user
    public function index()
    {
        $userId = Auth::id();
        $interactions = $this->service->recentForUser($userId);

        return view('ai_interactions.index', compact('interactions'));
    }

    // Store a new interaction
    public function store(Request $request)
    {
        $data = $request->validate([
            'type'            => 'required|string|in:question_generation,error_explanation,scenario_feedback,progress_insight,content_suggestion',
            'prompt'          => 'required|string',
            'response'        => 'required|string',
            'model_used'      => 'nullable|string',
            'tokens_used'     => 'nullable|integer',
            'response_time_ms'=> 'nullable|integer',
            'was_helpful'     => 'nullable|boolean',
            'metadata'        => 'nullable|array',
        ]);
        $data['user_id'] = Auth::id();
        if(isset($data['metadata'])) $data['metadata'] = json_encode($data['metadata']);

        $this->service->create($data);

        return redirect()->route('ai_interactions.index')->with('success', 'Interaction saved.');
    }
}
