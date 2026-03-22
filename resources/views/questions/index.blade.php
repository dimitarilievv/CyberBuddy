@extends('layouts.app')

@section('content')
    <h1>Questions for Quiz Blade View</h1>

    @foreach($questions as $question)
        <div>
            <strong>{{ $question->question_text }}</strong>
        </div>
    @endforeach
@endsection
