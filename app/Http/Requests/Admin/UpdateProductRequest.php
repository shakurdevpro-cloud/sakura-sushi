<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id' => ['sometimes', 'exists:categories,id'],
            'name' => ['sometimes', 'string', 'max:150'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:170', Rule::unique('products', 'slug')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'integer', 'min:0'],
            'price_original' => ['nullable', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'stock' => ['nullable', 'integer'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'allergens' => ['nullable', 'array'],
            'allergens.*' => ['string'],
            'calories' => ['nullable', 'integer'],
        ];
    }
}