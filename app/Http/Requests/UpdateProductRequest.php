<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array{category_id: int, name: string, slug: string, description: string|null, price: int, stock: int, is_active: bool}
     */
    public function productData(): array
    {
        return [
            'category_id' => $this->integer('category_id'),
            'name' => $this->string('name')->toString(),
            'slug' => Str::slug($this->string('name')->toString()),
            'description' => $this->string('description')->toString() ?: null,
            'price' => $this->integer('price'),
            'stock' => $this->integer('stock'),
            'is_active' => $this->boolean('is_active'),
        ];
    }
}
