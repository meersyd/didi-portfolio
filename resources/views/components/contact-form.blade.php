<form method="POST" action="{{ route('contact.store') }}" class="relative grid gap-4" novalidate>
    @csrf
    <div class="honeypot" aria-hidden="true">
        <label for="company_website">Company website</label>
        <input id="company_website" type="text" name="company_website" tabindex="-1" autocomplete="off">
    </div>

    @if (session('status'))
        <p class="border border-line bg-surface px-4 py-3 text-sm" role="status">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div class="border border-line px-4 py-3 text-sm text-subtle" role="alert">
            <p class="font-medium text-ink">Please check the form.</p>
            <ul class="mt-2 list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Name</span>
            <input class="field" type="text" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name">
        </label>
        <label class="grid gap-2 text-sm">
            <span class="label-meta">Email</span>
            <input class="field" type="email" name="email" value="{{ old('email') }}" required maxlength="180" autocomplete="email">
        </label>
    </div>
    <label class="grid gap-2 text-sm">
        <span class="label-meta">Subject</span>
        <input class="field" type="text" name="subject" value="{{ old('subject') }}" required maxlength="180">
    </label>
    <label class="grid gap-2 text-sm">
        <span class="label-meta">Message</span>
        <textarea class="field" name="message" required minlength="12" maxlength="4000">{{ old('message') }}</textarea>
    </label>
    <div>
        <x-button type="submit">Send message <x-icon name="arrow-right" class="btn-arrow h-4 w-4" /></x-button>
    </div>
</form>
