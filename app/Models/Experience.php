<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company',
    'role',
    'start_date',
    'end_date',
    'description',
    'technologies',
    'sort_order',
])]
class Experience extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'technologies' => 'array',
        ];
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('start_date');
    }

    public function periodLabel(): string
    {
        $start = $this->start_date?->format('M Y');
        $end = $this->end_date?->format('M Y');

        if ($this->end_date === null) {
            return $start.' — Present';
        }

        if ($start === $end) {
            return (string) $start;
        }

        return $start.' — '.$end;
    }

    public function isCurrent(): bool
    {
        return $this->end_date === null || $this->end_date->isFuture();
    }
}
