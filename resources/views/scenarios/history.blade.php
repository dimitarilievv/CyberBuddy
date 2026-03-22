@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Scenario Attempts History</h1>

        @if($attempts->isEmpty())
            <p>No attempts yet.</p>
        @else
            <table border="1" cellpadding="5">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Choice</th>
                    <th>Score</th>
                    <th>AI Feedback</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($attempts as $attempt)
                    <tr>
                        <td>{{ $attempt->id }}</td>
                        <td>{{ $attempt->choice->choice_text ?? '-' }}</td>
                        <td>{{ $attempt->safety_score }}</td>
                        <td>{{ $attempt->ai_feedback }}</td>
                        <td>{{ $attempt->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
