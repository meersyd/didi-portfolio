<x-layouts.admin title="Education">
    <div class="flex items-end justify-between gap-4">
        <div>
            <p class="label-meta">Education</p>
            <h1 class="mt-2 text-3xl font-bold">Records</h1>
        </div>
        <x-button href="{{ route('admin.education.create') }}">New record</x-button>
    </div>
    <div class="mt-8 border-t border-line">
        @forelse ($records as $record)
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line py-4">
                <div>
                    <p class="font-medium">{{ $record->institution }}</p>
                    <p class="text-sm text-subtle">{{ $record->degree }} · {{ $record->periodLabel() }}</p>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('admin.education.edit', $record) }}" class="text-subtle">Edit</a>
                    <form method="POST" action="{{ route('admin.education.destroy', $record) }}" onsubmit="return confirm('Delete this record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-subtle">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="py-8 text-sm text-subtle">No education records yet.</p>
        @endforelse
    </div>
</x-layouts.admin>
