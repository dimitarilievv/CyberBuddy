<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">🔔 My Notifications</h1>
                <p class="text-slate-600 mt-2">You have <span class="font-bold text-cyan-600">{{ $unreadCount }}</span> unread</p>
            </div>

            @if($unreadCount > 0)
                <button
                    wire:click="markAllAsRead"
                    class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold px-6 py-3 rounded-lg transition"
                >
                    ✓ Mark all as read
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition border-l-4 {{ !$notification->read_at ? 'border-cyan-500 bg-cyan-50' : 'border-slate-300' }}">
                        <div class="p-6 flex justify-between items-start gap-4">
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-bold text-slate-900">
                                        {{ $notification->title ?? 'Notification' }}
                                    </h3>
                                    @if(!$notification->read_at)
                                        <span class="inline-block w-3 h-3 rounded-full bg-cyan-500"></span>
                                    @endif
                                </div>

                                <p class="text-slate-700 mb-3">
                                    {{ $notification->message ?? 'No message' }}
                                </p>

                                @if(isset($notification->data['details']))
                                    <div class="bg-slate-100 rounded p-3 mb-3 text-sm text-slate-600">
                                        {{ $notification->data['details'] }}
                                    </div>
                                @endif

                                <p class="text-sm text-slate-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 flex-shrink-0">
                                @if(!$notification->read_at)
                                    <button
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition text-sm"
                                    >
                                        ✓ Read
                                    </button>
                                @endif

                                <button
                                    wire:click="delete('{{ $notification->id }}')"
                                    onclick="confirm('Delete this notification?') || event.stopImmediatePropagation()"
                                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition text-sm"
                                >
                                    🗑️ Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>

            <!-- Delete All -->
            <div class="mt-8 text-center">
                <button
                    wire:click="deleteAll"
                    onclick="confirm('Delete all notifications?') || event.stopImmediatePropagation()"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg transition"
                >
                    🗑️ Delete All Notifications
                </button>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">📭</div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">No Notifications</h2>
                <p class="text-slate-600">You're all caught up! Check back later for updates.</p>
            </div>
        @endif
    </div>
</div>
