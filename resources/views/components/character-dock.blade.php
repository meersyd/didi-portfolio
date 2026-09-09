@props([
    'home' => false,
])

<div
    {{ $attributes->class(['character-dock']) }}
    x-data="characterDock(@js($home))"
    x-show="on"
    x-cloak
    x-bind:class="{ 'is-placed': placed, 'is-minimized': minimized, 'is-dragging': dragging }"
    x-bind:style="dockStyle"
    role="complementary"
    aria-label="Mirza, the site sprite"
>
    <div class="actor-window" x-show="! minimized">
        <div class="actor-window-bar" @pointerdown="startDrag($event)">
            <span class="actor-window-grip" aria-hidden="true">::</span>
            <span>mirza.exe</span>
            <button
                type="button"
                class="actor-window-btn"
                aria-label="Minimize character"
                title="Minimize"
                @pointerdown.stop
                @click.stop="minimize()"
            >−</button>
        </div>
        <x-character pose="idle" size="sm" :reactive="true" :clickable="true" />
    </div>

    <button
        type="button"
        class="character-dock-chip"
        x-show="minimized"
        x-cloak
        aria-label="Show character"
        title="Drag or tap to restore"
        @pointerdown="startDrag($event)"
        @click="expand()"
    >
        <span class="actor-window-grip" aria-hidden="true">::</span>
        mirza.exe
    </button>
</div>
