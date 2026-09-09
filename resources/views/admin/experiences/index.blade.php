<x-layouts.admin title="Experience">
    <div class="flex items-end justify-between gap-4">
        <div>
            <p class="label-meta">Experience</p>
            <h1 class="mt-2 text-3xl font-bold">Where I've worked</h1>
        </div>
        <x-button href="{{ route('admin.experiences.create') }}">New role</x-button>
    </div>
    <div class="mt-8 border-t border-line">
        @forelse ($experiences as $experience)
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line py-4">
                <div>
                    <p class="font-medium">{{ $experience->company }}</p>
                    <p class="text-sm text-subtle">{{ $experience->role }} · {{ $experience->periodLabel() }}</p>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('admin.experiences.edit', $experience) }}" class="text-subtle">Edit</a>
                    <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}" onsubmit="return confirm('Delete this experience?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-subtle">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="py-8 text-sm text-subtle">No experience records yet.</p>
        @endforelse
    </div>
</x-layouts.admin>
