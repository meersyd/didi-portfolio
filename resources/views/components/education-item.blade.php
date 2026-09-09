@props(['record'])

<article {{ $attributes->class('timeline-item') }}>
    <span class="timeline-dot" aria-hidden="true"></span>
    <p class="label-meta timeline-period mb-3 md:mb-0">{{ $record->periodLabel() }}</p>
    <div>
        <h3 class="text-xl font-bold tracking-tight sm:text-2xl">{{ $record->degree }}</h3>
        <p class="mt-1 text-sm text-subtle">{{ $record->institution }}</p>
        @if ($record->field)
            <p class="mt-1 text-sm text-subtle">{{ $record->field }}</p>
        @endif
        @if ($record->description)
            <p class="mt-3 max-w-xl text-[0.95rem] leading-relaxed text-subtle">
                {{ $record->description }}
            </p>
        @endif
    </div>
</article>
