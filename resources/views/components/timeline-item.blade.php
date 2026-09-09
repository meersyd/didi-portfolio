@props(['experience'])

<article {{ $attributes->class('timeline-item') }}>
    <span class="timeline-dot" aria-hidden="true"></span>
    <p class="label-meta timeline-period mb-3 md:mb-0">{{ $experience->periodLabel() }}</p>
    <div>
        <h3 class="text-xl font-bold tracking-tight sm:text-2xl">{{ $experience->company }}</h3>
        <p class="mt-1 text-sm text-subtle">{{ $experience->role }}</p>
        @if ($experience->description)
            <p class="mt-3 max-w-xl text-[0.95rem] leading-relaxed text-subtle">
                {{ $experience->description }}
            </p>
        @endif
        @if (! empty($experience->technologies))
            <p class="mt-4 font-mono text-xs tracking-wide text-muted">
                {{ implode(' · ', $experience->technologies) }}
            </p>
        @endif
    </div>
</article>
