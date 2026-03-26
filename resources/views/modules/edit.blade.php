@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-6">
        <h2 class="text-2xl font-bold mb-4">Edit Module</h2>
        @if($errors->any())
            <div class="bg-red-100 text-red-700 rounded p-2 mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('modules.update', $module) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold mb-1" for="title">Title</label>
                <input class="w-full border px-3 py-2 rounded" name="title" id="title" required value="{{ old('title', $module->title) }}">
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="description">Description</label>
                <textarea class="w-full border px-3 py-2 rounded" name="description" id="description" rows="3">{{ old('description', $module->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="category_id">Category</label>
                <select class="w-full border px-3 py-2 rounded" name="category_id" id="category_id">
                    <option value="">— Select Category —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(old('category_id', $module->category_id) == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-1" for="difficulty">Difficulty</label>
                    <input class="w-full border px-3 py-2 rounded" name="difficulty" id="difficulty" value="{{ old('difficulty', $module->difficulty) }}">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1" for="age_group">Age Group</label>
                    <input class="w-full border px-3 py-2 rounded" name="age_group" id="age_group" value="{{ old('age_group', $module->age_group) }}">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="estimated_duration">Estimated Duration</label>
                <input class="w-full border px-3 py-2 rounded" name="estimated_duration" id="estimated_duration" value="{{ old('estimated_duration', $module->estimated_duration) }}">
            </div>

            <button class="bg-blue-600 text-white font-bold px-5 py-2 rounded" type="submit">Save Changes</button>
            <a href="{{ route('modules.index') }}" class="ml-3 text-slate-600 hover:underline">Cancel</a>
        </form>
    </div>
@endsection
