@props([
    'href' => '#',
    'variant' => 'primary',
    'external' => false,
    'type' => null,
])

@php
    $classes = $variant === 'ghost' ? 'btn btn-ghost' : 'btn btn-primary';
    $isButton = $type !== null || $attributes->has('type');
@endphp

@if ($isButton)
    <button type="{{ $type ?? 'button' }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@else
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if ($external) target="_blank" rel="noopener noreferrer" @endif
    >
        {{ $slot }}
    </a>
@endif
