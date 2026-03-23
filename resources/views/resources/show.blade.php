{{-- resources/views/resources/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>{{ $resource->title }}</h1>
    <p>{{ $resource->description }}</p>
    @if($resource->type === 'link')
        <a href="{{ $resource->url }}" target="_blank">Open Resource</a>
    @endif
@endsection
