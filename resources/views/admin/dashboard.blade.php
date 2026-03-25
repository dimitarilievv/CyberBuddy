@extends('layouts.app')

@section('content')
<h1>Admin Dashboard</h1>
<div style="max-width:640px; margin:2rem auto;">
    <div style="border:1px solid #e2e8f0; border-radius:10px; padding:1.25rem; background:#fff;">
        <h3 style="margin:0 0 .75rem; font-size:1.1rem;">🤖 AI Content Generator</h3>
        <p style="color:#666; font-size:.875rem; margin:0 0 1rem;">
            Generate lessons, quizzes, and scenarios using AI. All content is saved as unpublished so you can review before going live.
        </p>
        <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
            <a href="{{ route('admin.ai.lesson.form') }}"
               style="padding:.55rem 1rem; background:#6366f1; color:#fff; border-radius:6px; text-decoration:none; font-size:.875rem; font-weight:600;">
                ✨ Generate Lesson
            </a>
            <a href="{{ route('admin.ai.quiz.form') }}"
               style="padding:.55rem 1rem; background:#0891b2; color:#fff; border-radius:6px; text-decoration:none; font-size:.875rem; font-weight:600;">
                ✨ Generate Quiz
            </a>
            <a href="{{ route('admin.ai.scenario.form') }}"
               style="padding:.55rem 1rem; background:#d97706; color:#fff; border-radius:6px; text-decoration:none; font-size:.875rem; font-weight:600;">
                ✨ Generate Scenario
            </a>
        </div>
    </div>
</div>
@endsection
