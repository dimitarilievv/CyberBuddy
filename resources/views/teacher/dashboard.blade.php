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
{{--                    <th class="py-2 font-semibold text-left">Thumbnail</th>--}}
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
{{--                        <td class="py-2">--}}
{{--                            @if($module->thumbnail)--}}
{{--                                <img src="{{ asset('storage/' . $module->thumbnail) }}" class="w-12 h-12 object-cover rounded" alt="Thumbnail">--}}
{{--                            @else--}}
{{--                                <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-400">--}}
{{--                                    <span class="text-xs">No Image</span>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </td>--}}
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
                        </select>                        <input class="w-full border rounded p-2 mb-2" name="age_group" placeholder="Age Group">
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
            <!-- Assign Modal (unchanged) -->
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

            <div class="bg-white rounded-2xl shadow p-6 flex flex-col h-full" x-data="{ tab: 'lesson' }">
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
        <div class="bg-white rounded-2xl shadow p-6 mt-8">
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
                            <form action="{{ route('teacher.lessons.approve', $lesson) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-green-700">Approve</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @foreach($pendingQuizzes as $quiz)
                    <tr>
                        <td class="py-2">{{ $quiz->title }}</td>
                        <td class="py-2">Quiz</td>
                        <td class="py-2">
                            <form action="{{ route('teacher.quizzes.approve', $quiz) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-green-700">Approve</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @foreach($pendingScenarios as $scenario)
                    <tr>
                        <td class="py-2">{{ $scenario->title }}</td>
                        <td class="py-2">Scenario</td>
                        <td class="py-2">
                            <form action="{{ route('teacher.scenarios.approve', $scenario) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-green-700">Approve</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Recent Student Activity -->
        <div class="bg-white rounded-2xl shadow p-6 mt-8">
            <span class="font-bold text-lg text-gray-900 block mb-4">Recent Student Activity</span>
            <ul>
                @foreach($recentActivities as $item)
                    <li class="flex gap-2 items-center mb-3">
                        <span class="text-base">{{ $item['icon'] ?? '🟢' }}</span>
                        @if(isset($item['user']))
                            <span class="font-bold text-gray-700">{{ $item['user']->name ?? 'Student' }} </span>
                        @endif
                        <span class="text-gray-700">{{ $item['message'] }}</span>
                        <span class="flex-1"></span>
                        <span class="text-xs text-gray-500">{{ $item['time_ago'] }}</span>
                    </li>
                @endforeach
            </ul>
            <a href="#" class="block text-center mt-3 text-blue-600 font-bold text-xs">View Full Student Roster</a>
        </div>
    </div>
@endsection
