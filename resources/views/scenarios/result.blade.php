@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Scenario Result</h1>

        <p>You chose: {{ $attempt->choice->choice_text }}</p>
        <p>Score: {{ $attempt->score }}</p>
        <p>AI Feedback: {{ $attempt->ai_feedback ?? 'No feedback available.' }}</p>

        @if(!empty($newBadges))
            <h3>New Badges Earned:</h3>
            <ul>
                @foreach($newBadges as $badge)
                    <li>{{ $badge->name }}</li>
                @endforeach
            </ul>
        @endif

        <a href="{{ url()->previous() }}">Back</a>
    </div>
@endsection
