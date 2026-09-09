@props(['images'])

@php
    $items = collect($images)->filter()->values();
@endphp

@if ($items->isNotEmpty())
    <section class="mt-16">
        <div class="mb-6 flex items-end justify-between gap-4">
            <h2 class="label-meta">Screens</h2>
            @if ($items->count() > 1)
                <p class="label-meta">Scroll</p>
            @endif
        </div>
        <div class="project-gallery" tabindex="0" aria-label="Project screenshots">
            @foreach ($items as $src)
                <figure class="project-gallery-item">
                    <img
                        src="{{ $src }}"
                        alt="Screenshot {{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}"
                        loading="lazy"
                    >
                </figure>
            @endforeach
        </div>
    </section>
@endif
