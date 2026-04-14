@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-1">Teacher Dashboard</h1>
        <div class="text-gray-500 mb-6">Manage your classroom, curate content, and leverage AI for new lessons.</div>

        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center">
                <span class="text-gray-400">Total Students</span>
                <span class="text-3xl font-bold text-blue-700 my-2">{{ $totalStudents }}</span>
                <span class="text-xs text-green-600">+12% this month</span>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center">
                <span class="text-gray-400">Safety Score</span>
                <span class="text-3xl font-bold text-slate-700 my-2">{{ $safetyScore['score'] }}%</span>
                <span class="text-xs text-gray-500">{{ ucfirst($safetyScore['trend']) }}</span>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center">
                <span class="text-gray-400">Lessons Completed</span>
                <span class="text-3xl font-bold text-green-600 my-2">{{ number_format($lessonsCompleted) }}</span>
                <span class="text-xs text-green-600">+{{ $lessonsCompletedToday }} today</span>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center">
                <span class="text-gray-400">Active Alerts</span>
                <span class="text-3xl font-bold text-red-600 my-2">{{ $activeAlerts }}</span>
                <span class="text-xs text-red-600">Requires attention</span>
            </div>
        </div>

        <!-- Main modules table + Content Architect (side) -->
        <div
            class="col-span-2 bg-white rounded-2xl shadow p-6"
            x-data="{
        showCreate: false,
        showEdit: false,
        showAssign: false,
        activeModule: null,
        modules: @js($myModules),
        editData: {},
    }"
        >
            <div class="flex justify-between items-center mb-4">
                <span class="font-bold text-lg text-gray-900">Manage Modules</span>
                <button @click="showCreate = true"
                        class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded shadow">+ New Module</button>
            </div>
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-2 rounded mb-2">{{ session('success') }}</div>
            @endif
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                <tr class="text-xs text-gray-500 uppercase">
                    <th class="py-2 font-semibold text-left">Title</th>
                    <th class="py-2 font-semibold text-left">Audience</th>
                    <th class="py-2 font-semibold text-left">Category</th>
                    <th class="py-2 font-semibold text-left">Difficulty</th>
                    <th class="py-2 font-semibold text-left">Age Group</th>
                    <th class="py-2 font-semibold text-left">Est. Duration</th>
                    <th class="py-2 font-semibold text-left">Last Updated</th>
                    <th class="py-2 font-semibold text-left">Status</th>
                    <th class="py-2 font-semibold text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($myModules as $module)
                    <tr>
                        <td class="py-2 font-medium">{{ $module->title }}</td>
                        <td class="py-2">
                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">
                        {{ ucfirst($module->audience ?? '-') }}
                    </span>
                        </td>
                        <td class="py-2">
                    <span class="bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-semibold">
                        {{ $module->category->name ?? '—' }}
                    </span>
                        </td>
                        <td class="py-2">{{ $module->difficulty ?? '-' }}</td>
                        <td class="py-2">{{ $module->age_group ?? '-' }}</td>
                        <td class="py-2">{{ $module->estimated_duration ?? '-' }}</td>
                        <td class="py-2">{{ $module->updated_at->diffForHumans() }}</td>
                        <td class="py-2">
                    <span class="px-2 py-1 rounded-full {{ $module->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $module->is_published ? 'Published' : 'Pending' }}
                    </span>
                        </td>
                        <td class="py-2 flex gap-2 flex-wrap">
                            <button
                                class="text-blue-600 font-semibold text-xs"
                                @click="
                                activeModule = {{ $module->id }};
                                showEdit = true;
                                editData = {
                                    title: '{{ addslashes($module->title) }}',
                                    description: `{{ addslashes($module->description ?? '') }}`,
                                    category_id: '{{ $module->category_id ?? '' }}',
                                    difficulty: '{{ addslashes($module->difficulty ?? '') }}',
                                    age_group: '{{ addslashes($module->age_group ?? '') }}',
                                    estimated_duration: '{{ addslashes($module->estimated_duration ?? '') }}',
                                    audience: '{{ $module->audience ?? '' }}'
                                };
                            "
                            >Edit</button>
                            <form
                                action="{{ route('teacher.modules.destroy', $module) }}"
                                method="POST"
                                onsubmit="return confirm('Delete module?')"
                                class="inline"
                            >
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 text-xs font-semibold">Delete</button>
                            </form>

                            @if(!$module->is_published)
                                <form action="{{ route('teacher.modules.publish', $module) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-blue-500 text-xs font-semibold">Publish</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Create Modal -->
            <div
                x-show="showCreate"
                x-cloak
                class="fixed inset-0 bg-black/30 z-40 flex items-center justify-center"
            >
                <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full">
                    <h2 class="font-semibold text-lg mb-3">Create Module</h2>
                    <form method="POST" action="{{ route('teacher.modules.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input class="w-full border rounded p-2 mb-2" name="title" placeholder="Module Title" required>
                        <textarea class="w-full border rounded p-2 mb-2" name="description" placeholder="Description"></textarea>
                        <input type="file" name="thumbnail" class="w-full border rounded p-2 mb-2"/>
                        <select name="audience" class="w-full border rounded mb-2" required>
                            <option value="">— Audience —</option>
                            <option value="child">Child</option>
                            <option value="parent">Parent</option>
                        </select>
                        <select name="category_id" class="w-full border rounded mb-2" required>
                            <option value="">— Category —</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="difficulty" class="w-full border rounded mb-2" x-model="editData.difficulty" required>
                            <option value="">— Difficulty —</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                        </select>
                        <input class="w-full border rounded p-2 mb-2" name="age_group" placeholder="Age Group">
                        <input class="w-full border rounded p-2 mb-2" name="estimated_duration" placeholder="Estimated Duration">
                        <div class="flex gap-2 mt-3">
                            <button class="bg-blue-600 text-white py-2 px-4 rounded" type="submit">Create</button>
                            <button type="button" @click="showCreate = false" class="bg-gray-300 py-2 px-4 rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Edit Modal -->
            <div
                x-show="showEdit"
                x-cloak
                class="fixed inset-0 bg-black/30 z-40 flex items-center justify-center"
            >
                <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full">
                    <h2 class="font-semibold text-lg mb-3">Edit Module</h2>
                    <form method="POST" :action="`{{ url('/teacher/modules') }}/` + activeModule" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <input class="w-full border rounded p-2 mb-2" name="title" placeholder="Module Title" required x-model="editData.title">
                        <textarea class="w-full border rounded p-2 mb-2" name="description" placeholder="Description" x-text="editData.description"></textarea>
                        <input type="file" name="thumbnail" class="w-full border rounded p-2 mb-2"/>
                        <select name="audience" class="w-full border rounded mb-2" x-model="editData.audience" required>
                            <option value="">— Audience —</option>
                            <option value="child">Child</option>
                            <option value="parent">Parent</option>
                        </select>
                        <select name="category_id" class="w-full border rounded mb-2" x-model="editData.category_id" required>
                            <option value="">— Category —</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="difficulty" class="w-full border rounded mb-2" x-model="editData.difficulty" required>
                            <option value="">— Difficulty —</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                        </select>

                        <input class="w-full border rounded p-2 mb-2" name="age_group" placeholder="Age Group" x-model="editData.age_group">
                        <input class="w-full border rounded p-2 mb-2" name="estimated_duration" placeholder="Estimated Duration" x-model="editData.estimated_duration">
                        <div class="flex gap-2 mt-3">
                            <button class="bg-blue-600 text-white py-2 px-4 rounded" type="submit">Update</button>
                            <button type="button" @click="showEdit = false" class="bg-gray-300 py-2 px-4 rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Assign Modal -->
            <div
                x-show="showAssign"
                x-cloak
                class="fixed inset-0 bg-black/30 z-40 flex items-center justify-center"
            >
                <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full">
                    <h2 class="font-semibold text-lg mb-3">Assign Lessons to Module</h2>
                    <form method="POST" :action="`{{ url('/teacher/modules') }}/` + activeModule + '/lessons'">
                        @csrf
                        <div class="mb-4 flex flex-col gap-2 max-h-40 overflow-y-auto">
                            @foreach($lessons as $lesson)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="lessons[]" value="{{ $lesson->id }}">
                                    {{ $lesson->title }}
                                </label>
                            @endforeach
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button class="bg-blue-600 text-white py-2 px-4 rounded" type="submit">Assign</button>
                            <button type="button" @click="showAssign = false" class="bg-gray-300 py-2 px-4 rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 flex flex-col h-full mt-5" x-data="{ tab: 'lesson' }">
            <span class="uppercase text-blue-500 text-xs font-semibold mb-2">AI Powered</span>
            <div class="font-bold text-lg mb-1">Generate Content with AI</div>
            <div class="text-gray-500 mb-3 text-xs">
                Instantly draft a lesson, quiz, or scenario for your modules with AI.
            </div>
            <div class="flex gap-1 mb-4">
                <button @click="tab = 'lesson'" :class="tab === 'lesson' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700'" class="px-3 py-1 rounded text-xs font-bold focus:outline-none">Lesson</button>
                <button @click="tab = 'quiz'" :class="tab === 'quiz' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700'" class="px-3 py-1 rounded text-xs font-bold focus:outline-none">Quiz</button>
                <button @click="tab = 'scenario'" :class="tab === 'scenario' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700'" class="px-3 py-1 rounded text-xs font-bold focus:outline-none">Scenario</button>
            </div>

            @if(session('success'))
                <div class="bg-green-50 text-green-800 p-2 rounded mb-2 font-semibold">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 text-red-800 p-2 rounded mb-2 font-semibold">{{ session('error') }}</div>
            @endif

            <!-- Lesson Generator -->
            <form x-show="tab === 'lesson'" method="POST" action="{{ route('teacher.ai.lesson.generate') }}">
                @csrf
                <div class="mb-2">
                    <label for="module_id_lesson" class="block text-xs font-semibold mb-1">Module</label>
                    <select name="module_id" id="module_id_lesson" required class="w-full border px-2 py-2 rounded">
                        <option value="">— select module —</option>
                        @foreach($myModules as $module)
                            <option value="{{ $module->id }}">{{ $module->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="topic_lesson" class="block text-xs font-semibold mb-1">Lesson Topic</label>
                    <input type="text" name="topic" id="topic_lesson" class="w-full border px-2 py-2 rounded" placeholder="e.g. Phishing, Social Media Privacy" required>
                </div>
                <button type="submit" class="btn-generate bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded mb-2 w-full">✨ Generate Lesson</button>
            </form>

            <!-- Quiz Generator -->
            <form x-show="tab === 'quiz'" method="POST" action="{{ route('teacher.ai.quiz.generate') }}">
                @csrf
                <div class="mb-2">
                    <label for="lesson_id_quiz" class="block text-xs font-semibold mb-1">Lesson</label>
                    <select name="lesson_id" id="lesson_id_quiz" required class="w-full border px-2 py-2 rounded">
                        <option value="">— select lesson —</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="topic_quiz" class="block text-xs font-semibold mb-1">Quiz Topic</label>
                    <input type="text" name="topic" id="topic_quiz" class="w-full border px-2 py-2 rounded" placeholder="e.g. Password Safety" required>
                </div>
                <button type="submit" class="btn-generate bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded mb-2 w-full">✨ Generate Quiz</button>
            </form>

            <!-- Scenario Generator -->
            <form x-show="tab === 'scenario'" method="POST" action="{{ route('teacher.ai.scenario.generate') }}">
                @csrf
                <div class="mb-2">
                    <label for="lesson_id_scenario" class="block text-xs font-semibold mb-1">Lesson</label>
                    <select name="lesson_id" id="lesson_id_scenario" required class="w-full border px-2 py-2 rounded">
                        <option value="">— select lesson —</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="topic_scenario" class="block text-xs font-semibold mb-1">Scenario Topic</label>
                    <input type="text" name="topic" id="topic_scenario" class="w-full border px-2 py-2 rounded" placeholder="e.g. Public Wi-Fi Safety" required>
                </div>
                <button type="submit" class="btn-generate bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded mb-2 w-full">✨ Generate Scenario</button>
            </form>

            <div class="text-xs text-gray-400 mt-3">
                * AI models generate content based on the latest NCSC and cybersecurity curriculum standards.
            </div>
        </div>

    </div>

    <!-- Pending AI Suggestions -->
    <div x-data="{
        reviewOpen: false,
        review: {},
        stripHtml(html) {
            if (!html) return '';
            const div = document.createElement('div');
            div.innerHTML = html;
            return div.textContent || div.innerText || '';
        },
        openReviewFromEvent(e) {
            const el = e.currentTarget;
            let item = {};
            try {
                const raw = el.getAttribute('data-item') || '{}';
                item = JSON.parse(raw);
            } catch (err) {
                console && console.error('Failed to parse item JSON', err);
                item = {};
            }
            item.approveUrl = el.getAttribute('data-approve-url') || null;
            item.declineUrl = el.getAttribute('data-decline-url') || null;
            item.type = el.getAttribute('data-type') || item.type || null;
            this.review = item;
            this.reviewOpen = true;
        },
        closeReview() { this.reviewOpen = false; this.review = {}; }
    }">
        <div class="bg-white rounded-2xl shadow p-6 mt-2">
            <span class="font-bold text-lg text-gray-900 block mb-4">Pending AI Suggestions</span>
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                <tr class="text-xs text-gray-500 uppercase">
                    <th class="py-2 font-semibold text-left">Draft Content Name</th>
                    <th class="py-2 font-semibold text-left">Type</th>
                    <th class="py-2 font-semibold text-left">Vetting</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pendingLessons as $lesson)
                    <tr>
                        <td class="py-2">{{ $lesson->title }}</td>
                        <td class="py-2">Lesson</td>
                        <td class="py-2">
                            <div class="flex gap-2">
                                <button @click="openReviewFromEvent($event)" data-item='@json($lesson)' data-approve-url="{{ route('teacher.lessons.approve', $lesson) }}" data-decline-url="{{ route('teacher.lessons.reject', $lesson) }}" data-type="Lesson" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-all duration-200">
                                    👁️ Review
                                </button>

                                <form action="{{ route('teacher.lessons.approve', $lesson) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 hover:text-green-800 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                        ✓ Approve
                                    </button>
                                </form>

                                <form action="{{ route('teacher.lessons.reject', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('Decline this suggestion?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                        ✕ Decline
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @foreach($pendingQuizzes as $quiz)
                    <tr>
                        <td class="py-2">{{ $quiz->title }}</td>
                        <td class="py-2">Quiz</td>
                        <td class="py-2">
                            <div class="flex gap-2">
                                <button @click="openReviewFromEvent($event)" data-item='@json($quiz)' data-approve-url="{{ route('teacher.quizzes.approve', $quiz) }}" data-decline-url="{{ route('teacher.quizzes.reject', $quiz) }}" data-type="Quiz" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-all duration-200">
                                    👁️ Review
                                </button>

                                <form action="{{ route('teacher.quizzes.approve', $quiz) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 hover:text-green-800 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                        ✓ Approve
                                    </button>
                                </form>

                                <form action="{{ route('teacher.quizzes.reject', $quiz) }}" method="POST" class="inline" onsubmit="return confirm('Decline this suggestion?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                        ✕ Decline
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @foreach($pendingScenarios as $scenario)
                    <tr>
                        <td class="py-2">{{ $scenario->title }}</td>
                        <td class="py-2">Scenario</td>
                        <td class="py-2">
                            <div class="flex gap-2">
                                <button @click="openReviewFromEvent($event)" data-item='@json($scenario)' data-approve-url="{{ route('teacher.scenarios.approve', $scenario) }}" data-decline-url="{{ route('teacher.scenarios.reject', $scenario) }}" data-type="Scenario" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-all duration-200">
                                    👁️ Review
                                </button>

                                <form action="{{ route('teacher.scenarios.approve', $scenario) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 hover:text-green-800 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                        ✓ Approve
                                    </button>
                                </form>

                                <form action="{{ route('teacher.scenarios.reject', $scenario) }}" method="POST" class="inline" onsubmit="return confirm('Decline this suggestion?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                        ✕ Decline
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Beautiful Review Modal - PLAIN TEXT ONLY (HTML STRIPPED) -->
            <div x-show="reviewOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-2xl">
                        <div>
                            <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Review Suggestion</p>
                            <h3 class="text-2xl font-black text-white mt-1" x-text="review.title || review.name || 'Content'"></h3>
                        </div>
                        <button @click="closeReview()" class="text-white hover:text-gray-200 transition-colors text-3xl leading-none">✕</button>
                    </div>

                    <!-- Content - PLAIN TEXT ONLY (HTML STRIPPED) -->
                    <div class="p-6 space-y-4 max-h-64 overflow-y-auto">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <p class="text-xs font-bold text-gray-600 uppercase">Type</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1" x-text="review.type || 'Item'"></p>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-gray-600 uppercase">Created</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1" x-text="new Date(review.created_at).toLocaleString()"></p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 space-y-3 border border-gray-200">
                            <div x-show="review.description">
                                <p class="text-xs font-bold text-gray-600 uppercase mb-2">Description</p>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed" x-text="stripHtml(review.description)"></p>
                            </div>

                            <div x-show="review.content">
                                <p class="text-xs font-bold text-gray-600 uppercase mb-2">Content</p>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed" x-text="stripHtml(review.content)"></p>
                            </div>

                            <div x-show="review.body">
                                <p class="text-xs font-bold text-gray-600 uppercase mb-2">Body</p>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed" x-text="stripHtml(review.body)"></p>
                            </div>

                            <div x-show="review.text">
                                <p class="text-xs font-bold text-gray-600 uppercase mb-2">Text</p>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed" x-text="stripHtml(review.text)"></p>
                            </div>

                            <div x-show="review.message">
                                <p class="text-xs font-bold text-gray-600 uppercase mb-2">Message</p>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed" x-text="stripHtml(review.message)"></p>
                            </div>

                            <template x-if="review.questions && review.questions.length > 0">
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase mb-2">Questions</p>
                                    <div class="space-y-3">
                                        <template x-for="(q, idx) in review.questions" :key="idx">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800" x-text="`${idx + 1}. ${stripHtml(q.question || q.title || '')}`"></p>
                                                <template x-if="q.options && q.options.length > 0">
                                                    <div class="mt-1 ml-3">
                                                        <template x-for="(opt, optIdx) in q.options" :key="optIdx">
                                                            <p class="text-sm text-gray-700" x-text="`• ${stripHtml(opt)}`"></p>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end border-t border-gray-200 rounded-b-2xl">
                        <button @click="closeReview()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-bold text-sm transition-all">
                            Close
                        </button>

                        <form :action="review.declineUrl" method="POST" x-show="review.declineUrl" onsubmit="return confirm('Decline this suggestion?');" class="inline">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-sm transition-all shadow-md hover:shadow-lg">
                                ✕ Decline
                            </button>
                        </form>

                        <form :action="review.approveUrl" method="POST" x-show="review.approveUrl" class="inline">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg font-bold text-sm transition-all shadow-md hover:shadow-lg">
                                ✓ Approve
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Recent Student Activity -->
    <div class="bg-white rounded-2xl shadow p-6 mt-8 mb-4">
        <span class="font-bold text-lg text-gray-900 block mb-4">Recent Student Activity</span>

        <div class="space-y-2">
            @foreach($recentActivities as $item)
                <div class="text-gray-700 text-sm">{{ $item['message'] }}</div>
            @endforeach
        </div>

        <a href="#" class="block text-center mt-3 text-blue-600 font-bold text-xs">View Full Student Roster</a>
    </div>
    </div>
@endsection
