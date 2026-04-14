@extends('layouts.app')

@section('title', 'Certificate Details')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header with back button -->
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('certificates.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Achievements
                </a>
                <span class="inline-flex items-center bg-gradient-to-r from-green-400 to-emerald-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    Verified Authentic
                </span>
            </div>

            <!-- Main content grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Certificate Preview (Main) -->
                <div class="lg:col-span-2">
                    <div class="relative">
                        <!-- Decorative background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-200 to-purple-200 rounded-3xl blur-2xl opacity-30"></div>

                        <!-- Certificate Card -->
                        <div class="relative bg-white shadow-2xl rounded-3xl overflow-hidden">
                            <!-- Top decorative border -->
                            <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-500"></div>

                            <!-- Certificate Content -->
                            <div class="p-12 sm:p-16 text-center relative">
                                <!-- Decorative corners -->
                                <div class="absolute top-4 left-4 w-8 h-8 border-t-2 border-l-2 border-blue-300"></div>
                                <div class="absolute top-4 right-4 w-8 h-8 border-t-2 border-r-2 border-blue-300"></div>
                                <div class="absolute bottom-4 left-4 w-8 h-8 border-b-2 border-l-2 border-blue-300"></div>
                                <div class="absolute bottom-4 right-4 w-8 h-8 border-b-2 border-r-2 border-blue-300"></div>

                                <!-- Header -->
                                <div class="mb-8">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-blue-600 tracking-widest uppercase mb-2">CyberBuddy Academy</p>
                                    <h1 class="text-5xl font-black text-gray-900 mb-2">Certificate</h1>
                                    <p class="text-xl font-semibold text-purple-600 tracking-widest">OF COMPLETION</p>
                                </div>

                                <!-- Recipient info -->
                                <div class="my-10 py-8 border-t-2 border-b-2 border-gray-300">
                                    <p class="text-gray-600 mb-3 italic">This is to certify that</p>
                                    <h2 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-3">
                                        {{ $certificate->user->name }}
                                    </h2>
                                    <p class="text-gray-700">has successfully completed the comprehensive cybersecurity training module</p>
                                </div>

                                <!-- Module title -->
                                <div class="mb-10">
                                    <div class="inline-block bg-gradient-to-r from-blue-100 to-purple-100 px-8 py-4 rounded-full">
                                        <p class="text-blue-700 font-bold text-xl">{{ $certificate->module->title }}</p>
                                    </div>
                                </div>

                                <!-- Footer info -->
                                <div class="grid grid-cols-2 gap-6 pt-8 text-xs text-gray-600">
                                    <div class="text-left">
                                        <p class="font-semibold text-gray-800">ISSUED ON</p>
                                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $certificate->issued_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-800">CERTIFICATE ID</p>
                                        <p class="mt-1 text-lg font-bold text-gray-900 font-mono">{{ $certificate->certificate_number ?? ('CBP-' . str_pad($certificate->id, 6, '0', STR_PAD_LEFT)) }}</p>
                                    </div>
                                </div>

                                <!-- Bottom message -->
                                <p class="mt-8 text-xs text-gray-500 italic">
                                    This certificate is a permanent record of your digital safety skills.<br/>
                                    You can download it for your portfolio or print it to show your teacher!
                                </p>
                            </div>

                            <!-- Bottom decorative border -->
                            <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-500"></div>
                        </div>

                        <!-- Floating badge decoration -->
                        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-gradient-to-br  rounded-full opacity-20 blur-2xl"></div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Main Actions -->
                    <div class="bg-white shadow-xl rounded-2xl p-6 border-t-4 border-blue-500">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('certificates.download', $certificate) }}"
                               class="w-full inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download PDF
                            </a>
                            <button onclick="window.print()"
                                    class="w-full inline-flex items-center justify-center bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-800 px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m4-4V9m0 4v4"></path>
                                </svg>
                                Print Certificate
                            </button>
                        </div>
                    </div>

                    <!-- Certificate Stats -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-500 shadow-xl rounded-2xl p-6 text-white">
                        <h3 class="text-lg font-bold mb-4">Your Achievement</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-purple-100">Completion Date</span>
                                <span class="font-bold">{{ $certificate->issued_at->diffForHumans() }}</span>
                            </div>
                            <div class="h-px bg-white/30"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-purple-100">Module</span>
                                <span class="font-bold">{{ $certificate->module->title }}</span>
                            </div>
                            <div class="h-px bg-white/30"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-purple-100">Status</span>
                                <span class="inline-flex items-center px-3 py-1 bg-white/20 text-white rounded-full text-sm font-bold">
                                    ✓ Verified
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Milestone -->
                    <div class="bg-white shadow-xl rounded-2xl p-6 border-l-4 border-yellow-400">
                        <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Next Milestone
                        </h3>
                        <div class="mb-4">
                            <p class="text-gray-600 text-sm mb-2">You're only <span class="font-bold text-blue-600">2 certificates</span> away from:</p>
                            <p class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-orange-500">
                                Master Defender Badge
                            </p>
                        </div>
                        <a href="{{ route('modules.index') }}"
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold transition-colors">
                            View Next Module
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Share Badge -->
                    <div class="bg-white shadow-xl rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Share Your Success</h3>
                        <p class="text-gray-600 text-sm mb-4">Show your achievement to friends and family!</p>
                        <div class="flex gap-3">
                            <a href="#" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg p-3 transition-colors text-center font-bold">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="flex-1 bg-sky-100 hover:bg-sky-200 text-sky-700 rounded-lg p-3 transition-colors text-center font-bold">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 002.856-3.51 10.02 10.02 0 01-2.836.856 4.958 4.958 0 002.165-2.724c-.951.564-2.005.974-3.127 1.195a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg p-3 transition-colors text-center font-bold">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
