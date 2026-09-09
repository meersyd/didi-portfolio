<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data
    x-init="$store.theme.init()"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $pageTitle = $title ?? $site['seo']['title'];
            $pageDescription = $description ?? $site['seo']['description'];
            $canonical = $canonical ?? url()->current();
        @endphp
        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $pageDescription }}">
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:site_name" content="{{ $site['name'] }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
        <link
            rel="icon"
            href="{{ asset('assets/brand/logo-light.png') }}"
            type="image/png"
            data-icon-light="{{ asset('assets/brand/logo-light.png') }}"
            data-icon-dark="{{ asset('assets/brand/logo-dark.png') }}"
        >
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&family=Pixelify+Sans:wght@500;600;700&display=swap" rel="stylesheet">
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
    <body id="top" class="bg-canvas text-ink antialiased {{ request()->routeIs('home') ? 'is-home' : '' }}">
        <a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-surface focus:px-3 focus:py-2">
            Skip to content
        </a>
        <x-navbar />
        <main id="content">
            {{ $slot }}
        </main>
        <x-footer />
        <x-character-dock :home="request()->routeIs('home')" />
        <x-command-palette />
    </body>
</html>
