@extends('layouts.app')

@section('title', 'Scenarios for Lesson')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Scenarios for this lesson</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($scenarios as $scenario)
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="font-semibold">{{ $scenario->title }}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($scenario->situation ?? $scenario->description, 120) }}</p>
                    <div class="mt-4 flex gap-2">
                        {{-- Link to the Livewire attempt page (route name: scenario.attempt) --}}
                        <a href="{{ route('scenario.attempt', $scenario->id) }}" class="text-sm px-3 py-2 bg-cyan-500 text-white rounded">Attempt</a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8">
                    <p class="text-sm text-gray-500">No scenarios for this lesson yet.</p>
                </div>
            @endforelse
        </div>

        @if(isset($scenarios) && count($scenarios) > 0)
            <p class="mt-4 text-xs text-gray-500">Showing {{ count($scenarios) }} scenario(s) for this lesson.</p>
        @endif
    </div>
@endsection
