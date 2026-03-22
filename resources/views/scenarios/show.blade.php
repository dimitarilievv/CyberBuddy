@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $scenario->title }}</h1>
        <p>{{ $scenario->description }}</p>

        <form action="{{ route('scenarios.submit', $scenario->id) }}" method="POST">
            @csrf
            @foreach ($scenario->choices as $choice)
                <div>
                    <label>
                        <input type="radio" name="choice_id" value="{{ $choice->id }}">
                        {{ $choice->choice_text }}
                    </label>
                </div>
            @endforeach

            <button type="submit">Submit Choice</button>
        </form>
    </div>
@endsection
