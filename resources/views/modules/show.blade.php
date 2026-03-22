@extends('layouts.app')

@section('content')
    <h1>{{ $module->title ?? 'Module Details' }}</h1>
    @if($isEnrolled)
        <p>You are enrolled in this module ✅</p>
    @else
        <p>You are not enrolled yet.</p>
    @endif
@endsection
