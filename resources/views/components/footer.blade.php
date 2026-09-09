<footer class="border-t-2 border-ink">
    <div class="site-wrap flex flex-col items-center gap-3 py-8 text-center sm:flex-row sm:justify-between sm:text-left">
        <p class="text-sm text-subtle">© {{ now()->year }} {{ $site['name'] }}. Built with a sprite.</p>
        <p class="label-meta">
            <button type="button" class="text-ink" @click="$store.palette.toggle()">⌘K to travel</button>
            <span class="mx-2 text-muted">·</span>
            poke the sprite
        </p>
    </div>
</footer>
