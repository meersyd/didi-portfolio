<?php

namespace App\Http\Requests\Admin;

use App\Support\TextList;
use Illuminate\Foundation\Http\FormRequest;

class SiteCopyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $nullable = [];

        foreach ([
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
        ] as $field) {
            if ($this->input($field) === '') {
                $nullable[$field] = null;
            }
        }

        $this->merge(array_merge($nullable, [
            'focus' => TextList::from($this->input('focus')),
            'remove_resume' => $this->boolean('remove_resume'),
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'title' => ['nullable', 'string', 'max:120'],
            'headline' => ['nullable', 'string', 'max:400'],
            'tagline' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:120'],
            'meta_degree' => ['nullable', 'string', 'max:120'],
            'meta_discipline' => ['nullable', 'string', 'max:120'],
            'about_heading' => ['nullable', 'string', 'max:180'],
            'about_body' => ['nullable', 'string'],
            'currently_heading' => ['nullable', 'string', 'max:180'],
            'currently_body' => ['nullable', 'string'],
            'availability' => ['nullable', 'string', 'max:120'],
            'focus' => ['nullable', 'array'],
            'focus.*' => ['string', 'max:80'],
            'resume' => ['nullable', 'file', 'extensions:pdf', 'max:10240'],
            'remove_resume' => ['sometimes', 'boolean'],
        ];
    }
}
