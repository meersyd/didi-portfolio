@props(['embed', 'title' => 'Walkthrough'])

@if ($embed)
    <section class="mt-10">
        <h2 class="label-meta mb-4">{{ $title }}</h2>
        <div class="project-video">
            @if (($embed['type'] ?? '') === 'iframe')
                <iframe
                    src="{{ $embed['src'] }}"
                    title="{{ $title }}"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
            @else
                <video src="{{ $embed['src'] }}" controls playsinline preload="metadata"></video>
            @endif
        </div>
    </section>
@endif
