@props(['layers' => []])

<div {{ $attributes->class('overflow-hidden border border-line bg-surface') }}>
    <div class="flex items-center justify-between border-b border-line px-4 py-2">
        <p class="label-meta">Architecture</p>
        <x-logo class="h-4 w-4" decorative />
    </div>
    <div class="grid gap-3 p-5 sm:grid-cols-3 sm:items-center">
        @foreach ($layers as $index => $layer)
            <div class="border border-line px-4 py-4">
                <p class="label-meta mb-2">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm font-semibold">{{ $layer['title'] }}</p>
                <p class="mt-1 text-sm text-subtle">{{ $layer['detail'] }}</p>
            </div>
        @endforeach
    </div>
</div>
