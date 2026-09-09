@php
    use App\Support\TextList;
    $editing = $experience->exists;
@endphp

<x-layouts.admin :title="$editing ? 'Edit experience' : 'New experience'">
    <p class="label-meta">Experience</p>
    <h1 class="mt-2 text-3xl font-bold">{{ $editing ? 'Edit role' : 'New role' }}</h1>

    <form
        method="POST"
        action="{{ $editing ? route('admin.experiences.update', $experience) : route('admin.experiences.store') }}"
        class="mt-8 grid max-w-2xl gap-4"
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
            <span class="label-meta">Company</span>
            <input class="field" name="company" value="{{ old('company', $experience->company) }}" required>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Role</span>
            <input class="field" name="role" value="{{ old('role', $experience->role) }}" required>
        </label>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="grid gap-2 text-sm">
                <span class="label-meta">Start date</span>
                <input class="field" type="date" name="start_date" value="{{ old('start_date', optional($experience->start_date)->format('Y-m-d')) }}" required>
            </label>
            <label class="grid gap-2 text-sm">
                <span class="label-meta">End date</span>
                <input class="field" type="date" name="end_date" value="{{ old('end_date', optional($experience->end_date)->format('Y-m-d')) }}">
            </label>
        </div>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Description</span>
            <textarea class="field" name="description">{{ old('description', $experience->description) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Technologies</span>
            <textarea class="field" name="technologies">{{ old('technologies', TextList::toText($experience->technologies)) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Sort order</span>
            <input class="field" type="number" min="0" name="sort_order" value="{{ old('sort_order', $experience->sort_order ?? 0) }}" required>
        </label>
        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <x-button href="{{ route('admin.experiences.index') }}" variant="ghost">Cancel</x-button>
        </div>
    </form>
</x-layouts.admin>
