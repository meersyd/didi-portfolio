@php $editing = $record->exists; @endphp

<x-layouts.admin :title="$editing ? 'Edit education' : 'New education'">
    <p class="label-meta">Education</p>
    <h1 class="mt-2 text-3xl font-bold">{{ $editing ? 'Edit record' : 'New record' }}</h1>
    <form
        method="POST"
        action="{{ $editing ? route('admin.education.update', $record) : route('admin.education.store') }}"
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
            <span class="label-meta">Institution</span>
            <input class="field" name="institution" value="{{ old('institution', $record->institution) }}" required>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Degree</span>
            <input class="field" name="degree" value="{{ old('degree', $record->degree) }}" required>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Field</span>
            <input class="field" name="field" value="{{ old('field', $record->field) }}">
        </label>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="grid gap-2 text-sm">
                <span class="label-meta">Start year</span>
                <input class="field" type="number" name="start_year" value="{{ old('start_year', $record->start_year) }}" required>
            </label>
            <label class="grid gap-2 text-sm">
                <span class="label-meta">End year</span>
                <input class="field" type="number" name="end_year" value="{{ old('end_year', $record->end_year) }}">
            </label>
        </div>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Description</span>
            <textarea class="field" name="description">{{ old('description', $record->description) }}</textarea>
        </label>
        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <x-button href="{{ route('admin.education.index') }}" variant="ghost">Cancel</x-button>
        </div>
    </form>
</x-layouts.admin>
