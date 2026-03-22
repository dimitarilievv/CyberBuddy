@extends('layouts.app')

@section('title', 'Certificate')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Certificate for {{ $certificate->module->name ?? 'Module' }}</h1>

        <p>User: {{ $certificate->user->name }}</p>
        <p>Score: {{ $certificate->score }}%</p>
        <p>Issued at: {{ $certificate->issued_at->format('d M Y') }}</p>

        <a href="{{ route('certificates.download', $certificate) }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block">Download PDF</a>
    </div>
@endsection
