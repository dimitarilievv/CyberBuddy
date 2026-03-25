@extends('layouts.app')

@section('content')
    @include('admin.ai._style')

    <div class="ai-page">
        <h2>🤖 Generate AI Lesson</h2>
        <p class="subtitle">The AI writes a full lesson and saves it as <strong>unpublished</strong> for your review.</p>

        @include('admin.ai._alert')

        <div class="ai-card">
            <form method="POST" action="{{ route('admin.ai.lesson.generate') }}"
                  onsubmit="this.querySelector('.btn-generate').disabled=true;
                        this.querySelector('.btn-generate').textContent='Generating…'">
                @csrf

                <div class="field">
                    <label for="module_id">Module</label>
                    <select name="module_id" id="module_id" required>
                        <option value="">— select module —</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                {{ $module->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="topic">Topic</label>
                    <input type="text" name="topic" id="topic"
                           placeholder="e.g. Password Safety, Phishing, Two-Factor Authentication"
                           value="{{ old('topic') }}" required>
                    <span class="hint">Be specific — the more detail you give, the better the lesson.</span>
                </div>

                <button type="submit" class="btn-generate">✨ Generate Lesson</button>
            </form>
        </div>

        <div class="nav-links">
            <a href="{{ route('admin.ai.quiz.form') }}">→ Generate Quiz</a>
            <a href="{{ route('admin.ai.scenario.form') }}">→ Generate Scenario</a>
        </div>
    </div>
@endsection
