@props([
    'decorative' => false,
])

<span
    {{ $attributes->class('logo-d') }}
    @if ($decorative)
        aria-hidden="true"
    @else
        role="img"
        aria-label="D"
    @endif
>
    <img
        src="{{ asset('assets/brand/logo-light.png') }}"
        alt=""
        width="1031"
        height="1031"
        class="dark:hidden"
        decoding="async"
    >
    <img
        src="{{ asset('assets/brand/logo-dark.png') }}"
        alt=""
        width="1031"
        height="1031"
        class="hidden dark:block"
        decoding="async"
    >
</span>
