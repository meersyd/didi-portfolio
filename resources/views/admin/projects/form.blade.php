@php
    use App\Support\TextList;
    $editing = $project->exists;
@endphp

<x-layouts.admin :title="$editing ? 'Edit project' : 'New project'">
    <p class="label-meta">Projects</p>
    <h1 class="mt-2 text-3xl font-bold">{{ $editing ? 'Edit project' : 'New project' }}</h1>

    <p class="mt-2 max-w-2xl text-sm text-subtle">Empty fields stay hidden on the public project page.</p>

    <form
        method="POST"
        action="{{ $editing ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
        class="mt-8 grid max-w-3xl gap-4"
    >
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        @if ($errors->any())
            <ul class="border border-line px-4 py-3 text-sm text-subtle" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <label class="grid gap-2 text-sm">
            <span class="label-meta">Title</span>
            <input class="field" name="title" value="{{ old('title', $project->title) }}" required>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Slug</span>
            <input class="field" name="slug" value="{{ old('slug', $project->slug) }}">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Category</span>
            <input class="field" name="category" value="{{ old('category', $project->category) }}">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Role</span>
            <input class="field" name="role" value="{{ old('role', $project->role) }}">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Short description</span>
            <textarea class="field" name="short_description">{{ old('short_description', $project->short_description) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Description</span>
            <textarea class="field" name="description">{{ old('description', $project->description) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Technologies</span>
            <textarea class="field" name="technologies">{{ old('technologies', TextList::toText($project->technologies)) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Problem</span>
            <textarea class="field" name="problem">{{ old('problem', $project->problem) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Approach</span>
            <textarea class="field" name="solution">{{ old('solution', $project->solution) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Features</span>
            <textarea class="field" name="features" rows="8">{{ old('features', $project->featuresText()) }}</textarea>
            <span class="text-xs text-muted">Paste sentences as written. Commas stay in the sentence. Optional Markdown: * bullet, **bold**.</span>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Technical details</span>
            <textarea class="field" name="technical_details">{{ old('technical_details', $project->technical_details) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Challenges</span>
            <textarea class="field" name="challenges">{{ old('challenges', $project->challenges) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Outcome</span>
            <textarea class="field" name="outcome">{{ old('outcome', $project->outcome) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Hero image path</span>
            <input class="field" name="hero_image" value="{{ old('hero_image', $project->hero_image) }}" placeholder="assets/projects/fixease/hero.png">
            <span class="text-xs text-muted">Cover image. Files live in public/.</span>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Gallery images</span>
            <textarea class="field" name="gallery" placeholder="assets/projects/fixease/home.png">{{ old('gallery', TextList::toText($project->gallery)) }}</textarea>
            <span class="text-xs text-muted">One path per line. Extra screenshots for expired sites or app screens.</span>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Video URL</span>
            <input class="field" name="video_url" value="{{ old('video_url', $project->video_url) }}" placeholder="https://youtu.be/… or assets/projects/fixease/walkthrough.mp4">
            <span class="text-xs text-muted">YouTube, Loom, Vimeo, or a local file such as assets/projects/fixease/walkthrough.mp4</span>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">GitHub URL</span>
            <input class="field" name="github_url" value="{{ old('github_url', $project->github_url) }}">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Live URL</span>
            <input class="field" name="live_url" value="{{ old('live_url', $project->live_url) }}">
            <span class="text-xs text-muted">Leave empty if the live site is down — the button stays hidden.</span>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Sort order</span>
            <input class="field" type="number" min="0" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" required>
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $project->featured))>
            Featured
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="published" value="1" @checked(old('published', $project->published))>
            Published
        </label>
        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <x-button href="{{ route('admin.projects.index') }}" variant="ghost">Cancel</x-button>
        </div>
    </form>
</x-layouts.admin>
