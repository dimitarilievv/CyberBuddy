@extends('layouts.app')

@section('title', 'My Certificates')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">My Certificates</h1>

        <ul class="space-y-4">
            @foreach($certificates as $cert)
                <li class="bg-white shadow p-4 rounded flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $cert->module->name ?? 'Module' }}</p>
                        <p class="text-sm text-gray-600">Issued: {{ $cert->issued_at->format('d M Y') }}</p>
                        <p class="text-sm text-gray-600">Score: {{ $cert->score }}%</p>
                    </div>
                    <div>
                        <a href="{{ route('certificates.download', $cert) }}" class="bg-blue-500 text-white px-3 py-1 rounded">Download PDF</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
