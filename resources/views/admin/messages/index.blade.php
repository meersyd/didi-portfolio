<x-layouts.admin title="Messages">
    <p class="label-meta">Inbox</p>
    <h1 class="mt-2 text-3xl font-bold">Contact messages</h1>
    <div class="mt-8 border-t border-line">
        @forelse ($messages as $message)
            <a href="{{ route('admin.messages.show', $message) }}" class="flex items-center justify-between gap-4 border-b border-line py-4">
                <div>
                    <p class="font-medium">{{ $message->subject }}</p>
                    <p class="text-sm text-subtle">{{ $message->name }} · {{ $message->created_at->diffForHumans() }}</p>
                </div>
                <span class="label-meta">{{ $message->isRead() ? 'Read' : 'New' }}</span>
            </a>
        @empty
            <p class="py-8 text-sm text-subtle">No messages yet.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $messages->links() }}</div>
</x-layouts.admin>
