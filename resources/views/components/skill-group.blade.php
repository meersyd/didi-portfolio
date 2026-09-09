@props(['category', 'skills'])

<div {{ $attributes->class('skill-col') }}>
    <p class="label-meta mb-4">{{ $category }}</p>
    <ul class="space-y-2">
        @foreach ($skills as $skill)
            <li class="text-[0.95rem] text-ink">{{ $skill->name }}</li>
        @endforeach
    </ul>
</div>
