<x-layouts.admin title="Skills">
    <div class="flex items-end justify-between gap-4">
        <div>
            <p class="label-meta">Toolkit</p>
            <h1 class="mt-2 text-3xl font-bold">Skills</h1>
        </div>
        <x-button href="{{ route('admin.skills.create') }}">New skill</x-button>
    </div>
    <div class="mt-8 grid gap-8 sm:grid-cols-2">
        @forelse ($skills as $category => $group)
            <div>
                <p class="label-meta mb-3">{{ $category }}</p>
                @foreach ($group as $skill)
                    <div class="flex items-center justify-between border-t border-line py-3 text-sm">
                        <span>{{ $skill->name }}</span>
                        <span class="flex gap-3">
                            <a href="{{ route('admin.skills.edit', $skill) }}" class="text-subtle">Edit</a>
                            <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" onsubmit="return confirm('Delete this skill?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-subtle">Delete</button>
                            </form>
                        </span>
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-sm text-subtle">No skills yet.</p>
        @endforelse
    </div>
</x-layouts.admin>
