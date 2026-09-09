<x-layouts.admin title="Projects">
    <div class="flex items-end justify-between gap-4">
        <div>
            <p class="label-meta">Projects</p>
            <h1 class="mt-2 text-3xl font-bold">Selected work</h1>
        </div>
        <x-button href="{{ route('admin.projects.create') }}">New project</x-button>
    </div>

    <div class="mt-8 border-t border-line">
        @forelse ($projects as $project)
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line py-4">
                <div>
                    <p class="font-medium">{{ $project->title }}</p>
                    <p class="text-sm text-subtle">{{ $project->category }} · {{ $project->published ? 'Published' : 'Draft' }}</p>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('admin.projects.edit', $project) }}" class="text-subtle">Edit</a>
                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-subtle">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="py-8 text-sm text-subtle">No projects yet.</p>
        @endforelse
    </div>
</x-layouts.admin>
