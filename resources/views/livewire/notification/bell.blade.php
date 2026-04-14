<div wire:poll.5s x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative text-gray-400 hover:text-gray-600 transition-colors p-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">{{ $unreadCount }}</span>
        @else
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-gray-300 rounded-full"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-100 z-50">
        <div class="p-3">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-bold">Notifications</h4>
                <a href="{{ route('notifications.index') }}" class="text-xs text-blue-500">View all</a>
            </div>

            @if(count($notifications) > 0)
                <ul class="space-y-2">
                    @foreach($notifications as $note)
                        <li class="p-2 rounded hover:bg-gray-50 flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <button wire:click="openNotification({{ $note['id'] }}, '{{ $note['action_url'] ?? route('notifications.index') }}')" class="text-sm font-semibold text-gray-800 block text-left w-full">
                                    {{ $note['title'] }}
                                </button>
                                <p class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($note['message'], 80) }}</p>
                                <p class="text-[10px] text-gray-300 mt-1">{{ $note['created_at_human'] ?? '' }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <button wire:click="markAsRead({{ $note['id'] }})" class="text-xs text-blue-600">Mark</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-4 text-center text-sm text-gray-500">No new notifications</div>
            @endif
        </div>
    </div>
</div>
<script>
    window.addEventListener('notification-redirect', function(e) {
        if (e.detail && e.detail.url) {
            window.location.href = e.detail.url;
        }
    });
</script>
