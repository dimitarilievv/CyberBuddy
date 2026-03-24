@extends('layouts.app')

@section('title', 'Report Content')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Report Content</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('reported_contents.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Reportable Type (Model class)</label>
                <input type="text" name="reportable_type" value="{{ old('reportable_type', 'App\\Models\\Question') }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-semibold mb-1">Reportable ID</label>
                <input type="number" name="reportable_id" value="{{ old('reportable_id', 1) }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-semibold mb-1">Reason</label>
                <select name="reason" class="w-full border rounded px-3 py-2">
                    <option value="inappropriate">inappropriate</option>
                    <option value="incorrect">incorrect</option>
                    <option value="offensive">offensive</option>
                    <option value="spam">spam</option>
                    <option value="other">other</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Description (optional)</label>
                <textarea name="description" rows="5"
                          class="w-full border rounded px-3 py-2"
                          placeholder="Explain the issue...">{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Submit report
                </button>

                <a href="{{ route('reported_contents.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
