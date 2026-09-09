@php $editing = $skill->exists; @endphp

<x-layouts.admin :title="$editing ? 'Edit skill' : 'New skill'">
    <p class="label-meta">Toolkit</p>
    <h1 class="mt-2 text-3xl font-bold">{{ $editing ? 'Edit skill' : 'New skill' }}</h1>
    <form
        method="POST"
        action="{{ $editing ? route('admin.skills.update', $skill) : route('admin.skills.store') }}"
        class="mt-8 grid max-w-lg gap-4"
    >
        @csrf
        @if ($editing)
            @method('PUT')
        @endif
        @if ($errors->any())
            <ul class="border border-line px-4 py-3 text-sm" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Name</span>
            <input class="field" name="name" value="{{ old('name', $skill->name) }}" required>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Category</span>
            <input class="field" name="category" value="{{ old('category', $skill->category) }}" required>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Sort order</span>
            <input class="field" type="number" min="0" name="sort_order" value="{{ old('sort_order', $skill->sort_order ?? 0) }}" required>
        </label>
        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <x-button href="{{ route('admin.skills.index') }}" variant="ghost">Cancel</x-button>
        </div>
    </form>
</x-layouts.admin>
