@extends('layouts.app')

@section('title', 'My Certificates')

@section('content')
    <div class="min-h-screen bg-gray-100 py-12">

        {{-- Header --}}
        <div class="max-w-3xl mx-auto mb-8">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">My Certificates</h1>
            </div>
            <p class="text-sm text-gray-400 ml-11">All your earned certificates in one place</p>
            <div class="mt-5 border-t border-gray-100"></div>
        </div>

        {{-- List --}}
        <ul class="max-w-3xl mx-auto space-y-3">
            @forelse($certificates as $cert)
                <li class="group bg-white border border-gray-200 rounded-xl p-5 flex items-center justify-between gap-4
                        hover:border-blue-300 hover:shadow-sm hover:shadow-blue-50
                        transition-all duration-200">

                    {{-- Icon --}}
                    <div
                        class="shrink-0 w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                        </svg>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            {{ $cert->module->title ?? 'Module' }}
                        </p>
                        <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs text-gray-400">
                            {{ $cert->issued_at->format('d M Y') }}
                        </span>
                            <span class="text-gray-200 text-xs">|</span>
                            <span class="text-xs font-medium text-blue-600">
                            Score: {{ $cert->final_score }}
                        </span>
                        </div>
                    </div>

                    {{-- Score badge --}}
                    <div class="hidden sm:flex shrink-0">
                    <span
                        class="px-2.5 py-1 bg-blue-50 border border-blue-100 rounded-full text-xs font-semibold text-blue-700">
                        Completed
                    </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('certificates.show', $cert) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium
                              text-blue-600 border border-blue-200 bg-white
                              hover:bg-blue-50 hover:border-blue-300
                              transition-all duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            View
                        </a>
                        <a href="{{ route('certificates.download', $cert) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium
                              bg-blue-600 text-white
                              hover:bg-blue-700
                              transition-all duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            PDF
                        </a>
                    </div>
                </li>
            @empty
                <li class="text-center py-16 border border-dashed border-blue-200 rounded-xl bg-blue-50/40">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">No certificates yet</p>
                    <p class="text-xs text-gray-400 mt-1">Complete a module to earn your first certificate.</p>
                </li>
            @endforelse
        </ul>
    </div>
@endsection
