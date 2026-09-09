<x-layouts.admin title="Dashboard">
    <p class="label-meta">Overview</p>
    <h1 class="mt-2 text-3xl font-bold">Build log</h1>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
            ['label' => 'Projects', 'value' => $projectCount, 'meta' => $publishedCount.' published'],
            ['label' => 'Messages', 'value' => $unreadCount, 'meta' => 'unread'],
            ['label' => 'Experience', 'value' => $experienceCount, 'meta' => 'roles'],
            ['label' => 'Skills', 'value' => $skillCount, 'meta' => 'in toolkit'],
            ['label' => 'Education', 'value' => $educationCount, 'meta' => 'records'],
        ] as $stat)
            <div class="border border-line bg-surface px-5 py-4">
                <p class="label-meta">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-bold">{{ $stat['value'] }}</p>
                <p class="mt-1 text-sm text-subtle">{{ $stat['meta'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-12">
        <p class="label-meta mb-4">Recent messages</p>
        @forelse ($recentMessages as $message)
            <a href="{{ route('admin.messages.show', $message) }}" class="flex items-center justify-between gap-4 border-t border-line py-4">
                <div>
                    <p class="font-medium">{{ $message->subject }}</p>
                    <p class="text-sm text-subtle">{{ $message->name }} · {{ $message->email }}</p>
                </div>
                <span class="label-meta">{{ $message->isRead() ? 'Read' : 'New' }}</span>
            </a>
        @empty
            <p class="text-sm text-subtle">No messages yet.</p>
        @endforelse
    </div>
</x-layouts.admin>
