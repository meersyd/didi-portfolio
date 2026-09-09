<x-layouts.admin title="Message">
    <p class="label-meta">{{ $message->created_at->toDayDateTimeString() }}</p>
    <h1 class="mt-2 text-3xl font-bold">{{ $message->subject }}</h1>
    <p class="mt-3 text-sm text-subtle">{{ $message->name }} · {{ $message->email }}</p>
    <p class="mt-8 max-w-2xl whitespace-pre-wrap text-[0.98rem] leading-relaxed">{{ $message->message }}</p>
    <div class="mt-8 flex gap-4">
        @unless ($message->isRead())
            <form method="POST" action="{{ route('admin.messages.update', $message) }}">
                @csrf
                @method('PATCH')
                <x-button type="submit" variant="ghost">Mark as read</x-button>
            </form>
        @endunless
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-subtle">Delete</button>
        </form>
        <a href="{{ route('admin.messages.index') }}" class="text-sm text-subtle">Back</a>
    </div>
</x-layouts.admin>
