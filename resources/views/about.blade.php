<x-layouts.app
    title="About — {{ $site['name'] }}"
    :description="$site['tagline']"
>
    <section class="py-16 lg:py-24">
        <div class="site-wrap max-w-3xl" data-character-pose="coding" x-data="poseSection">
            <x-section-label number="02 / About" />
            <x-about-copy heading="h1" />
            @if (filled($site['currently']['body'] ?? null))
                <p class="mt-8 max-w-md text-lg leading-snug text-ink">
                    {{ $site['currently']['body'] }}
                </p>
            @endif
        </div>

        <div class="site-wrap mt-20" id="experience" data-character-pose="walking" x-data="poseSection">
            <p class="label-meta mb-8">Experience</p>
            <div class="timeline">
                @foreach ($experiences as $experience)
                    <x-timeline-item :experience="$experience" />
                @endforeach
            </div>
        </div>

        <div class="site-wrap mt-20" data-character-pose="reading" x-data="poseSection">
            <p class="label-meta mb-8">Education</p>
            <div class="timeline">
                @foreach ($education as $record)
                    <x-education-item :record="$record" />
                @endforeach
            </div>
        </div>

        <div class="site-wrap mt-20">
            <p class="label-meta mb-8">Toolkit</p>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($skills as $category => $group)
                    <x-skill-group :category="$category" :skills="$group" />
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
