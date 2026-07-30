<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'price_formatted' => $this->price_formatted,
            'price_original' => $this->price_original,
            'is_on_sale' => $this->is_on_sale,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'tags' => $this->tags,
            'allergens' => $this->allergens,
            'calories' => $this->calories,
            'primary_image' => $this->whenLoaded('primaryImage', fn () => $this->primaryImage?->path),
            'images' => $this->whenLoaded('images', fn () => $this->images->pluck('path')),
        ];
    }
}