@extends('layouts.app')

@section('content')
    @include('admin.ai._style')

    <div class="ai-page">
        <h2>🤖 Generate AI Scenario</h2>
        <p class="subtitle">Creates a real-life decision scenario with choices and safety scores, saved as <strong>unpublished</strong>.</p>

        @include('admin.ai._alert')

        <div class="ai-card">
            <form method="POST" action="{{ route('admin.ai.scenario.generate') }}"
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
                           placeholder="e.g. Cyberbullying, Suspicious Downloads, Public Wi-Fi"
                           value="{{ old('topic') }}" required>
                </div>

                <button type="submit" class="btn-generate">✨ Generate Scenario</button>
            </form>
        </div>

        <div class="nav-links">
            <a href="{{ route('admin.ai.lesson.form') }}">→ Generate Lesson</a>
            <a href="{{ route('admin.ai.quiz.form') }}">→ Generate Quiz</a>
        </div>
    </div>
@endsection
