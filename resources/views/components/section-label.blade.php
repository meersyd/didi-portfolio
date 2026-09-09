@props(['number', 'title' => null])

<div {{ $attributes->class('mb-6') }}>
    <p class="label-meta">{{ $number }}</p>
    @if ($title)
        <p class="mt-2 text-sm font-medium text-subtle">{{ $title }}</p>
    @endif
</div>
