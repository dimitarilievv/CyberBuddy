@extends('layouts.app')
@section('content')
<h2>Generate AI Scenario</h2>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="POST" action="{{ route('admin.ai.scenario.generate') }}">
    @csrf
    <label>Lesson:</label>
    <select name="lesson_id" required>
        @foreach($lessons as $lesson)
            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
        @endforeach
    </select>
    <label>Topic:</label>
    <input type="text" name="topic" required>
    <button type="submit">Generate Scenario</button>
</form>
@endsection

