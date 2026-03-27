@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($canAttempt)
                <!-- Quiz Header - Ќе се освежува од Livewire -->
                <livewire:quiz.header :quiz="$quiz" />

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 py-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-4">
                        <livewire:quiz.attempt :quiz-id="$quiz->id" :quiz="$quiz" />
                    </div>
                </div>
            @else
                <div class="py-12">
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <div class="mb-6">
                            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Cannot Attempt This Quiz</h2>
                        <p class="text-gray-600 mb-6">You have reached the maximum number of attempts for this quiz. Please try another quiz or contact your instructor.</p>
                        <a href="{{ route('modules.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                            Back to Modules
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
