@php
    use App\Support\TextList;
@endphp

<x-layouts.admin title="Pages">
    <p class="label-meta">Pages</p>
    <h1 class="mt-2 text-3xl font-bold">Introduction, about and currently</h1>
    <p class="mt-2 max-w-2xl text-sm text-subtle">
        These fields power the public homepage. Leave a field blank to hide it. In About, wrap words in **double asterisks** to make them bold. Resume is a PDF upload — no code change needed.
    </p>

    <form method="POST" action="{{ route('admin.pages.update') }}" class="mt-8 grid max-w-3xl gap-4" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <ul class="border border-line px-4 py-3 text-sm" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <p class="label-meta pt-4">01 / Introduction</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="grid gap-2 text-sm">
                <span class="label-meta">First name</span>
                <input class="field" name="first_name" value="{{ old('first_name', $copy->first_name) }}" required>
            </label>
            <label class="grid gap-2 text-sm">
                <span class="label-meta">Last name</span>
                <input class="field" name="last_name" value="{{ old('last_name', $copy->last_name) }}" required>
            </label>
        </div>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Title</span>
            <input class="field" name="title" value="{{ old('title', $copy->title) }}" placeholder="Software Engineer">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Headline</span>
            <textarea class="field" name="headline" rows="3">{{ old('headline', $copy->headline) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Tagline</span>
            <textarea class="field" name="tagline" rows="3">{{ old('tagline', $copy->tagline) }}</textarea>
        </label>
        <div class="grid gap-4 sm:grid-cols-3">
            <label class="grid gap-2 text-sm">
                <span class="label-meta">Location</span>
                <input class="field" name="location" value="{{ old('location', $copy->location) }}">
            </label>
            <label class="grid gap-2 text-sm">
                <span class="label-meta">Degree</span>
                <input class="field" name="meta_degree" value="{{ old('meta_degree', $copy->meta_degree) }}">
            </label>
            <label class="grid gap-2 text-sm">
                <span class="label-meta">Discipline</span>
                <input class="field" name="meta_discipline" value="{{ old('meta_discipline', $copy->meta_discipline) }}">
            </label>
        </div>

        <p class="label-meta pt-6">02 / About Me</p>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Heading</span>
            <input class="field" name="about_heading" value="{{ old('about_heading', $copy->about_heading) }}">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Body</span>
            <textarea class="field" name="about_body" rows="10">{{ old('about_body', $copy->about_body) }}</textarea>
        </label>

        <p class="label-meta pt-6">03 / Currently</p>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Heading</span>
            <input class="field" name="currently_heading" value="{{ old('currently_heading', $copy->currently_heading) }}">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Body</span>
            <textarea class="field" name="currently_body" rows="4">{{ old('currently_body', $copy->currently_body) }}</textarea>
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Availability</span>
            <input class="field" name="availability" value="{{ old('availability', $copy->availability) }}">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Focus</span>
            <textarea class="field" name="focus" rows="5">{{ old('focus', TextList::toText($copy->focus)) }}</textarea>
            <span class="text-xs text-muted">One item per line.</span>
        </label>

        <p class="label-meta pt-6">Resume</p>
        <div class="grid gap-3">
            @if ($copy->hasResume())
                <p class="text-sm text-subtle">
                    Current file:
                    <a href="{{ route('resume') }}" class="text-ink underline underline-offset-2">Download</a>
                </p>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remove_resume" value="1">
                    Remove current resume
                </label>
            @else
                <p class="text-sm text-subtle">No resume uploaded yet. The download button stays hidden on the site until you add one.</p>
            @endif
            <label class="grid gap-2 text-sm">
                <span class="label-meta">PDF file</span>
                <input class="field" type="file" name="resume" accept="application/pdf">
                <span class="text-xs text-muted">PDF only, up to 10 MB. Uploading a new file replaces the current one.</span>
            </label>
        </div>

        <div class="flex gap-3 pt-2">
            <x-button type="submit">Save</x-button>
            <x-button href="{{ route('home') }}" variant="ghost">View site</x-button>
        </div>
    </form>
</x-layouts.admin>
