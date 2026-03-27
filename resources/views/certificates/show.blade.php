@extends('layouts.app')

@section('title', 'Certificate Details')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <!-- Back navigation and page title -->
        <div class="flex items-center mb-2">
            <a href="{{ route('certificates.index') }}" class="text-gray-500 text-sm hover:underline flex items-center">
                &lt; Back to Achievements
            </a>
            <h1 class="ml-6 text-xl font-bold flex-1">Certificate Details</h1>
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M5 13l4 4L19 7"></path>
            </svg>
            Verified Authentic
        </span>
        </div>

        <div class="flex flex-col md:flex-row mt-6 gap-8">
            <!-- Certificate preview card -->
            <div class="bg-white shadow-lg rounded-xl p-8 flex-1 relative">
                <div class="border-4 border-blue-200 rounded-xl p-6 text-center relative z-10 bg-white">
                    <div class="text-sm text-blue-400 font-semibold mb-1 tracking-wider">CYBERBUDDY ACADEMY</div>
                    <div class="text-3xl font-black mb-1">CERTIFICATE</div>
                    <div class="text-md font-semibold mb-2 tracking-widest">OF COMPLETION</div>
                    <div class="my-4 text-gray-700">
                        <span class="italic">This is to certify that</span> <br>
                        <span class="text-2xl font-bold">{{ $certificate->user->name }}</span>
                    </div>
                    <div class="mb-2">
                        has successfully completed the comprehensive cybersecurity
                        training module:
                    </div>
                    <div class="text-blue-700 font-bold text-lg mb-2">
                        "{{ $certificate->module->title }}"
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-7 mb-1">
                        <div>
                            ISSUED ON: {{ $certificate->issued_at->format('F d, Y') }}
                        </div>
                        <div>
                            CERTIFICATE
                            ID: {{ $certificate->certificate_number ?? ('CBP-' . str_pad($certificate->id, 6, '0', STR_PAD_LEFT)) }}
                        </div>
                    </div>
                    <div class="text-gray-400 text-xs mt-2">
                        This certificate is a permanent record of your digital safety skills.
                        You can download it for your portfolio or print it to show your teacher!
                    </div>
                </div>
                <!-- stamp icon-like accent, optional -->
                <div class="absolute left-2/4 bottom-0 -mb-8 -translate-x-2/4 z-0 opacity-25">
                    <svg width="80" height="80">
                        <circle cx="40" cy="40" r="36" stroke="#60a5fa" stroke-width="8" fill="none"/>
                    </svg>
                </div>
            </div>

            <!-- Actions and sharing -->
            <div class="flex flex-col w-full md:w-72 gap-4">
                <!-- Actions -->
                <div class="bg-white shadow rounded-lg p-6 flex flex-col gap-3">
                    <div class="font-semibold mb-1">Certificate Actions</div>
                    <a href="{{ route('certificates.download', $certificate) }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded font-bold flex items-center justify-center text-center mb-2">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2m-5 0V9m0 8l-4-4m4 4l4-4"></path>
                        </svg>
                        Download PDF
                    </a>
                    <button onclick="window.print()"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded font-bold w-full flex items-center justify-center text-center">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path d="M6 9V2h12v7m2 6v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4"></path>
                        </svg>
                        Print Certificate
                    </button>
                </div>

                <!-- Share badge (optionally integrate with actual sharing) -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="font-semibold mb-2">Share Success</div>
                    <div class="flex gap-2">
                        <a href="#" class="bg-blue-100 text-blue-700 rounded-full p-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="..."/>
                            </svg>
                        </a>
                        <a href="#" class="bg-blue-100 text-blue-700 rounded-full p-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="..."/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Milestone progress -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-xs text-gray-500">Next Milestone</div>
                    <div class="font-semibold text-blue-700 mt-1 mb-1">
                        You’re only 2 certificates away<br/> from the <span
                            class="underline font-bold">Master Defender</span> badge!
                    </div>
                    <a href="#" class="text-sm text-blue-500 hover:underline">View Next Module →</a>
                </div>
            </div>
        </div>
    </div>
@endsection
