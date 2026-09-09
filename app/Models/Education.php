<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'institution',
    'degree',
    'field',
    'start_year',
    'end_year',
    'description',
])]
class Education extends Model
{
    protected $table = 'education';

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('end_year')->orderByDesc('start_year');
    }

    public function periodLabel(): string
    {
        if ($this->end_year === null) {
            return $this->start_year.' — Present';
        }

        return $this->start_year.' — '.$this->end_year;
    }
}
