<x-layouts.app
    title="Contact — {{ $site['name'] }}"
    description="Get in touch with Mirza Rusyaidi about software engineering roles, projects, and collaboration."
>
    <section class="py-16 lg:py-24" data-character-pose="calling" x-data="poseSection">
        <div class="site-wrap grid gap-16 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <x-section-label number="04 / Contact" />
                <h1 class="text-5xl font-extrabold sm:text-6xl">
                    PING ME.
                </h1>
                <p class="mt-6 max-w-sm text-[0.98rem] leading-relaxed text-subtle">
                    Roles, products, weird ideas.<br>
                    I actually reply.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <x-button href="mailto:{{ $site['email'] }}">
                        Email me
                        <x-icon name="arrow-right" class="btn-arrow h-4 w-4" />
                    </x-button>
                    <x-button href="{{ $site['social']['linkedin'] }}" variant="ghost" external>LinkedIn</x-button>
                    <x-button href="{{ $site['social']['github'] }}" variant="ghost" external>GitHub</x-button>
                </div>
            </div>
            <div>
                <p class="label-meta mb-4">Write</p>
                <x-contact-form />
            </div>
        </div>
    </section>
</x-layouts.app>
