<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function __construct(
        private ActivityLogService $service
    ) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 20);

        // Basic rule:
        // - admin sees all
        // - others see only own
        $user = $request->user();

        if ($user && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            $logs = $this->service->listAll($perPage);
        } else {
            $logs = $this->service->listForUser((int) $user->id, $perPage);
        }

        return view('activity_logs.index', [
            'logs' => $logs,
        ]);
    }

    public function show(int $id, Request $request): View
    {
        $log = $this->service->find($id);

        abort_if(! $log, 404);

        // Basic authorization: non-admin can view only own logs
        $user = $request->user();
        if (!($user && method_exists($user, 'hasRole') && $user->hasRole('admin')) && $log->user_id !== (int) $user->id) {
            abort(403);
        }

        return view('activity_logs.show', [
            'log' => $log,
        ]);
    }
}
