@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Admin Dashboard</h1>
        <div
            x-data="{
                tab: 'users',
                showResolveModal: false,
                resolveId: null,
                resolveNotes: ''
            }"
            class="mt-10"
        >
        <!-- Top Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="flex items-center p-5 bg-blue-50 rounded-xl shadow group hover:bg-blue-100 transition">
                <div class="p-3 rounded-full bg-blue-100 text-blue-700 mr-4 group-hover:bg-blue-500 group-hover:text-white"><svg class="w-5 h-5" fill="currentColor"><use xlink:href="#heroicon-user-group"></use></svg></div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['total_users'] }}</div>
                    <div class="text-xs text-blue-700 tracking-wide">Total Users</div>
                </div>
            </div>
            <div class="flex items-center p-5 bg-green-50 rounded-xl shadow group hover:bg-green-100 transition">
                <div class="p-3 rounded-full bg-green-100 text-green-700 mr-4 group-hover:bg-green-500 group-hover:text-white"><svg class="w-5 h-5" fill="currentColor"><use xlink:href="#heroicon-shield-check"></use></svg></div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['active_users'] }}</div>
                    <div class="text-xs text-green-700 tracking-wide">Active Users</div>
                </div>
            </div>
            <div class="flex items-center p-5 bg-teal-50 rounded-xl shadow group hover:bg-teal-100 transition">
                <div class="p-3 rounded-full bg-teal-100 text-teal-700 mr-4 group-hover:bg-teal-500 group-hover:text-white"><svg class="w-5 h-5" fill="currentColor"><use xlink:href="#heroicon-book-open"></use></svg></div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['published_modules'] }}</div>
                    <div class="text-xs text-teal-700 tracking-wide">Published Modules</div>
                </div>
            </div>
            <div class="flex items-center p-5 bg-red-50 rounded-xl shadow group hover:bg-red-100 transition">
                <div class="p-3 rounded-full bg-red-100 text-red-700 mr-4 group-hover:bg-red-500 group-hover:text-white"><svg class="w-5 h-5" fill="currentColor"><use xlink:href="#heroicon-exclamation-circle"></use></svg></div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['pending_reports'] }}</div>
                    <div class="text-xs text-red-700 tracking-wide">Pending Reports</div>
                </div>
            </div>
        </div>
{{--        <div class="grid md:grid-cols-4 gap-4 mb-4">--}}
{{--            <div class="p-4 rounded-xl bg-indigo-50 shadow text-center">--}}
{{--                <div class="text-xl font-bold">{{ $stats['total_enrollments'] }}</div>--}}
{{--                <div class="text-indigo-700 text-xs">Enrollments</div>--}}
{{--            </div>--}}
{{--            <div class="p-4 rounded-xl bg-yellow-50 shadow text-center">--}}
{{--                <div class="text-xl font-bold">{{ $stats['completed_jobs'] }}</div>--}}
{{--                <div class="text-yellow-700 text-xs">Completed Jobs</div>--}}
{{--            </div>--}}
{{--            <div class="p-4 rounded-xl bg-orange-50 shadow text-center">--}}
{{--                <div class="text-xl font-bold">{{ $stats['total_children'] }}</div>--}}
{{--                <div class="text-orange-700 text-xs">Children</div>--}}
{{--            </div>--}}
{{--            <div class="p-4 rounded-xl bg-purple-50 shadow text-center">--}}
{{--                <div class="text-xl font-bold">{{ $stats['total_teachers'] }}</div>--}}
{{--                <div class="text-purple-700 text-xs">Teachers</div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Tabbed Management Section -->
            <div x-show="tab==='users'" class="bg-white rounded-xl shadow p-6">
                <div class="mb-2 flex justify-between items-center">
                    <span class="font-bold text-xl">All Users</span>
                    <a href="{{ route('admin.users.export') }}" target="_blank" class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-bold hover:bg-blue-700">Export CSV</a>
                </div>

                @if (session('status'))
                    <div class="mb-3 text-green-600 text-sm">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-3 text-red-600 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow divide-y divide-gray-100">
                        <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <th class="py-2 px-4 text-left">Name</th>
                            <th class="py-2 px-4 text-left">Email</th>
                            <th class="py-2 px-4 text-left">Role</th>
                            <th class="py-2 px-4 text-left">Status</th>
                            <th class="py-2 px-4 text-left">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\User::orderBy('name')->take(30)->get() as $user)
                            @php
                                // If you're fully on Spatie, use roles; otherwise fall back to column
                                $currentRole = method_exists($user, 'roles')
                                    ? $user->roles->pluck('name')->implode(', ')
                                    : ($user->role ?? '');
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $user->name }}</td>
                                <td class="py-2 px-4">{{ $user->email }}</td>
                                <td class="py-2 px-4 capitalize">
                                    {{ $currentRole ?: '— none —' }}
                                </td>
                                <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                                </td>
                                <td class="py-2 px-4">
                                    <button
                                        type="button"
                                        class="text-xs px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
                                        onclick="openRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    >
                                        Assign role
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="tab==='modules'" class="bg-white rounded-xl shadow p-6"
                 x-data="{
        showCreate: false, showEdit: false, activeModule: null, editData: {},
        openEdit(m) {
            this.activeModule = m.id;
            this.editData = {...m};
            this.showEdit = true;
        }
    }"
            >
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-xl">All Modules</span>
                    <button @click="showCreate = true" class="bg-blue-600 text-white px-4 py-1 rounded font-semibold shadow text-sm">+ New Module</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow divide-y divide-gray-100">
                        <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <th class="py-2 px-4 text-left">Title</th>
                            <th class="py-2 px-4 text-left">Category</th>
                            <th class="py-2 px-4 text-left">Audience</th>
                            <th class="py-2 px-4 text-left">Difficulty</th>
                            <th class="py-2 px-4 text-left">Author</th>
                            <th class="py-2 px-4 text-left">Status</th>
                            <th class="py-2 px-4 text-left">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($modules as $module)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $module->title }}</td>
                                <td class="py-2 px-4">{{ $module->category->name ?? '-' }}</td>
                                <td class="py-2 px-4 capitalize">{{ $module->audience }}</td>
                                <td class="py-2 px-4">{{ $module->difficulty }}</td>
                                <td class="py-2 px-4">{{ $module->author->name ?? '-' }}</td>
                                <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $module->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $module->is_published ? 'Published' : 'Pending' }}
                        </span>
                                </td>
                                <td class="py-2 px-4 flex flex-wrap gap-2">
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
                                    <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" class="inline" onsubmit="return confirm('Delete this module?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-xs font-semibold py-1 px-2 rounded border hover:bg-red-50">Delete</button>
                                    </form>
                                    <form action="{{ route('admin.modules.publish', $module) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="text-green-600 text-xs font-semibold py-1 px-2 rounded border hover:bg-green-50">
                                            {{ $module->is_published ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Create Modal -->
                <div x-show="showCreate" x-cloak class="fixed inset-0 bg-black/40 z-40 flex items-center justify-center">
                    <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full">
                        <h2 class="font-semibold text-lg mb-3">Create Module</h2>
                        <form method="POST" action="{{ route('admin.modules.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input class="w-full border rounded p-2 mb-2" name="title" placeholder="Title" required>
                            <textarea class="w-full border rounded p-2 mb-2" name="description" placeholder="Description"></textarea>
                            <input type="file" name="thumbnail" class="w-full border rounded p-2 mb-2"/>
                            <select name="audience" class="w-full border rounded mb-2" required>
                                <option value="">— Audience —</option>
                                <option value="child">Child</option>
                                <option value="parent">Parent</option>
                            </select>
                            <select name="category_id" class="w-full border rounded mb-2" required>
                                <option value="">— Category —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <select name="difficulty" class="w-full border rounded mb-2" x-model="editData.difficulty" required>
                                <option value="">— Difficulty —</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                            </select>                                  <input class="w-full border rounded p-2 mb-2" name="age_group" placeholder="Age Group">
                            <input class="w-full border rounded p-2 mb-2" name="estimated_duration" placeholder="Estimated Duration">
                            <div class="flex gap-2 mt-3">
                                <button class="bg-blue-600 text-white py-2 px-4 rounded" type="submit">Create</button>
                                <button type="button" @click="showCreate=false" class="bg-gray-300 py-2 px-4 rounded">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Edit Modal -->
                <div x-show="showEdit" x-cloak class="fixed inset-0 bg-black/40 z-40 flex items-center justify-center">
                    <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full">
                        <h2 class="font-semibold text-lg mb-3">Edit Module</h2>
                        <form method="POST" :action="`{{ url('/admin/modules') }}/` + activeModule" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input class="w-full border rounded p-2 mb-2" name="title" placeholder="Title" required x-model="editData.title">
                            <textarea class="w-full border rounded p-2 mb-2" name="description" placeholder="Description" x-model="editData.description"></textarea>
                            <input type="file" name="thumbnail" class="w-full border rounded p-2 mb-2"/>
                            <select name="audience" class="w-full border rounded mb-2" x-model="editData.audience" required>
                                <option value="">— Audience —</option>
                                <option value="child">Child</option>
                                <option value="parent">Parent</option>
                            </select>
                            <select name="category_id" class="w-full border rounded mb-2" x-model="editData.category_id" required>
                                <option value="">— Category —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <select name="difficulty" class="w-full border rounded mb-2" x-model="editData.difficulty" required>
                                <option value="">— Difficulty —</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                            </select>                                  <input class="w-full border rounded p-2 mb-2" name="age_group" placeholder="Age Group" x-model="editData.age_group">
                            <input class="w-full border rounded p-2 mb-2" name="estimated_duration" placeholder="Estimated Duration" x-model="editData.estimated_duration">
                            <div class="flex gap-2 mt-3">
                                <button class="bg-blue-600 text-white py-2 px-4 rounded" type="submit">Update</button>
                                <button type="button" @click="showEdit=false" class="bg-gray-300 py-2 px-4 rounded">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div>



                <div x-show="tab==='reports'" class="bg-white rounded-xl shadow p-6">
                    <span class="font-bold text-xl mb-2 block">Pending Reports</span>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded shadow divide-y divide-gray-100">
                            <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                                <th class="py-2 px-4 text-left">ID</th>
                                <th class="py-2 px-4 text-left">Reporter</th>
                                <th class="py-2 px-4 text-left">Reason</th>
                                <th class="py-2 px-4 text-left">Description</th>
                                <th class="py-2 px-4 text-left">Date</th>
                                <th class="py-2 px-4 text-left">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pendingReports as $rc)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4">{{ $rc->id }}</td>
                                    <td class="py-2 px-4">{{ $rc->reporter->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ ucfirst($rc->reason) }}</td>
                                    <td class="py-2 px-4">{{ \Illuminate\Support\Str::limit($rc->description, 40) }}</td>
                                    <td class="py-2 px-4">{{ $rc->created_at->format('Y-m-d') }}</td>
                                    <td class="py-2 px-4">
                                        <button
                                            @click="
                                    resolveId = {{ $rc->id }};
                                    resolveNotes = @js($rc->admin_notes ?? '');
                                    showResolveModal = true;
                                "
                                            class="px-2 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700"
                                        >Resolve</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Resolve Modal -->
                <div
                    x-show="showResolveModal"
                    x-cloak
                    class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center"
                    @keydown.escape.window="showResolveModal = false"
                >
                    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full">
                        <h2 class="font-semibold text-lg mb-3">Resolve Report</h2>
                        <form
                            method="POST"
                            :action="`/admin/reports/${resolveId}/resolve`"
                        >
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="block text-xs font-semibold mb-1">Admin Notes</label>
                                <textarea
                                    name="admin_notes"
                                    x-model="resolveNotes"
                                    rows="3"
                                    class="w-full border rounded p-2"
                                    placeholder="Optional notes"
                                ></textarea>
                            </div>
                            <div class="flex gap-2 mt-3 justify-end">
                                <button type="button" @click="showResolveModal = false" class="bg-gray-300 rounded px-4 py-2">Cancel</button>
                                <button type="submit" class="bg-green-600 text-white rounded px-4 py-2 font-bold">Resolve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br>


            <div x-show="tab==='jobs'" class="bg-white rounded-xl shadow p-6">
                <span class="font-bold text-xl mb-2 block">Completed Jobs (User Progress)</span>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow divide-y divide-gray-100">
                        <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <th class="py-2 px-4 text-left">User</th>
                            <th class="py-2 px-4 text-left">Lesson</th>
                            <th class="py-2 px-4 text-left">Completed At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($userProgressCompleted as $up)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $up->user->name ?? '-' }}</td>
                                <td class="py-2 px-4">{{ $up->lesson->title ?? '-' }}</td>
                                <td class="py-2 px-4">{{ $up->completed_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <br>

        <!-- Optional: SVG icons via Heroicons -->
        <svg style="display:none;">
            <symbol id="heroicon-user-group" viewBox="0 0 24 24"><path d="M7.5 7.5a3 3 0 11-6 0 3 3 0 016 0zm15 0a3 3 0 11-6 0 3 3 0 016 0zM21 21v-.75A4.25 4.25 0 0016.75 16h-9.5A4.25 4.25 0 003 20.25V21m6-6a4 4 0 118-0"></path></symbol>
            <symbol id="heroicon-shield-check" viewBox="0 0 24 24"><path d="M12 22C12 22 4 18 4 8V5.38A2.375 2.375 0 017.23 3.25c2.36-.18 4.72-.18 7.08 0A2.375 2.375 0 0120 5.38V8c0 10-8 14-8 14z"></path></symbol>
            <symbol id="heroicon-book-open" viewBox="0 0 24 24"><path d="M23 19V6c0-1.1-.9-2-2-2H4C2.9 4 2 4.9 2 6v13M6 7h.01M6 19h.01M22 7h.01"></path></symbol>
            <symbol id="heroicon-exclamation-circle" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v4m0 4h.01"></path></symbol>
        </svg>
    </div>
    <!-- AI Content Generator (Admin) -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <span class="uppercase text-blue-500 text-xs font-semibold mb-2 block">AI Powered</span>
        <div class="font-bold text-lg mb-1">Generate Content with AI</div>
        <div class="text-gray-500 mb-3 text-xs">
            Instantly draft a lesson, quiz, or scenario for any module with AI.
        </div>
        <div x-data="{ tab: 'lesson' }">
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
            <form x-show="tab === 'lesson'" method="POST" action="{{ route('admin.ai.lesson.generate') }}">
                @csrf
                <div class="mb-2">
                    <label class="block text-xs font-semibold mb-1">Module</label>
                    <select name="module_id" required class="w-full border px-2 py-2 rounded">
                        <option value="">— select module —</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-xs font-semibold mb-1">Lesson Topic</label>
                    <input type="text" name="topic" class="w-full border px-2 py-2 rounded" placeholder="e.g. Phishing, Privacy" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded mb-2 w-full">✨ Generate Lesson</button>
            </form>
            <!-- Quiz Generator -->
            <form x-show="tab === 'quiz'" method="POST" action="{{ route('admin.ai.quiz.generate') }}">
                @csrf
                <div class="mb-2">
                    <label class="block text-xs font-semibold mb-1">Lesson</label>
                    <select name="lesson_id" required class="w-full border px-2 py-2 rounded">
                        <option value="">— select lesson —</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-xs font-semibold mb-1">Quiz Topic</label>
                    <input type="text" name="topic" class="w-full border px-2 py-2 rounded" placeholder="e.g. Password Safety" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded mb-2 w-full">✨ Generate Quiz</button>
            </form>
            <!-- Scenario Generator -->
            <form x-show="tab === 'scenario'" method="POST" action="{{ route('admin.ai.scenario.generate') }}">
                @csrf
                <div class="mb-2">
                    <label class="block text-xs font-semibold mb-1">Lesson</label>
                    <select name="lesson_id" required class="w-full border px-2 py-2 rounded">
                        <option value="">— select lesson —</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-xs font-semibold mb-1">Scenario Topic</label>
                    <input type="text" name="topic" class="w-full border px-2 py-2 rounded" placeholder="e.g. Wi-Fi Safety" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded mb-2 w-full">✨ Generate Scenario</button>
            </form>
            <div class="text-xs text-gray-400 mt-3">
                * AI models generate content based on the latest NCSC and cybersecurity curriculum standards.
            </div>
        </div>
    </div>

    <!-- Pending AI Suggestions (Admin) -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
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
    <!-- Assign Role Modal -->
    <div
        id="role-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 relative">
            <button
                type="button"
                class="absolute top-2 right-2 text-slate-500 hover:text-slate-700"
                onclick="closeRoleModal()"
            >
                ✕
            </button>

            <h2 class="text-lg font-semibold mb-4">
                Assign role to <span id="role-modal-user-name" class="font-bold"></span>
            </h2>

            <form id="role-modal-form" method="POST">
                @csrf

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Role
                </label>
                <select
                    name="role"
                    class="w-full border rounded px-2 py-1 text-sm mb-4"
                    required
                >
                    <option value="">Select role</option>
                    <option value="child">Child</option>
                    <option value="parent">Parent</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        class="px-3 py-1 text-sm rounded border border-slate-300 text-slate-700"
                        onclick="closeRoleModal()"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-3 py-1 text-sm rounded bg-blue-600 text-white hover:bg-blue-700"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRoleModal(userId, userName) {
            const modal = document.getElementById('role-modal');
            const form  = document.getElementById('role-modal-form');
            const name  = document.getElementById('role-modal-user-name');

            name.textContent = userName;

            // /admin/users/{user}/role
            form.action = "{{ url('admin/users') }}/" + userId + "/role";

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRoleModal() {
            const modal = document.getElementById('role-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

@endsection
