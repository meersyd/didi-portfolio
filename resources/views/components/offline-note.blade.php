@props(['project', 'compact' => false])

@if ($project->live_unavailable)
    @if ($compact)
        <p class="project-offline-flag">Currently unavailable online</p>
    @else
        <aside {{ $attributes->class(['project-offline-note']) }} role="note">
            <p class="label-meta text-ink">Currently unavailable online</p>
            <p>
                The live site is currently unavailable due to the expired server and domain subscription. The project is fully functional locally, with selected screenshots provided below for reference.
            </p>
        </aside>
    @endif
@endif
