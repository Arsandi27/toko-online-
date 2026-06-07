<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Admin') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array{name: string, slug: string, description: string|null, is_active: bool}
     */
    public function categoryData(): array
    {
        return [
            'name' => $this->string('name')->toString(),
            'slug' => Str::slug($this->string('name')->toString()),
            'description' => $this->string('description')->toString() ?: null,
            'is_active' => $this->boolean('is_active'),
        ];
    }
}
