<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'end_year' => $this->filled('end_year') ? $this->input('end_year') : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'institution' => ['required', 'string', 'max:180'],
            'degree' => ['required', 'string', 'max:180'],
            'field' => ['nullable', 'string', 'max:180'],
            'start_year' => ['required', 'integer', 'min:1990', 'max:2100'],
            'end_year' => ['nullable', 'integer', 'min:1990', 'max:2100', 'gte:start_year'],
            'description' => ['nullable', 'string'],
        ];
    }
}
