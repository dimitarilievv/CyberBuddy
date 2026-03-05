<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $parent = $request->user();
        $children = $parent->children()->with([
            'enrollments.module',
            'profile',
        ])->get();

        $childrenProgress = $children->map(function ($child) {
            return [
                'child' => $child,
                'enrollments_count' => $child->enrollments->count(),
                'completed_count' => $child->enrollments->where('status', 'completed')->count(),
                'avg_progress' => $child->enrollments->avg('progress_percentage') ?? 0,
            ];
        });

        return view('parent.dashboard', compact('childrenProgress'));
    }
}

