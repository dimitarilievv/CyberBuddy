@extends('layouts.app')


@section('content')
    <div class="max-w-4xl mx-auto py-12 px-4">
        <h1 class="text-2xl font-bold mb-4">Scenario result</h1>
        <p class="mb-6 text-gray-600">Thanks for submitting your choice — here’s how you did.</p>

        {{-- Mount Livewire Result with the attempt instance --}}
        @livewire('scenario.result', ['attempt' => $attempt])

        <div class="mt-8">
            <a href="{{ route('scenarios.index') }}" class="text-sm text-cyan-600 hover:underline">Back to scenarios</a>
        </div>
    </div>
@endsection
