@extends('layouts.app')

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Scenario Choices</h1>

        @if($choices->isEmpty())
            <p>No choices available.</p>
        @else
            <ul>
                @foreach($choices as $choice)
                    <li>
                        <strong>{{ $choice->choice_text }}</strong>
                        (Score: {{ $choice->safety_score }})
                    </li>
                @endforeach
            </ul>
        @endif

        <h3>Add New Choice</h3>
        <form action="{{ route('scenarios.choices.store', $choices->first()->scenario_id ?? 1) }}" method="POST">
            @csrf
            <input type="text" name="choice_text" placeholder="Choice text" required><br>
            <textarea name="consequence" placeholder="Consequence" required></textarea><br>
            <input type="number" name="safety_score" placeholder="Safety Score" min="0" max="100" required><br>
            <textarea name="ai_explanation" placeholder="AI Explanation" required></textarea><br>
            <button type="submit">Create Choice</button>
        </form>
    </div>
@endsection
