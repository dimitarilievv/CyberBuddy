<li class="bg-white shadow p-4 rounded flex justify-between items-start">
    <div class="pr-4">
        <p class="font-semibold">
            {{ $notification->title }}
            @if(!$notification->is_read)
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded ml-2">Unread</span>
            @endif
        </p>

        <p class="text-sm text-gray-600 mt-1">
            {{ $notification->message }}
        </p>

        <p class="text-xs text-gray-500 mt-2">
            {{ optional($notification->created_at)->format('d M Y H:i') }}
        </p>

        @if(!empty($notification->action_url))
            <a href="{{ $notification->action_url }}" class="text-sm text-blue-600 underline mt-2 inline-block">
                Open
            </a>
        @endif
    </div>

    <div class="flex flex-col gap-2 items-end">
        @if(!$notification->is_read)
            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                    Mark as read
                </button>
            </form>
        @endif

        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}"
              onsubmit="return confirm('Delete this notification?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">
                Delete
            </button>
        </form>
    </div>
</li>
