@extends('layouts.app')

@section('content')
    @include('admin.ai._style')

    <div class="ai-page">
        <h2>🤖 Generate AI Quiz</h2>
        <p class="subtitle">Generates 5 multiple-choice questions and saves them as <strong>unpublished</strong>.</p>

        @include('admin.ai._alert')

        <div class="ai-card">
            <form method="POST" action="{{ route('admin.ai.quiz.generate') }}"
                  onsubmit="this.querySelector('.btn-generate').disabled=true;
                        this.querySelector('.btn-generate').textContent='Generating…'">
                @csrf

                <div class="field">
                    <label for="lesson_id">Lesson</label>
                    <select name="lesson_id" id="lesson_id" required>
                        <option value="">— select lesson —</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                {{ $lesson->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="topic">Topic</label>
                    <input type="text" name="topic" id="topic"
                           placeholder="e.g. Phishing, Safe Passwords, Online Privacy"
                           value="{{ old('topic') }}" required>
                </div>

                <button type="submit" class="btn-generate">✨ Generate Quiz</button>
            </form>
        </div>

        <div class="nav-links">
            <a href="{{ route('admin.ai.lesson.form') }}">→ Generate Lesson</a>
            <a href="{{ route('admin.ai.scenario.form') }}">→ Generate Scenario</a>
        </div>
    </div>
@endsection
