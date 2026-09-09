<?php

namespace App\Http\Requests\Admin;

use App\Support\TextList;
use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'end_date' => $this->filled('end_date') ? $this->input('end_date') : null,
            'technologies' => TextList::from($this->input('technologies')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'max:160'],
            'role' => ['required', 'string', 'max:160'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:80'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
