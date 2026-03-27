@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
    @livewire('leaderboard', ['period' => $period ?? 'all_time'])
@endsection
