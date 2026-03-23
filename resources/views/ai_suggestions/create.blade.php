@extends('layouts.app')

@section('title', 'New AI Suggestion')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">New AI Suggestion</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ai_suggestions.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Content Type</label>
                <select name="content_type" class="w-full border rounded px-3 py-2">
                    <option value="question" @selected(old('content_type') === 'question')>question</option>
                    <option value="scenario" @selected(old('content_type') === 'scenario')>scenario</option>
                    <option value="tip" @selected(old('content_type', 'tip') === 'tip')>tip</option>
                    <option value="resource" @selected(old('content_type') === 'resource')>resource</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border rounded px-3 py-2" placeholder="Title">
            </div>

            <div>
                <label class="block font-semibold mb-1">Suggested Content</label>
                <textarea name="suggested_content" rows="6"
                          class="w-full border rounded px-3 py-2"
                          placeholder="Write the suggested question/scenario/tip...">{{ old('suggested_content') }}</textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Submit
                </button>

                <a href="{{ route('ai_suggestions.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
