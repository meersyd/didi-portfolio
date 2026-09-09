<x-layouts.app title="{{ $site['seo']['title'] }}" description="{{ $site['seo']['description'] }}">
    <div class="home-shell">
        <div class="home-main">
            <section
                class="hero-room border-b-2 border-ink"
                data-character-pose="idle"
                x-data="poseSection"
            >
                <div class="site-wrap grid items-end gap-10 py-14 lg:py-20">
                    <div class="reveal is-visible max-w-3xl">
                        <x-section-label number="01 / Player one" />
                        <h1 class="text-5xl font-extrabold sm:text-6xl lg:text-7xl">
                            {{ strtoupper($site['first_name']) }}<br>
                            {{ strtoupper($site['last_name']) }}
                        </h1>
                        @if (filled($site['title']))
                            <p class="label-meta mt-6 text-ink">{{ $site['title'] }}</p>
                        @endif
                        @if (filled($site['headline']))
                            <p class="mt-8 max-w-2xl text-xl font-semibold leading-tight tracking-tight whitespace-pre-line sm:text-2xl">{{ $site['headline'] }}</p>
                        @endif
                        @if (filled($site['tagline']))
                            <p class="mt-6 max-w-lg text-[0.98rem] leading-relaxed text-subtle">
                                {{ $site['tagline'] }}
                            </p>
                        @endif
                        <div class="mt-8 flex flex-wrap gap-3">
                            <x-button href="{{ route('home') }}#work">
                                See the work
                                <x-icon name="arrow-right" class="btn-arrow h-4 w-4" />
                            </x-button>
                            @if (filled($site['resume']))
                                <x-button href="{{ $site['resume'] }}" variant="ghost">
                                    Download resume
                                    <x-icon name="download" class="h-4 w-4" />
                                </x-button>
                            @endif
                        </div>
                        <div class="mt-10 flex flex-wrap items-center gap-x-5 gap-y-2">
                            <span class="inline-flex items-center gap-2">
                                <span class="status-dot {{ ($presence['online'] ?? false) ? 'is-online' : 'is-offline' }}" aria-hidden="true"></span>
                                <span class="label-meta text-ink">{{ $presence['label'] ?? $site['availability'] }}</span>
                            </span>
                            @if (filled($site['location']))
                                <span class="label-meta">{{ $site['location'] }}</span>
                            @endif
                            <button type="button" class="label-meta text-ink" @click="$store.palette.toggle()">
                                Press <span class="kbd">⌘K</span>
                            </button>
                        </div>
                    </div>
                    <div class="reveal mx-auto w-full max-w-sm lg:hidden">
                        <div class="actor-window">
                            <div class="actor-window-bar">
                                <span>mirza.exe</span>
                                <span>poke me</span>
                            </div>
                            <x-character pose="idle" size="lg" :reactive="true" :clickable="true" />
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="work"
                class="border-b-2 border-ink py-16 lg:py-20"
                data-character-pose="walking"
                x-data="poseSection"
            >
                <div class="site-wrap">
                    <div class="reveal mb-10 max-w-2xl">
                        <x-section-label number="02 / Selected work" />
                        <h2 class="text-4xl font-bold sm:text-5xl">Proof, not a pitch deck.</h2>
                        <p class="mt-4 max-w-lg text-[0.98rem] leading-relaxed text-subtle">
                            Products with interfaces, backends, and the scars that come from using them.
                        </p>
                    </div>

                    @if ($projects->isNotEmpty())
                        <div
                            class="work-stage reveal"
                            x-data="workStage({{ $projects->count() }})"
                            @mouseenter="onEnter()"
                            @mouseleave="onLeave()"
                            @focusin="onEnter()"
                            @focusout="onLeave()"
                            @keydown.arrow-right.prevent="next()"
                            @keydown.arrow-left.prevent="prev()"
                            tabindex="0"
                            role="region"
                            aria-roledescription="carousel"
                            aria-label="Selected work"
                        >
                            <div class="work-stage-frame">
                            @foreach ($projects as $slideIndex => $project)
                                <a
                                    href="{{ route('projects.show', $project) }}"
                                    class="work-hero group {{ $project->isCompactPreview() ? 'is-phone' : '' }}"
                                    x-show="index === {{ $slideIndex }}"
                                    @if ($slideIndex > 0) x-cloak @endif
                                    x-transition.opacity.duration.350ms
                                >
                                    @if ($project->previewUrl())
                                        <img
                                            src="{{ $project->previewUrl() }}"
                                            alt="{{ $project->title }} preview"
                                            class="work-hero-image"
                                            @if ($slideIndex > 0) loading="lazy" @endif
                                        >
                                    @else
                                        <x-media-placeholder :title="$project->title" label="Quest" />
                                    @endif
                                    <div class="work-hero-meta">
                                        <p class="label-meta">Quest {{ str_pad((string) ($slideIndex + 1), 2, '0', STR_PAD_LEFT) }} / {{ str_pad((string) $projects->count(), 2, '0', STR_PAD_LEFT) }}</p>
                                        <h3 class="mt-2 text-3xl font-extrabold uppercase sm:text-5xl">{{ $project->title }}</h3>
                                        <x-offline-note :project="$project" compact />
                                        @if (filled($project->short_description))
                                            <p class="mt-3 max-w-xl text-sm leading-relaxed sm:text-base">{{ $project->short_description }}</p>
                                        @endif
                                        <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wide">
                                            Open case
                                            <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                            </div>

                            @if ($projects->count() > 1)
                                <div class="work-stage-controls">
                                    <button type="button" class="work-stage-btn" @click="prev()" aria-label="Previous project">
                                        <x-icon name="arrow-left" class="h-4 w-4" />
                                    </button>
                                    <div class="work-stage-dots" role="tablist" aria-label="Projects">
                                        @foreach ($projects as $slideIndex => $project)
                                            <button
                                                type="button"
                                                class="work-stage-dot"
                                                role="tab"
                                                :class="index === {{ $slideIndex }} ? 'is-on' : ''"
                                                :aria-selected="index === {{ $slideIndex }}"
                                                aria-label="Show {{ $project->title }}"
                                                @click="go({{ $slideIndex }})"
                                            ></button>
                                        @endforeach
                                    </div>
                                    <button type="button" class="work-stage-btn" @click="next()" aria-label="Next project">
                                        <x-icon name="arrow-right" class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button"
                                        class="work-stage-btn work-stage-pause"
                                        @click="toggleLock()"
                                        x-bind:aria-label="locked ? 'Play slideshow' : 'Pause slideshow'"
                                        x-text="locked ? 'Play' : 'Pause'"
                                    >Pause</button>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-4 border-t-2 border-ink">
                        @foreach ($projects as $index => $project)
                            <x-project-item :project="$project" :index="$index" class="reveal" />
                        @endforeach
                    </div>
                </div>
            </section>

            <section
                id="about"
                class="border-b-2 border-ink py-16 lg:py-20"
                data-character-pose="coding"
                x-data="poseSection"
            >
                <div class="site-wrap grid gap-12 lg:grid-cols-[1.15fr_0.85fr]">
                    <div class="reveal max-w-2xl">
                        <x-section-label number="03 / About" />
                        <x-about-copy />
                        @if (filled($site['currently']['heading'] ?? null))
                            <p class="label-meta mt-8 text-ink">{{ $site['currently']['heading'] }}</p>
                        @endif
                        @if (filled($site['currently']['body'] ?? null))
                            <p class="mt-3 max-w-md text-lg leading-snug text-ink">
                                {{ $site['currently']['body'] }}
                            </p>
                        @endif
                        @if (filled($site['availability']))
                            <p class="mt-5 flex items-center gap-2">
                                <span class="status-dot" aria-hidden="true"></span>
                                <span class="label-meta text-ink">{{ $site['availability'] }}</span>
                            </p>
                        @endif
                        <a href="{{ route('about') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold">
                            Education, toolkit, the rest
                            <x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                    <div class="reveal">
                        @if (! empty($site['focus']))
                            <p class="label-meta mb-4">Focus</p>
                            <ul class="mb-10 flex flex-wrap gap-2">
                                @foreach ($site['focus'] as $item)
                                    <li class="pixel-chip">{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <p class="label-meta mb-4">Log</p>
                        <ul class="space-y-3">
                            @foreach ($experiences->take(3) as $experience)
                                <li class="border-t-2 border-ink pt-3">
                                    <p class="font-semibold">{{ $experience->company }}</p>
                                    <p class="text-sm text-subtle">{{ $experience->role }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>

            <section
                id="contact"
                class="py-16 lg:py-20"
                data-character-pose="calling"
                x-data="poseSection"
            >
                <div class="site-wrap grid gap-16 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="reveal">
                        <x-section-label number="04 / Contact" />
                        <h2 class="text-5xl font-extrabold sm:text-6xl">
                            PING ME.
                        </h2>
                        <p class="mt-6 max-w-sm text-[0.98rem] leading-relaxed text-subtle">
                            Roles, products, weird ideas.<br>
                            I actually reply.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <x-button href="mailto:{{ $site['email'] }}">
                                Email me
                                <x-icon name="arrow-right" class="btn-arrow h-4 w-4" />
                            </x-button>
                            <x-button href="{{ $site['social']['linkedin'] }}" variant="ghost" external>
                                LinkedIn
                                <x-icon name="arrow-up-right" class="h-4 w-4" />
                            </x-button>
                            <x-button href="{{ $site['social']['github'] }}" variant="ghost" external>
                                GitHub
                                <x-icon name="arrow-up-right" class="h-4 w-4" />
                            </x-button>
                        </div>
                    </div>
                    <div class="reveal">
                        <p class="label-meta mb-4">Write</p>
                        <x-contact-form />
                    </div>
                </div>
            </section>
        </div>

        <aside class="home-actor" aria-label="Mirza, the site sprite">
            <div class="actor-window actor-window-sticky">
                <div class="actor-window-bar">
                    <span>mirza.exe</span>
                    <span>poke me</span>
                </div>
                <x-character pose="idle" size="lg" :reactive="true" :clickable="true" />
            </div>
        </aside>
    </div>
</x-layouts.app>
