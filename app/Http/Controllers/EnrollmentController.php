<?php

namespace App\Http\Controllers;

use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function __construct(private EnrollmentService $service) {}

    // List enrollments for the current user
    public function index()
    {
        $userId = Auth::id();
        $enrollments = $this->service->getUserEnrollments($userId);

        return view('enrollments.index', compact('enrollments'));
    }

    // Enroll current user in a module
    public function enroll(Request $request, $moduleId)
    {
        $userId = Auth::id();

        $enrollment = $this->service->enroll($userId, $moduleId);

        return redirect()->route('modules.show', $moduleId)
            ->with('status', 'Enrolled successfully!');
    }

    // Check if current user is enrolled in a module (AJAX or internal use)
    public function isEnrolled($moduleId)
    {
        $userId = Auth::id();
        $isEnrolled = $this->service->isEnrolled($userId, $moduleId);

        return response()->json(['enrolled' => $isEnrolled]);
    }

    // Show completed modules for user
    public function completed()
    {
        $userId = Auth::id();
        $completed = $this->service->getCompletedByUser($userId);

        return view('enrollments.completed', compact('completed'));
    }

    // Admin: List all enrollments for a module
    public function moduleEnrollments($moduleId)
    {
        $enrollments = $this->service->getModuleEnrollments($moduleId);

        return view('enrollments.module', compact('enrollments', 'moduleId'));
    }
}
