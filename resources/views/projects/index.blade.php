<x-layouts.app
    title="Work — {{ $site['name'] }}"
    description="Selected software projects by Mirza Rusyaidi, spanning platforms, web applications, and mobile products."
>
    <section class="py-16 lg:py-24" data-character-pose="walking" x-data="poseSection">
        <div class="site-wrap">
            <div class="mb-12 max-w-2xl">
                <x-section-label number="02 / Selected work" />
                <h1 class="text-4xl font-bold sm:text-5xl">Proof, not a pitch deck.</h1>
                <p class="mt-4 max-w-lg text-[0.98rem] leading-relaxed text-subtle">
                    Products with interfaces, backends, and the scars that come from using them.
                </p>
            </div>
            <div class="border-t-2 border-ink">
                @forelse ($projects as $index => $project)
                    <x-project-item :project="$project" :index="$index" />
                @empty
                    <p class="py-12 text-subtle">No published projects yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
