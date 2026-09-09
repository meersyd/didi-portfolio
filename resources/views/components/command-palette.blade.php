@php
    $projectCommands = [];

    if (\Illuminate\Support\Facades\Schema::hasTable('projects')) {
        $projectCommands = \App\Models\Project::query()
            ->published()
            ->ordered()
            ->get()
            ->map(fn ($project) => [
                'label' => 'Open '.$project->title,
                'url' => route('projects.show', $project),
                'external' => false,
            ])
            ->all();
    }

    $commands = array_values(array_filter([
        ['label' => 'Go to Work', 'url' => route('home').'#work', 'external' => false],
        ['label' => 'Go to About', 'url' => route('about'), 'external' => false],
        ['label' => 'Go to Contact', 'url' => route('contact'), 'external' => false],
        filled($site['resume'] ?? null) ? ['label' => 'Download Resume', 'url' => $site['resume'], 'external' => false] : null,
        ['label' => 'Open GitHub', 'url' => $site['social']['github'], 'external' => true],
        ['label' => 'Open LinkedIn', 'url' => $site['social']['linkedin'], 'external' => true],
        ...$projectCommands,
    ]));
@endphp

<div
    x-data="palette"
    x-cloak
    data-commands='@json($commands)'
>
    <div
        class="palette-overlay"
        x-show="$store.palette.open"
        x-transition.opacity.duration.180ms
        @click.self="$store.palette.close()"
        role="dialog"
        aria-modal="true"
        aria-label="Command palette"
    >
        <div class="palette-panel" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="run()">
            <div class="flex items-center gap-3 border-b-2 border-ink px-4">
                <x-icon name="search" class="h-4 w-4 text-muted" />
                <input
                    x-ref="search"
                    type="text"
                    x-model="query"
                    @input="index = 0"
                    class="w-full border-0 bg-transparent py-3 font-mono text-sm text-ink outline-none"
                    placeholder="Jump somewhere. Try xcellorate."
                    aria-label="Search commands"
                >
                <span class="kbd">esc</span>
            </div>
            <ul class="max-h-80 overflow-auto py-2" role="listbox">
                <template x-for="(command, i) in filtered" :key="command.label">
                    <li>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between px-4 py-2.5 text-left text-sm"
                            :class="i === index ? 'bg-pixel/40 text-ink' : 'text-subtle'"
                            @mouseenter="index = i"
                            @click="run(command)"
                        >
                            <span x-text="command.label"></span>
                            <span class="label-meta" x-show="i === index">enter</span>
                        </button>
                    </li>
                </template>
                <li x-show="filtered.length === 0" class="px-4 py-3 text-sm text-muted">No matching commands.</li>
            </ul>
            <p class="border-t-2 border-ink px-4 py-2 label-meta">⌘K / Ctrl+K · poke the sprite too</p>
        </div>
    </div>
</div>
