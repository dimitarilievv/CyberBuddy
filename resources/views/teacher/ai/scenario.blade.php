@extends('layouts.app')

@section('content')
    {{-- @include('teacher.ai._style') --}}

    <div class="ai-page">
        <h2>🤖 Generate AI Scenario</h2>
        <p class="subtitle">Creates a decision scenario with choices and safety outcomes, saved as <strong>unpublished</strong>.</p>

        {{-- @include('teacher.ai._alert') --}}
        @if(session('success'))
            <div class="bg-green-50 text-green-800 p-2 rounded mb-4 font-semibold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-800 p-2 rounded mb-4 font-semibold">{{ session('error') }}</div>
        @endif

        <div class="ai-card">
            <form method="POST" action="{{ route('teacher.ai.scenario.generate') }}"
                  onsubmit="this.querySelector('.btn-generate').disabled=true;this.querySelector('.btn-generate').textContent='Generating…'">
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
                    <label for="topic">Scenario Topic</label>
                    <input type="text" name="topic" id="topic"
                           placeholder="e.g. Public Wi-Fi Safety, Suspicious Links"
                           value="{{ old('topic') }}" required>
                    <span class="hint">Give a realistic title or imaginary situation as the scenario seed.</span>
                </div>

                <button type="submit" class="btn-generate">✨ Generate Scenario</button>
            </form>
        </div>

        <div class="nav-links">
            <a href="{{ route('teacher.ai.lesson.form') }}">→ Generate Lesson</a>
            <a href="{{ route('teacher.ai.quiz.form') }}">→ Generate Quiz</a>
        </div>
    </div>
@endsection
