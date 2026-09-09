@props(['project', 'index'])

@php
    $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
    $stack = collect($project->technologies ?? [])->take(3)->implode(' · ');
    $preview = $project->previewUrl();
@endphp

<a
    href="{{ route('projects.show', $project) }}"
    {{ $attributes->class(['project-row', 'group']) }}
>
    <span class="project-index">{{ $number }}</span>
    <div class="min-w-0">
        @if (filled($project->category))
            <p class="label-meta mb-2">{{ $project->category }}</p>
        @endif
        <h3 class="text-3xl font-bold tracking-tight sm:text-4xl">{{ $project->title }}</h3>
        @if (filled($project->short_description))
            <p class="mt-3 max-w-xl text-[0.95rem] leading-relaxed text-subtle">
                {{ $project->short_description }}
            </p>
        @endif
        @if ($stack)
            <p class="mt-4 font-mono text-xs tracking-wide text-muted">{{ $stack }}</p>
        @endif
    </div>
    <div class="project-thumb {{ $project->isCompactPreview() ? 'is-phone' : '' }}">
        @if ($preview)
            <img src="{{ $preview }}" alt="{{ $project->title }} preview" loading="lazy">
        @else
            <div class="project-thumb-empty">
                <span class="label-meta">{{ $project->title }}</span>
            </div>
        @endif
    </div>
    <div class="flex items-center justify-between gap-4 md:justify-end md:pb-1">
        <span class="label-meta text-ink">View</span>
        <x-icon name="arrow-right" class="project-arrow h-4 w-4" />
    </div>
</a>
