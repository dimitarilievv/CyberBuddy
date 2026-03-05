<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();

        $myModules = Module::where('author_id', $teacher->id)
            ->withCount('enrollments')
            ->latest()
            ->get();

        $stats = [
            'total_modules' => $myModules->count(),
            'published_modules' => $myModules->where('is_published', true)->count(),
            'total_students' => $myModules->sum('enrollments_count'),
        ];

        return view('teacher.dashboard', compact('myModules', 'stats'));
    }
}
