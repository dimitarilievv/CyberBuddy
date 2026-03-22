@extends('layouts.app')

@section('content')
    <h1>{{ $quiz->title }}</h1>

    @if($canAttempt)
        <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
            @csrf
            @foreach($quiz->questions as $question)
                <div>
                    <p>{{ $question->question_text }}</p>

                </div>
            @endforeach

            <button type="submit">Submit Quiz</button>
        </form>
    @else
        <p>You cannot attempt this quiz anymore.</p>
    @endif
@endsection
