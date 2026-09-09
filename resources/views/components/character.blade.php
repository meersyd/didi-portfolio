@props([
    'pose' => 'idle',
    'reactive' => false,
    'cycle' => false,
    'clickable' => false,
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'max-w-[9.5rem]',
        'md' => 'max-w-[14rem]',
        'lg' => 'max-w-[18rem]',
        'xl' => 'max-w-[22rem]',
    ];
    $animated = $reactive || $cycle || $clickable;
    $poseClass = $animated ? '' : 'character-'.$pose;
    $interactive = $clickable || $cycle;
@endphp

<div
    {{ $attributes->class(['character-stage', $sizes[$size] ?? $sizes['md'], $poseClass, $interactive ? 'cursor-pointer' : '']) }}
    data-pose="{{ $pose }}"
    @if ($animated)
        x-data="characterActor(@js($pose), @js((bool) $reactive), @js((bool) $clickable), @js((bool) $cycle))"
        :data-pose="pose"
    @endif
    @if ($interactive)
        role="button"
        tabindex="0"
        aria-label="Poke Mirza to change pose"
        @click="poke()"
        @keydown.enter.prevent="poke()"
        @keydown.space.prevent="poke()"
    @endif
>
    <div class="character-frame w-full">
        <svg
            class="character"
            viewBox="0 0 160 220"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            role="img"
            aria-label="Pixel character representing Mirza"
        >
            <g class="character-root">
                <g class="character-sparks" aria-hidden="true">
                    <rect class="laptop-glow spark" x="22" y="36" width="6" height="6" />
                    <rect class="laptop-glow spark" x="132" y="28" width="6" height="6" />
                    <rect class="wear-accent spark" x="18" y="64" width="4" height="4" />
                    <rect class="wear-accent spark" x="140" y="58" width="4" height="4" />
                </g>

                <g class="character-hello">
                    <rect x="106" y="4" width="50" height="28" fill="var(--ink)" />
                    <rect x="108" y="6" width="46" height="24" fill="var(--surface)" />
                    <rect x="100" y="18" width="10" height="8" fill="var(--ink)" />
                    <rect x="102" y="18" width="8" height="6" fill="var(--surface)" />
                    <text
                        x="131"
                        y="22"
                        text-anchor="middle"
                        font-family="IBM Plex Mono, ui-monospace, monospace"
                        font-size="9"
                        font-weight="500"
                        fill="var(--ink)"
                    >Hello!</text>
                </g>

                <g class="character-desk">
                    <rect x="24" y="168" width="112" height="8" fill="currentColor" class="text-line" />
                    <rect x="28" y="176" width="8" height="24" fill="currentColor" class="text-line" />
                    <rect x="124" y="176" width="8" height="24" fill="currentColor" class="text-line" />
                </g>

                <g class="leg-left">
                    <rect class="wear-pants" x="60" y="148" width="16" height="36" />
                    <rect class="wear-shoes" x="56" y="180" width="24" height="8" />
                </g>
                <g class="leg-right">
                    <rect class="wear-pants" x="84" y="148" width="16" height="36" />
                    <rect class="wear-shoes" x="80" y="180" width="24" height="8" />
                </g>

                <g class="character-sit">
                    <rect class="wear-pants" x="48" y="150" width="32" height="14" />
                    <rect class="wear-pants" x="80" y="150" width="32" height="14" />
                    <rect class="wear-pants" x="44" y="164" width="14" height="20" />
                    <rect class="wear-pants" x="102" y="164" width="14" height="20" />
                    <rect class="wear-shoes" x="40" y="180" width="22" height="8" />
                    <rect class="wear-shoes" x="98" y="180" width="22" height="8" />
                </g>

                <g class="character-body">
                    <rect class="wear-main" x="52" y="92" width="56" height="56" />
                    <rect class="wear-alt" x="68" y="100" width="24" height="40" />
                    <rect class="wear-main" x="52" y="88" width="16" height="12" />
                    <rect class="wear-main" x="92" y="88" width="16" height="12" />
                    <rect class="wear-alt" x="68" y="88" width="24" height="12" />
                    <rect class="wear-accent" x="52" y="144" width="56" height="4" />
                    <rect class="wear-alt" x="56" y="116" width="12" height="10" />
                    <rect class="wear-accent" x="58" y="118" width="8" height="2" />
                </g>

                <g class="arm-left">
                    <rect class="wear-main" x="40" y="96" width="16" height="36" />
                    <rect class="wear-alt" x="40" y="128" width="16" height="8" />
                    <rect x="40" y="136" width="16" height="12" fill="#C9936A" />
                </g>
                <g class="arm-right">
                    <rect class="wear-main" x="104" y="96" width="16" height="36" />
                    <rect class="wear-alt" x="104" y="128" width="16" height="8" />
                    <rect x="104" y="136" width="16" height="12" fill="#C9936A" />
                </g>

                <g class="arm-wave">
                    <rect class="wear-main" x="104" y="96" width="32" height="16" />
                    <rect class="wear-main" x="120" y="52" width="16" height="48" />
                    <rect class="wear-alt" x="120" y="48" width="16" height="8" />
                    <rect x="116" y="36" width="20" height="16" fill="#C9936A" />
                </g>

                <g class="character-laptop">
                    <rect x="112" y="108" width="16" height="12" fill="#C9936A" />
                    <rect class="laptop-shell" x="122" y="86" width="30" height="38" />
                    <rect class="laptop-screen" x="124" y="88" width="26" height="28" />
                    <rect class="laptop-glow" x="127" y="94" width="6" height="6" />
                    <rect class="wear-accent" x="127" y="104" width="16" height="3" />
                    <rect class="laptop-glow" x="127" y="110" width="10" height="3" />
                    <rect class="laptop-shell" x="122" y="116" width="30" height="8" />
                </g>

                <g class="character-phone">
                    <rect x="112" y="108" width="16" height="12" fill="#C9936A" />
                    <rect x="124" y="96" width="16" height="28" fill="#111413" />
                    <rect class="laptop-glow" x="127" y="100" width="10" height="16" />
                    <rect x="130" y="120" width="4" height="2" fill="#6b7280" />
                </g>

                <g class="character-book">
                    <rect x="112" y="108" width="16" height="12" fill="#C9936A" />
                    <rect x="122" y="88" width="28" height="40" fill="#FFFFFF" />
                    <rect x="124" y="90" width="24" height="36" fill="#FFF8EC" />
                    <rect x="135" y="90" width="3" height="36" fill="#6C63FF" />
                    <rect x="128" y="98" width="14" height="2" fill="#1C1714" />
                    <rect x="128" y="104" width="10" height="2" fill="#1C1714" />
                    <rect x="128" y="110" width="14" height="2" fill="#1C1714" />
                </g>

                <g class="character-head">
                    <rect x="56" y="28" width="48" height="16" fill="#1C1714" />
                    <rect x="52" y="36" width="56" height="12" fill="#1C1714" />
                    <rect x="60" y="24" width="32" height="8" fill="#1C1714" />
                    <rect x="56" y="44" width="48" height="40" fill="#C9936A" />
                    <rect x="52" y="48" width="8" height="28" fill="#C9936A" />
                    <rect x="100" y="48" width="8" height="28" fill="#B07D56" />
                    <g class="character-eyes">
                        <rect x="68" y="56" width="8" height="8" fill="#111111" />
                        <rect x="84" y="56" width="8" height="8" fill="#111111" />
                        <rect x="70" y="56" width="3" height="3" fill="#F7F7F5" />
                        <rect x="86" y="56" width="3" height="3" fill="#F7F7F5" />
                    </g>
                    <g class="character-smile">
                        <rect x="72" y="72" width="4" height="3" fill="#A56B4A" />
                        <rect x="76" y="74" width="8" height="3" fill="#A56B4A" />
                        <rect x="84" y="72" width="4" height="3" fill="#A56B4A" />
                    </g>
                </g>
            </g>
        </svg>

        <span class="character-badge character-badge-think" aria-hidden="true">💡</span>
        <span class="character-badge character-badge-call" aria-hidden="true">💬</span>
    </div>
</div>
