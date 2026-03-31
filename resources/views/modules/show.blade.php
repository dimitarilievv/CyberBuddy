@extends('layouts.app')

@section('content')
    @if($isEnrolled)
        @php
            $firstLesson = $module->lessons()->where('is_published', true)->orderBy('sort_order')->first();
        @endphp
        @if($firstLesson)
            <a href="{{ route('lessons.show', [$module->id, $firstLesson->id]) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg w-full text-center inline-block">
                Go to Lesson
            </a>
        @endif
    @endif
@endsection
