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
        return $this->resumeDiskPath() !== null;
    }

    public function resumeDiskPath(): ?string
    {
        if (blank($this->resume_path) || ! Storage::disk('local')->exists($this->resume_path)) {
            return null;
        }

        return Storage::disk('local')->path($this->resume_path);
    }

    public function resumeDownloadName(): string
    {
        $name = trim($this->first_name.' '.$this->last_name);

        return Str::slug($name !== '' ? $name : 'resume').'-resume.pdf';
    }
}
