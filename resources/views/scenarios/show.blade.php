@extends('layouts.app')

@section('title', $scenario->title)

@section('content')
    @livewire('scenario.attempt', ['scenario' => $scenario])
@endsection
