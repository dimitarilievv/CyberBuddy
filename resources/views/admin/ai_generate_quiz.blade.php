@extends('layouts.app')
@section('content')
<h2>Generate AI Quiz</h2>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="POST" action="{{ route('admin.ai.quiz.generate') }}">
    @csrf
    <label>Lesson:</label>
    <select name="lesson_id" required>
        @foreach($lessons as $lesson)
            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
        @endforeach
    </select>
    <label>Topic:</label>
    <input type="text" name="topic" required>
    <button type="submit">Generate Quiz</button>
</form>
@endsection

