<?php

namespace App\Models;

use App\Support\RichText;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'first_name',
    'last_name',
    'title',
    'headline',
    'tagline',
    'location',
    'meta_degree',
    'meta_discipline',
    'about_heading',
    'about_body',
    'currently_heading',
    'currently_body',
    'availability',
    'focus',
    'resume_path',
    'resume_data',
])]
class SiteCopy extends Model
{
    protected function casts(): array
    {
        return [
            'focus' => 'array',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultAttributes(): array
    {
        $defaults = config('portfolio');

        return [
            'first_name' => $defaults['first_name'],
            'last_name' => $defaults['last_name'],
            'title' => $defaults['title'],
            'headline' => $defaults['headline'],
            'tagline' => $defaults['tagline'],
            'location' => $defaults['location'],
            'meta_degree' => $defaults['meta']['degree'],
            'meta_discipline' => $defaults['meta']['discipline'],
            'about_heading' => 'Formerly Dee. Still shipping.',
            'about_body' => implode("\n\n", [
                "I'm **Mirza Rusyaidi** — Dee, if we met before the name stuck. Software Engineering graduate from UMPSA who likes turning a messy idea into something you can click, play, or ship.",
                'I work across the stack: **interfaces, APIs, databases, and the glue** that makes them behave. Laravel, React, Go, PostgreSQL. The work I care about has a pulse — scoring, sessions, care records, repair jobs — not just another dashboard.',
                'I like experimenting, breaking things, figuring out why they broke, and building them better the next time.',
            ]),
            'currently_heading' => "What I'm after.",
            'currently_body' => 'A software engineering seat where I can own a product surface end to end. Malaysia-based. Ready to build.',
            'availability' => $defaults['availability'],
            'focus' => $defaults['focus'],
        ];
    }

    public static function current(): self
    {
        return static::query()->first() ?? static::query()->create(static::defaultAttributes());
    }

    /**
     * @return array<string, mixed>
     */
    public function toSiteArray(): array
    {
        $name = trim($this->first_name.' '.$this->last_name);

        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $name !== '' ? $name : config('portfolio.name'),
            'title' => $this->title,
            'headline' => $this->headline,
            'tagline' => $this->tagline,
            'location' => $this->location,
            'availability' => $this->availability,
            'focus' => $this->focus ?? [],
            'meta' => [
                'degree' => $this->meta_degree,
                'discipline' => $this->meta_discipline,
            ],
            'about' => [
                'heading' => $this->about_heading,
                'paragraphs' => RichText::paragraphs($this->about_body),
            ],
            'currently' => [
                'heading' => $this->currently_heading,
                'body' => $this->currently_body,
            ],
        ];
    }

    public function hasResume(): bool
    {
        return $this->resumeContents() !== null;
    }

    public function hasStoredResumePayload(): bool
    {
        $data = $this->resume_data;

        return is_string($data) && $data !== '';
    }

    /**
     * Absolute path helper for tests / local tooling.
     */
    public function resumeDiskPath(): ?string
    {
        $this->restoreResumeToDisk();

        if (filled($this->resume_path) && Storage::disk('local')->exists($this->resume_path)) {
            return Storage::disk('local')->path($this->resume_path);
        }

        $public = public_path('resume.pdf');

        return is_file($public) ? $public : null;
    }

    /**
     * Preferred resume bytes: admin DB upload first, then storage, then public fallback.
     */
    public function resumeContents(): ?string
    {
        if ($this->hasStoredResumePayload()) {
            $binary = $this->resumeBinary();

            if (is_string($binary) && $binary !== '' && str_starts_with($binary, '%PDF')) {
                $this->restoreResumeToDisk(force: true);

                return $binary;
            }
        }

        $this->restoreResumeToDisk();

        if (filled($this->resume_path) && Storage::disk('local')->exists($this->resume_path)) {
            $stored = Storage::disk('local')->get($this->resume_path);

            if (is_string($stored) && $stored !== '' && str_starts_with($stored, '%PDF')) {
                return $stored;
            }
        }

        $public = public_path('resume.pdf');

        if (! is_file($public)) {
            return null;
        }

        $fallback = file_get_contents($public);

        return is_string($fallback) && $fallback !== '' ? $fallback : null;
    }

    public function resumeDownloadName(): string
    {
        $name = trim($this->first_name.' '.$this->last_name);

        return Str::slug($name !== '' ? $name : 'resume').'-resume.pdf';
    }

    /**
     * Persist an uploaded resume so portfolio downloads stay in sync
     * across ephemeral Render disks.
     */
    public function storeResumePayload(string $binary): void
    {
        if (! str_starts_with($binary, '%PDF')) {
            throw new \InvalidArgumentException('Uploaded file must be a PDF.');
        }

        $encoded = base64_encode($binary);

        $this->forceFill([
            'resume_path' => 'resumes/resume.pdf',
            'resume_data' => $encoded,
        ])->save();

        $this->refresh();

        Storage::disk('local')->put('resumes/resume.pdf', $binary);
        $this->mirrorResumeToPublic($binary);
    }

    public function clearStoredResume(): void
    {
        if (filled($this->resume_path) && Storage::disk('local')->exists($this->resume_path)) {
            Storage::disk('local')->delete($this->resume_path);
        }

        $this->forceFill([
            'resume_path' => null,
            'resume_data' => null,
        ])->save();
    }

    /**
     * Rehydrate private storage from the DB payload after deploys.
     */
    public function restoreResumeToDisk(bool $force = false): void
    {
        if (! $this->hasStoredResumePayload()) {
            return;
        }

        $binary = $this->resumeBinary();

        if ($binary === null || $binary === '' || ! str_starts_with($binary, '%PDF')) {
            return;
        }

        if ($force || ! Storage::disk('local')->exists('resumes/resume.pdf')) {
            Storage::disk('local')->put('resumes/resume.pdf', $binary);
        }

        if (blank($this->resume_path)) {
            $this->forceFill(['resume_path' => 'resumes/resume.pdf'])->saveQuietly();
        }
    }

    /**
     * Seed storage from the bundled public PDF only when no admin upload exists.
     * Never overwrite an admin-uploaded resume_data payload.
     */
    public static function syncResumeFromPublic(): void
    {
        $copy = static::query()->first();

        if ($copy?->hasStoredResumePayload()) {
            $copy->restoreResumeToDisk(force: true);

            return;
        }

        $public = public_path('resume.pdf');

        if (! is_file($public)) {
            return;
        }

        $binary = file_get_contents($public);

        if ($binary === false || $binary === '' || ! str_starts_with($binary, '%PDF')) {
            return;
        }

        if (! Storage::disk('local')->exists('resumes/resume.pdf')) {
            Storage::disk('local')->put('resumes/resume.pdf', $binary);
        }

        if ($copy && blank($copy->resume_path)) {
            $copy->forceFill([
                'resume_path' => 'resumes/resume.pdf',
            ])->save();
        }
    }

    public static function ensureResumeAvailable(): void
    {
        $copy = static::query()->first();

        if ($copy === null) {
            static::syncResumeFromPublic();

            return;
        }

        if ($copy->hasStoredResumePayload()) {
            $copy->restoreResumeToDisk(force: true);

            return;
        }

        static::syncResumeFromPublic();
    }

    protected function resumeBinary(): ?string
    {
        $data = $this->attributes['resume_data'] ?? $this->resume_data ?? null;

        if (is_resource($data)) {
            $data = stream_get_contents($data);
        }

        if (! is_string($data) || $data === '') {
            return null;
        }

        $decoded = base64_decode($data, true);

        if ($decoded !== false && $decoded !== '' && str_starts_with($decoded, '%PDF')) {
            return $decoded;
        }

        // Legacy rows may contain raw PDF bytes.
        if (str_starts_with($data, '%PDF')) {
            return $data;
        }

        return null;
    }

    protected function mirrorResumeToPublic(string $binary): void
    {
        // Intentionally no-op on production images where public/ is read-only.
        // Admin uploads live in DB + private storage and are streamed by ResumeController.
    }
}
