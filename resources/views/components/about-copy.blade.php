@props([
    'heading' => 'h2',
])

@php
    $about = $site['about'] ?? ['heading' => null, 'paragraphs' => []];
@endphp

@if (filled($about['heading']))
    @if ($heading === 'h1')
        <h1 class="text-4xl font-bold sm:text-5xl">{{ $about['heading'] }}</h1>
    @else
        <h2 class="text-4xl font-bold sm:text-5xl">{{ $about['heading'] }}</h2>
    @endif
@endif
@if (! empty($about['paragraphs']))
    <div class="mt-6 space-y-4 text-[0.98rem] leading-relaxed text-subtle">
        @foreach ($about['paragraphs'] as $paragraph)
            <p>{!! \App\Support\RichText::inline($paragraph) !!}</p>
        @endforeach
    </div>
@endif
