@extends('layouts.app')

@section('content')
    <h1>My Attempts for Quiz #{{ $quizId }}</h1>
    <ul>
        @foreach($attempts as $attempt)
            <li>
                <a href="{{ route('quiz_attempts.show', $attempt->id) }}">
                    Attempt #{{ $attempt->id }} - Status: {{ $attempt->status }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
