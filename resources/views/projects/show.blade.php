@php
    $number = str_pad((string) ($project->sort_order ?: 1), 2, '0', STR_PAD_LEFT);
    $techs = collect($project->technologies ?? [])->filter(fn ($item) => filled($item));
@endphp

<x-layouts.app
    title="{{ $project->title }} — {{ $site['name'] }}"
    :description="$project->short_description ?: $site['seo']['description']"
>
        <article class="py-16 lg:py-24" data-character-pose="coding" x-data="poseSection">
        <div class="site-wrap">
            <p class="label-meta">Project {{ $number }}</p>
            <h1 class="mt-4 text-5xl font-extrabold uppercase sm:text-6xl">{{ $project->title }}</h1>
            @if ($project->hasValue('short_description'))
                <p class="mt-4 max-w-xl text-lg text-subtle">{{ $project->short_description }}</p>
            @endif
            @if ($project->hasValue('category'))
                <p class="mt-3 font-mono text-xs tracking-wide text-muted">{{ $project->category }}</p>
            @endif

            @if ($project->hasValue('hero_image'))
                <div class="mt-10 {{ $project->isCompactPreview() ? 'flex justify-center' : '' }}">
                    <img
                        src="{{ $project->mediaUrl($project->hero_image) }}"
                        alt="{{ $project->title }} preview"
                        class="border-2 border-ink {{ $project->isCompactPreview() ? 'w-full max-w-[18rem]' : 'project-hero-wide' }}"
                        loading="lazy"
                    >
                </div>
            @endif

            <x-project-video :embed="$project->videoEmbed()" />

            @if ($project->hasValue('github_url') || $project->hasValue('live_url'))
                <div class="mt-8 flex flex-wrap gap-3">
                    @if ($project->hasValue('live_url'))
                        <x-button href="{{ $project->live_url }}" external>Live site</x-button>
                    @endif
                    @if ($project->hasValue('github_url'))
                        <x-button href="{{ $project->github_url }}" variant="ghost" external>GitHub</x-button>
                    @endif
                </div>
            @endif

            @if ($project->hasValue('description') || $project->hasValue('role') || $techs->isNotEmpty() || $project->hasValue('problem') || $project->hasValue('solution'))
                <dl class="project-spec mt-16">
                    @if ($project->hasValue('description'))
                        <div class="project-spec-row">
                            <dt class="label-meta">Overview</dt>
                            <dd class="project-spec-copy">
                                @foreach ($project->descriptionParagraphs() as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    @if ($project->hasValue('role'))
                        <div class="project-spec-row">
                            <dt class="label-meta">Role</dt>
                            <dd>{{ $project->role }}</dd>
                        </div>
                    @endif
                    @if ($techs->isNotEmpty())
                        <div class="project-spec-row">
                            <dt class="label-meta">Stack</dt>
                            <dd>
                                <ul class="project-stack">
                                    @foreach ($techs as $tech)
                                        <li>{{ $tech }}</li>
                                    @endforeach
                                </ul>
                            </dd>
                        </div>
                    @endif
                    @if ($project->hasValue('problem'))
                        <div class="project-spec-row">
                            <dt class="label-meta">The problem</dt>
                            <dd class="project-spec-copy">
                                @foreach ($project->textParagraphs($project->problem) as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    @if ($project->hasValue('solution'))
                        <div class="project-spec-row">
                            <dt class="label-meta">The approach</dt>
                            <dd class="project-spec-copy">
                                @foreach ($project->textParagraphs($project->solution) as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </dl>
            @endif

            @php
                $featureItems = $project->featureItems();
            @endphp
            @if ($featureItems !== [])
                <section class="mt-16">
                    <h2 class="label-meta mb-6">Key features</h2>
                    <div class="project-features {{ count($featureItems) > 1 ? 'is-grid' : '' }}">
                        @foreach ($featureItems as $feature)
                            <article class="project-feature">
                                <p class="label-meta">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</p>
                                @if (filled($feature['title']))
                                    <h3>{{ $feature['title'] }}</h3>
                                @endif
                                <p>{{ $feature['body'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            <x-project-gallery :images="$project->galleryUrls()" />

            @if ($project->hasValue('technical_details') || $project->hasValue('challenges') || $project->hasValue('outcome'))
                <div class="mt-16 grid gap-12 lg:grid-cols-3">
                    @if ($project->hasValue('technical_details'))
                        <section>
                            <h2 class="label-meta mb-4">Technical details</h2>
                            <p class="text-[0.95rem] leading-relaxed text-subtle">{{ $project->technical_details }}</p>
                        </section>
                    @endif
                    @if ($project->hasValue('challenges'))
                        <section>
                            <h2 class="label-meta mb-4">Challenges</h2>
                            <p class="text-[0.95rem] leading-relaxed text-subtle">{{ $project->challenges }}</p>
                        </section>
                    @endif
                    @if ($project->hasValue('outcome'))
                        <section>
                            <h2 class="label-meta mb-4">Outcome</h2>
                            <p class="text-[0.95rem] leading-relaxed text-subtle">{{ $project->outcome }}</p>
                        </section>
                    @endif
                </div>
            @endif

            <div class="mt-20 flex items-center justify-between border-t border-line pt-8">
                <a href="{{ route('projects.index') }}" class="label-meta text-ink">All work</a>
                @if ($nextProject)
                    <a href="{{ route('projects.show', $nextProject) }}" class="inline-flex items-center gap-2 text-sm font-semibold">
                        Next project
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                @endif
            </div>
        </div>
    </article>
</x-layouts.app>
