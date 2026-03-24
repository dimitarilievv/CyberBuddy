<?php

namespace App\Http\Controllers;

use App\Services\ReportedContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportedContentController extends Controller
{
    public function __construct(
        private ReportedContentService $service
    ) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 15);
        $user = $request->user();

        if ($user && method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('teacher'))) {
            $reports = $this->service->listForAdmin($perPage);
        } else {
            $reports = $this->service->listForReporter((int) $user->id, $perPage);
        }

        return view('reported_contents.index', [
            'reports' => $reports,
        ]);
    }

    public function show(int $id, Request $request): View
    {
        $report = $this->service->find($id);
        abort_if(! $report, 404);

        $user = $request->user();
        $isReviewer = $user && method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('teacher'));

        // Non-reviewers can only view their own
        if (! $isReviewer && (int) $report->reporter_id !== (int) $user->id) {
            abort(403);
        }

        return view('reported_contents.show', [
            'report' => $report,
            'isReviewer' => $isReviewer,
        ]);
    }

    public function create(): View
    {
        return view('reported_contents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'reportable_type' => ['required', 'string', 'max:255'],
            'reportable_id' => ['required', 'integer'],
            'reason' => ['required', 'in:inappropriate,incorrect,offensive,spam,other'],
            'description' => ['nullable', 'string'],
        ]);

        $this->service->createReport((int) $request->user()->id, $data);

        return redirect()
            ->route('reported_contents.index')
            ->with('success', 'Report submitted.');
    }

    public function review(int $id, Request $request): RedirectResponse
    {
        $user = $request->user();
        $isReviewer = $user && method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('teacher'));
        abort_unless($isReviewer, 403);

        $data = $request->validate([
            'status' => ['required', 'in:reviewed,dismissed'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $ok = $this->service->review($id, (int) $user->id, $data['status'], $data['admin_notes'] ?? null);

        return back()->with($ok ? 'success' : 'error', $ok ? 'Updated.' : 'Not found.');
    }
}
