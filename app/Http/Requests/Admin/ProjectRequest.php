<?php

namespace App\Http\Requests\Admin;

use App\Support\TextList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $nullable = [];

        foreach (['slug', 'github_url', 'live_url', 'hero_image', 'video_url', 'category', 'short_description', 'description', 'role', 'problem', 'solution', 'technical_details', 'challenges', 'outcome'] as $field) {
            if ($this->input($field) === '') {
                $nullable[$field] = null;
            }
        }

        $this->merge(array_merge($nullable, [
            'featured' => $this->boolean('featured'),
            'published' => $this->boolean('published'),
            'live_unavailable' => $this->boolean('live_unavailable'),
            'technologies' => TextList::from($this->input('technologies')),
            'features' => TextList::fromLines($this->input('features')),
            'gallery' => TextList::fromLines($this->input('gallery')),
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'title' => ['required', 'string', 'max:160'],
            'slug' => [
                'nullable',
                'string',
                'max:180',
                Rule::unique('projects', 'slug')->ignore($project),
            ],
            'category' => ['nullable', 'string', 'max:120'],
            'short_description' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'role' => ['nullable', 'string', 'max:120'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:80'],
            'problem' => ['nullable', 'string'],
            'solution' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:2000'],
            'technical_details' => ['nullable', 'string'],
            'challenges' => ['nullable', 'string'],
            'outcome' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:500'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'live_unavailable' => ['boolean'],
            'featured' => ['boolean'],
            'published' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
