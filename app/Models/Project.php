<?php

namespace App\Models;

use App\Support\VideoEmbed;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'category',
    'short_description',
    'description',
    'role',
    'technologies',
    'problem',
    'solution',
    'features',
    'technical_details',
    'challenges',
    'outcome',
    'hero_image',
    'gallery',
    'video_url',
    'github_url',
    'live_url',
    'live_unavailable',
    'featured',
    'published',
    'sort_order',
])]
class Project extends Model
{
    protected static function booted(): void
    {
        static::saving(function (Project $project): void {
            if (blank($project->slug) && filled($project->title)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'features' => 'array',
            'gallery' => 'array',
            'featured' => 'boolean',
            'published' => 'boolean',
            'live_unavailable' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function nextPublished(): ?self
    {
        return static::query()
            ->published()
            ->ordered()
            ->where('sort_order', '>', $this->sort_order)
            ->first()
            ?? static::query()
                ->published()
                ->ordered()
                ->where('id', '!=', $this->id)
                ->first();
    }

    public function imageDirectory(): string
    {
        return 'assets/projects/'.$this->slug;
    }

    public function displayNumber(int $index): string
    {
        return str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Features as pasted sentences. Comma-split fragments are joined back together.
     */
    public function featuresText(): string
    {
        $items = collect($this->features ?? [])
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values();

        if ($items->isEmpty()) {
            return '';
        }

        $startsBullet = static fn (string $item): bool => (bool) preg_match('/^(\*|-{1,3}|•)\s+/u', $item);

        if ($items->contains(fn (string $item): bool => $startsBullet($item))) {
            $lines = [];

            foreach ($items as $item) {
                if ($lines === [] || $startsBullet($item)) {
                    $lines[] = $item;
                } else {
                    $lines[array_key_last($lines)] .= ', '.$item;
                }
            }

            return implode("\n", $lines);
        }

        return $items->implode("\n\n");
    }

    /**
     * @return list<string>
     */
    public function textParagraphs(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(preg_split('/\R{2,}/u', $value) ?: [])
            ->map(fn ($paragraph) => trim((string) preg_replace('/\s*\R\s*/u', ' ', $paragraph)))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public function descriptionParagraphs(): array
    {
        return $this->textParagraphs($this->description);
    }

    /**
     * @return list<array{title: ?string, body: string}>
     */
    public function featureItems(): array
    {
        $lines = preg_split('/\R+/u', $this->featuresText()) ?: [];
        $items = [];

        foreach ($lines as $line) {
            $line = trim(preg_replace('/^(\*|-{1,3}|•)\s+/u', '', $line) ?? '');

            if ($line === '') {
                continue;
            }

            if (preg_match('/^\*\*(.+?)\*\*\s*(?:—|–|-{1,2})\s+(.+)$/su', $line, $match)
                || preg_match('/^(.+?)\s+(?:—|–)\s+(.+)$/su', $line, $match)) {
                $items[] = [
                    'title' => trim($match[1]),
                    'body' => trim($match[2]),
                ];

                continue;
            }

            $items[] = [
                'title' => null,
                'body' => $line,
            ];
        }

        return $items;
    }

    public function hasValue(string $field): bool
    {
        $value = $this->{$field};

        if (is_array($value)) {
            return collect($value)->filter(fn ($item) => filled($item))->isNotEmpty();
        }

        return filled($value);
    }

    public function previewUrl(): ?string
    {
        $path = $this->previewPath();

        return $path ? $this->mediaUrl($path) : null;
    }

    public function isCompactPreview(): bool
    {
        if ($this->slug === 'fixease' || str_contains(strtolower((string) $this->category), 'mobile')) {
            return true;
        }

        return $this->previewIsPortrait();
    }

    public function previewIsPortrait(): bool
    {
        $relative = $this->previewPath();

        if ($relative === null) {
            return false;
        }

        $full = public_path($relative);

        if (! is_file($full)) {
            return false;
        }

        $size = @getimagesize($full);

        if (! is_array($size) || (int) ($size[0] ?? 0) < 1) {
            return false;
        }

        return (int) $size[1] > (int) $size[0];
    }

    public function previewPath(): ?string
    {
        $path = $this->hasValue('hero_image')
            ? $this->hero_image
            : collect($this->gallery ?? [])->first();

        if (! is_string($path) || blank($path) || preg_match('#^(https?:)?//#i', $path) === 1) {
            return null;
        }

        return ltrim($path, '/');
    }

    public function mediaUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (preg_match('#^(https?:)?//#i', $path) === 1) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    /**
     * @return list<string>
     */
    public function galleryUrls(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn ($item) => is_string($item) ? trim($item) : '')
            ->filter()
            ->map(fn ($item) => $this->mediaUrl($item))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array{type: 'iframe'|'file', src: string}|null
     */
    public function videoEmbed(): ?array
    {
        return VideoEmbed::from($this->video_url);
    }
}
