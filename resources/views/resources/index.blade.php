{{-- resources/views/resources/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Resources for {{ $lesson->title }}</h1>
    <ul>
        @foreach($resources as $resource)
            <li>{{ $resource->title }}</li>
        @endforeach
    </ul>
@endsection
