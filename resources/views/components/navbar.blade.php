@php
    $links = [
        ['label' => 'Work', 'url' => route('home').'#work', 'active' => request()->routeIs('projects.*')],
        ['label' => 'About', 'url' => route('about'), 'active' => request()->routeIs('about')],
        ['label' => 'Contact', 'url' => route('contact'), 'active' => request()->routeIs('contact')],
    ];
@endphp

<header
    x-data="navbar"
    x-bind:class="scrolled || open ? 'is-scrolled' : ''"
    class="nav-shell"
>
    <div class="site-wrap flex items-center justify-between gap-4 py-3">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex text-ink" aria-label="{{ $site['name'] }}">
                <x-logo class="h-10 w-10" />
            </a>
            <span class="inline-flex items-center gap-2">
                <span class="status-dot {{ ($presence['online'] ?? false) ? 'is-online' : 'is-offline' }}" aria-hidden="true"></span>
                <span class="label-meta text-ink">{{ $presence['label'] ?? 'Online' }}</span>
            </span>
        </div>

        <nav class="hidden items-center gap-7 lg:flex" aria-label="Primary">
            @foreach ($links as $link)
                <a href="{{ $link['url'] }}" class="nav-link {{ $link['active'] ? 'is-active' : '' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <button
                type="button"
                class="kbd"
                @click="$store.palette.toggle()"
                aria-label="Open command palette"
            >⌘K</button>
            <button
                type="button"
                class="nav-link"
                @click="$store.theme.toggle()"
                x-bind:aria-label="$store.theme.dark ? 'Switch to light mode' : 'Switch to dark mode'"
            >
                <span class="sr-only">Toggle theme</span>
                <x-icon name="sun" class="h-4 w-4 dark:hidden" />
                <x-icon name="moon" class="hidden h-4 w-4 dark:block" />
            </button>
        </nav>

        <div class="flex items-center gap-2 lg:hidden">
            <button
                type="button"
                class="kbd"
                @click="$store.palette.toggle()"
                aria-label="Open command palette"
            >⌘K</button>
            <button
                type="button"
                class="nav-link p-2"
                @click="$store.theme.toggle()"
                aria-label="Toggle theme"
            >
                <x-icon name="sun" class="h-4 w-4 dark:hidden" />
                <x-icon name="moon" class="hidden h-4 w-4 dark:block" />
            </button>
            <button
                type="button"
                class="label-meta px-2 py-2 text-ink"
                @click="open = ! open"
                x-bind:aria-expanded="open"
                aria-controls="mobile-menu"
            >
                <span x-text="open ? 'Close' : 'Menu'">Menu</span>
            </button>
        </div>
    </div>

    <nav
        id="mobile-menu"
        class="site-wrap border-t-2 border-ink py-3 lg:hidden"
        x-show="open"
        x-cloak
        x-transition.opacity.duration.200ms
        aria-label="Mobile"
    >
        @foreach ($links as $link)
            <a href="{{ $link['url'] }}" class="block py-3 text-sm font-medium tracking-[0.12em] uppercase" @click="close()">
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
</header>
