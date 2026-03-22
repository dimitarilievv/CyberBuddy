@extends('layouts.app')

@section('content')
    <h1>Quiz Result</h1>

    <p>Your score: {{ $attempt->score }}%</p>

    @if(!empty($newBadges))
        <h3>New Badges Earned!</h3>
        <ul>
            @foreach($newBadges as $badge)
                <li>{{ $badge->name }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('quizzes.show', $attempt->quiz_id) }}">Back to Quiz</a>
@endsection
