@props(['title', 'label' => null])

<div {{ $attributes->class('flex aspect-[16/9] items-end border border-line bg-canvas p-5') }}>
    <div>
        @if ($label)
            <p class="label-meta mb-2">{{ $label }}</p>
        @endif
        <p class="text-lg font-semibold tracking-tight">{{ $title }}</p>
        <p class="mt-1 text-sm text-muted">Visual still in progress</p>
    </div>
</div>
