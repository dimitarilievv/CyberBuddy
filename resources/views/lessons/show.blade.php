@extends('layouts.app')

@php
    use App\Models\UserProgress;
@endphp

@section('content')
    @php
        // Ensure $module exists: prefer the controller-provided $module, otherwise derive from the lesson relation.
        $module = $module ?? ($lesson->module ?? null);
    @endphp
    <div class="min-h-screen bg-gray-50">
        <!-- Breadcrumb -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-8">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>›</span>
                <a href="{{ route('modules.index') }}" class="hover:text-gray-900">Modules</a>
                <span>›</span>
                {{-- If $module is available link to it, otherwise fallback to modules index --}}
                @if($module)
                    <a href="{{ route('modules.show', $module->id) }}" class="hover:text-gray-900">{{ $module->name ?? 'Module' }}</a>
                @else
                    <a href="{{ route('modules.index') }}" class="hover:text-gray-900">Module</a>
                @endif
                <span>›</span>
                <span class="text-gray-900 font-medium">{{ $lesson->title }}</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Title -->
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $lesson->title }}</h1>
                    <div class="flex items-center gap-4 mb-8 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $lesson->estimated_minutes ?? '15' }} mins
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Reading + Video
                        </span>
                    </div>

                    <!-- Video Section -->
                    <div class="relative w-full bg-gray-900 rounded-2xl overflow-hidden mb-12 aspect-video">
                        @if($lesson->mediaFiles && $lesson->mediaFiles->where('type', 'video')->first())
                            <iframe
                                class="w-full h-full"
                                src="{{ $lesson->mediaFiles->where('type', 'video')->first()->url }}"
                                title="{{ $lesson->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content Tabs -->
                    <div class="mb-12">
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="flex gap-8">
                                <button class="py-4 border-b-2 border-blue-500 text-blue-600 font-semibold">Overview</button>
                                <button class="py-4 text-gray-600 font-semibold hover:text-gray-900">Resources</button>
                            </nav>
                        </div>

                        <!-- Overview Content -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $lesson->title }}</h3>
                            <div class="prose prose-blue max-w-none mb-8 text-gray-700 leading-relaxed">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>

                            <!-- Do's and Don'ts -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-12">
                                <!-- Do's -->
                                <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900">The "Do's"</h4>
                                    </div>
                                    <ul class="space-y-2 text-gray-700">
                                        <li class="flex items-start gap-2">
                                            <span class="text-green-500 font-bold mt-1">•</span>
                                            <span>Use at least 12 characters</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-green-500 font-bold mt-1">•</span>
                                            <span>Mix uppercase and lowercase letters</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-green-500 font-bold mt-1">•</span>
                                            <span>Include numbers and special symbols (!@#$%)</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-green-500 font-bold mt-1">•</span>
                                            <span>Use a Passphrase - a string of random words</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Don'ts -->
                                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900">The "Don'ts"</h4>
                                    </div>
                                    <ul class="space-y-2 text-gray-700">
                                        <li class="flex items-start gap-2">
                                            <span class="text-red-500 font-bold mt-1">•</span>
                                            <span>Don't use your name or birthday</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-red-500 font-bold mt-1">•</span>
                                            <span>Don't use easy sequences like "12345"</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-red-500 font-bold mt-1">•</span>
                                            <span>Don't share your password with friends</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-red-500 font-bold mt-1">•</span>
                                            <span>Don't use the same password for everything</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Did You Know? -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg my-12 flex gap-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Did you know?</h4>
                                    <p class="text-gray-700 text-sm mb-2">
                                        It would take a standard hacker's computer over 3,000 years to crack a 12-character random password, but only 2 seconds to crack a 6-character one!
                                    </p>
                                    <a href="#" class="text-blue-600 font-semibold text-sm hover:text-blue-700">Learn more about password length →</a>
                                </div>
                            </div>

                            <!-- Try the Passphrase Method -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-lg mb-12">
                                <h4 class="text-xl font-bold text-gray-900 mb-4">Try the "Passphrase" Method</h4>
                                <p class="text-gray-700 mb-6">
                                    <a href="#" class="text-blue-600 font-semibold hover:underline">#correcthorsebattery</a> is a combo of random words that are easy for you to remember but hard for a computer to guess. For example:
                                </p>
                                <div class="bg-white p-4 rounded border border-gray-200 font-mono text-sm">
                                    correct + horse + battery + 2024! = correcthorsebattery2024!
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Extra Resources -->
                    <div class="mb-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Extra Resources</h3>
                        <p class="text-gray-600 mb-6">Deepen your knowledge with these fun activities</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @forelse($lesson->resources as $resource)
                                <a href="{{ $resource->url ?? '#' }}" target="_blank" class="bg-white p-6 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-lg transition">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $resource->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $resource->type ?? 'Resource' }}</p>
                                </a>
                            @empty
                                <a href="#" class="bg-white p-6 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-lg transition">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Password Safety Guide</h4>
                                    <p class="text-sm text-gray-600">PDF Document</p>
                                </a>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Progress Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 sticky top-20">
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-600 uppercase mb-2">Your Progress</h4>
                            @php
                                // Compute totals only when module is available to avoid calling methods on null
                                if ($module) {
                                    $totalLessons = $module->lessons()->where('is_published', true)->count();
                                    $completedLessons = 0;
                                    if (auth()->check()) {
                                        $completedLessons = UserProgress::whereHas('enrollment', function($q) use ($module) {
                                            $q->where('user_id', auth()->id())
                                              ->where('module_id', $module->id);
                                        })
                                        ->where('status', 'completed')
                                        ->count();
                                    }
                                } else {
                                    $totalLessons = 0;
                                    $completedLessons = 0;
                                }
                            @endphp
                             <p class="text-3xl font-bold text-blue-600 mb-1">{{ $completedLessons }} / {{ $totalLessons }} Lessons</p>
                             <p class="text-xs text-gray-500">Starting your journey</p>
                         </div>

                        <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0 }}%"></div>
                        </div>

                        {{-- Complete button: only enable action when module is known --}}
                        @if($module)
                            <form action="{{ route('lessons.complete', [$module->id, $lesson->id]) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg text-center transition block">
                                    @if(isset($progress) && $progress && (is_object($progress) ? ($progress->status ?? null) === 'completed' : (is_array($progress) && ($progress['status'] ?? null) === 'completed')))
                                        ✓ Marked as Completed
                                    @else
                                        Mark as Completed
                                    @endif
                                </button>
                            </form>
                        @else
                            <div class="mb-3">
                                <button disabled class="w-full bg-gray-200 text-gray-400 font-semibold py-3 rounded-lg text-center cursor-not-allowed">
                                    Mark as Completed
                                </button>
                            </div>
                        @endif

                         @php
                            $hasQuiz = $lesson->quizzes && $lesson->quizzes->count() > 0;
                            $firstQuiz = $hasQuiz ? $lesson->quizzes->first() : null;
                        @endphp

                        @if($hasQuiz && $firstQuiz)
                            <a href="{{ route('quizzes.show', $firstQuiz->id) }}" class="w-full border-2 border-blue-500 text-blue-600 font-semibold py-3 rounded-lg text-center hover:bg-blue-50 transition block">
                                Take the Quiz →
                            </a>
                        @else
                            <button disabled class="w-full border-2 border-gray-300 text-gray-400 font-semibold py-3 rounded-lg text-center cursor-not-allowed">
                                No Quiz Available
                            </button>
                        @endif

                        @php
                            // Find first published scenario for this lesson (if any)
                            try {
                                $firstScenario = $lesson->scenarios()->where('is_published', true)->first();
                            } catch (\Throwable $e) {
                                // Fallback if relationship/method not available in view
                                $firstScenario = null;
                            }
                        @endphp

                        @if($firstScenario)
                            {{-- If a scenario exists, link directly to the Livewire attempt page for a smoother UX --}}
                            <a href="{{ route('scenarios.show', $firstScenario->id) }}" class="w-full mt-3 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg text-center transition block">
                                Take the Scenario →
                            </a>
                        @else
                            {{-- No direct scenario available: link to the lesson's scenario list as a fallback --}}
                            <a href="{{ route('scenarios.for_lesson', $lesson->id) }}" class="w-full mt-3 bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg text-center transition block">
                                View Scenarios →
                            </a>
                        @endif

                        <!-- Info Box -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <p class="text-xs text-blue-700">
                                <span class="font-semibold">💡 Pro Tip:</span> Finish this lesson to unlock the next one!
                            </p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h4 class="text-sm font-semibold text-gray-600 uppercase mb-4">Navigation</h4>

                        <div class="flex items-center justify-between mb-4">
                            @if($module)
                                <a href="{{ route('modules.show', $module->id) }}" class="text-blue-600 font-semibold hover:text-blue-700 flex items-center gap-1">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                     </svg>
                                     Back to Modules
                                </a>
                            @else
                                <a href="{{ route('modules.index') }}" class="text-blue-600 font-semibold hover:text-blue-700 flex items-center gap-1">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                     </svg>
                                     Back to Modules
                                </a>
                            @endif
                         </div>

                        <div class="space-y-3">
                            @if($prevLesson)
                                <div class="text-sm">
                                    @if($module)
                                        <a href="{{ route('lessons.show', [$module->id, $prevLesson->id]) }}" class="text-gray-600 hover:text-gray-900 font-medium">← {{ $prevLesson->title }}</a>
                                    @else
                                        <span class="text-gray-600 font-medium">← {{ $prevLesson->title }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($nextLesson)
                                <div class="text-sm">
                                    @if($module)
                                        <a href="{{ route('lessons.show', [$module->id, $nextLesson->id]) }}" class="text-gray-600 hover:text-gray-900 font-medium">{{ $nextLesson->title }} →</a>
                                    @else
                                        <span class="text-gray-600 font-medium">{{ $nextLesson->title }} →</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
