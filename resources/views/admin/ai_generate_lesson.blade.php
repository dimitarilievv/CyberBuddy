@extends('layouts.app')
@section('content')
<h2>Generate AI Lesson</h2>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="POST" action="{{ route('admin.ai.lesson.generate') }}">
    @csrf
    <label>Module:</label>
    <select name="module_id" required>
        @foreach($modules as $module)
            <option value="{{ $module->id }}">{{ $module->title }}</option>
        @endforeach
    </select>
    <label>Topic:</label>
    <input type="text" name="topic" required>
    <button type="submit">Generate Lesson</button>
</form>
@endsection

