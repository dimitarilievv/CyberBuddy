@extends('layouts.app')

@section('title', $scenario->title)

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <a href="{{ url()->previous() }}" class="text-cyan-600 hover:text-cyan-700 font-semibold mb-6 inline-flex items-center gap-2">
                ← Back
            </a>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <h1 class="text-4xl font-bold text-slate-900 mb-2">{{ $scenario->title }}</h1>

                    @if($scenario->module)
                        <div class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold mb-4">
                            📚 {{ $scenario->module->name }}
                        </div>
                    @endif

                    <p class="text-lg text-slate-600 mb-6">{{ $scenario->situation ?? $scenario->description }}</p>

                    <form action="{{ route('scenarios.submit', $scenario->id) }}" method="POST" id="scenario-submit-form">
                        @csrf

                        <div class="space-y-4">
                            @foreach($scenario->choices as $choice)
                                <label class="block bg-gray-50 border border-gray-100 rounded-lg p-4 cursor-pointer">
                                    <input type="radio" name="choice_id" value="{{ $choice->id }}" class="mr-3 align-middle" />
                                    <span class="font-semibold">{{ $choice->choice_text }}</span>
                                    <div class="text-sm text-gray-500 mt-1">{{ $choice->consequence }}</div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-6 flex items-center gap-3">
                            <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-6 rounded-lg">Submit Answer</button>

                            <a href="{{ route('scenario.attempt', $scenario->id) }}" class="text-sm text-gray-500 underline">Or open interactive attempt</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
