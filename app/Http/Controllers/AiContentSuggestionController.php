<?php

namespace App\Http\Controllers;

use App\Services\AiContentSuggestionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiContentSuggestionController extends Controller
{
    public function __construct(
        private AiContentSuggestionService $service
    ) {}

    // Teacher review list (pending by default)
    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 15);
        $status = $request->query('status', 'pending');

        // If you want to lock it to teachers only, add route middleware role:teacher
        $suggestions = $this->service->listForTeacher($status, $perPage);

        return view('ai_suggestions.index', [
            'suggestions' => $suggestions,
            'status' => $status,
        ]);
    }

    // Basic create page (optional; useful for testing)
    public function create(): View
    {
        return view('ai_suggestions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'content_type' => ['required', 'in:question,scenario,tip,resource'],
            'title' => ['required', 'string', 'max:255'],
            'suggested_content' => ['required', 'string'],
        ]);

        $this->service->createSuggestion((int) auth()->id(), $data);

        return redirect()
            ->route('ai_suggestions.index')
            ->with('success', 'Suggestion submitted (pending review).');
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'admin_notes' => ['nullable', 'string'],
        ]);

        $ok = $this->service->approve($id, (int) auth()->id(), $data['admin_notes'] ?? null);

        return back()->with($ok ? 'success' : 'error', $ok ? 'Approved.' : 'Not found.');
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'admin_notes' => ['nullable', 'string'],
        ]);

        $ok = $this->service->reject($id, (int) auth()->id(), $data['admin_notes'] ?? null);

        return back()->with($ok ? 'success' : 'error', $ok ? 'Rejected.' : 'Not found.');
    }
}
