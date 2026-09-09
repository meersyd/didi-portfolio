<x-layouts.app title="Sign in — {{ $site['name'] }}" description="Admin access.">
    <section class="py-20">
        <div class="site-wrap max-w-md">
            <a href="{{ route('home') }}" class="mb-4 inline-flex text-ink" aria-label="{{ $site['name'] }}">
                <x-logo class="h-10 w-10" />
            </a>
            <h1 class="text-3xl font-bold">Sign in</h1>
            <p class="mt-2 text-sm text-subtle">Admin access only.</p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 grid gap-4">
                @csrf
                @if ($errors->any())
                    <p class="border border-line px-4 py-3 text-sm" role="alert">{{ $errors->first() }}</p>
                @endif
                <label class="grid gap-2 text-sm">
                    <span class="label-meta">Email</span>
                    <input class="field" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                </label>
                <label class="grid gap-2 text-sm">
                    <span class="label-meta">Password</span>
                    <input class="field" type="password" name="password" required autocomplete="current-password">
                </label>
                <label class="flex items-center gap-2 text-sm text-subtle">
                    <input type="checkbox" name="remember" value="1">
                    Remember me
                </label>
                <x-button type="submit">Continue</x-button>
            </form>
        </div>
    </section>
</x-layouts.app>
