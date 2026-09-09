@php
    $nav = [
        ['label' => 'Overview', 'route' => 'admin.dashboard'],
        ['label' => 'Pages', 'route' => 'admin.pages.edit'],
        ['label' => 'Projects', 'route' => 'admin.projects.index'],
        ['label' => 'Experience', 'route' => 'admin.experiences.index'],
        ['label' => 'Skills', 'route' => 'admin.skills.index'],
        ['label' => 'Education', 'route' => 'admin.education.index'],
        ['label' => 'Messages', 'route' => 'admin.messages.index'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-init="$store.theme.init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Admin' }} — {{ $site['name'] }}</title>
        <meta name="robots" content="noindex, nofollow">
        <link
            rel="icon"
            href="{{ asset('assets/brand/logo-light.png') }}"
            type="image/png"
            data-icon-light="{{ asset('assets/brand/logo-light.png') }}"
            data-icon-dark="{{ asset('assets/brand/logo-dark.png') }}"
        >
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&family=Pixelify+Sans:wght@500;600;700&display=swap" rel="stylesheet">
        <script>
            (function () {
                try {
                    var stored = localStorage.getItem('mirza-theme');
                    var dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.classList.toggle('dark', dark);
                    document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
                    var icon = document.querySelector('link[rel="icon"]');
                    if (icon && icon.dataset.iconLight && icon.dataset.iconDark) {
                        icon.href = dark ? icon.dataset.iconDark : icon.dataset.iconLight;
                    }
                } catch (e) {}
            })();
        </script>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-canvas text-ink antialiased">
        <div class="min-h-screen lg:grid lg:grid-cols-[15rem_1fr]">
            <aside class="border-b border-line lg:border-b-0 lg:border-r">
                <div class="flex items-center justify-between px-5 py-4">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex text-ink" aria-label="{{ $site['name'] }}">
                        <x-logo class="h-8 w-8" />
                    </a>
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="nav-link p-1"
                            @click="$store.theme.toggle()"
                            x-bind:aria-label="$store.theme.dark ? 'Switch to light mode' : 'Switch to dark mode'"
                        >
                            <span class="sr-only">Toggle theme</span>
                            <x-icon name="sun" class="h-4 w-4 dark:hidden" />
                            <x-icon name="moon" class="hidden h-4 w-4 dark:block" />
                        </button>
                        <span class="label-meta">Admin</span>
                    </div>
                </div>
                <nav class="admin-nav flex gap-1 overflow-x-auto px-3 pb-3 lg:block lg:space-y-1 lg:px-3" aria-label="Admin">
                    @foreach ($nav as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            class="block whitespace-nowrap px-3 py-2 text-sm text-subtle {{ request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route'])) || request()->routeIs(str_replace('.edit', '.*', $item['route'])) ? 'is-active' : '' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                <div class="flex items-center justify-between px-5 py-4 lg:mt-8">
                    <a href="{{ route('home') }}" class="text-sm text-subtle">View site</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-subtle">Log out</button>
                    </form>
                </div>
            </aside>
            <div class="px-5 py-8 lg:px-10">
                @if (session('status'))
                    <p class="mb-6 border border-line bg-surface px-4 py-3 text-sm" role="status">{{ session('status') }}</p>
                @endif
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
