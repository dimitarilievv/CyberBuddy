<x-admin-layout>
    <h1 class="text-2xl font-bold mb-4">Users</h1>

    @if (session('status'))
        <div class="mb-4 text-green-600">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100">
            <tr>
                <th class="px-3 py-2 text-left">ID</th>
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Email</th>
                <th class="px-3 py-2 text-left">Current Role</th>
                <th class="px-3 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                @php
                    $currentRole = method_exists($user, 'roles')
                        ? $user->roles->pluck('name')->implode(', ')
                        : ($user->role ?? '');
                @endphp
                <tr class="border-t">
                    <td class="px-3 py-2">{{ $user->id }}</td>
                    <td class="px-3 py-2">{{ $user->name }}</td>
                    <td class="px-3 py-2">{{ $user->email }}</td>
                    <td class="px-3 py-2">
                        {{ $currentRole ?: '— none —' }}
                    </td>
                    <td class="px-3 py-2">
                        <!-- Button opens the modal -->
                        <button
                            type="button"
                            class="text-xs px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
                            onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')"
                        >
                            Assign role
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="p-3">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal -->
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

            // Set form action dynamically: /admin/users/{user}/role
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
</x-admin-layout>
