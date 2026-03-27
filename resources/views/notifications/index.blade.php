@extends('layouts.app')

@section('title', 'My Notifications')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">My Notifications</h1>

        {{-- Use the Livewire notifications component (handles listing, pagination, actions) --}}
        <livewire:notification.index />
    </div>
@endsection
