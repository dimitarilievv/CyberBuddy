@extends('layouts.app')

@section('content')
    <h1>{{ $lesson->title }}</h1>

    <p>{{ $lesson->content }}</p>

    <hr>

    <h3>🤖 AI Generate Content</h3>

    <form method="POST" action="{{ route('ai.quiz.generate') }}">
        @csrf
        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
        <input type="text" name="topic" placeholder="Quiz topic">
        <button type="submit">Generate Quiz</button>
    </form>

    <form method="POST" action="{{ route('ai.scenario.generate') }}">
        @csrf
        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
        <input type="text" name="topic" placeholder="Scenario topic">
        <button type="submit">Generate Scenario</button>
    </form>
@endsection
