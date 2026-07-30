<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function store(array $data, array $images = []): Product
    {
        $product = Product::create($data);

        if (! empty($images)) {
            $this->uploadImages($product, $images);
        }

        $this->flushCache();

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        if (! empty($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = Product::generateUniqueSlug($data['name']);
        }

        $product->update($data);

        $this->flushCache();

        return $product->fresh();
    }

    public function uploadImages(Product $product, array $files): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($files as $index => $file) {
            /** @var UploadedFile $file */
            $path = $file->store('products', 'public');

            $product->images()->create([
                'path' => $path,
                'is_primary' => ! $hasPrimary && $index === 0,
                'sort_order' => $product->images()->count(),
            ]);
        }

        $this->flushCache();
    }

    public function deleteImage(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->path);

        $product = $image->product;
        $wasPrimary = $image->is_primary;

        $image->delete();

        if ($wasPrimary) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }

        $this->flushCache();
    }

    public function reorderImages(Product $product, array $order): void
    {
        foreach ($order as $position => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id)
                ->update(['sort_order' => $position]);
        }
    }

    public function getActiveProducts(array $filters = [])
    {
        $key = 'products:' . md5(serialize($filters));

        return Cache::remember($key, now()->addMinutes(10), function () use ($filters) {
            return Product::active()
                ->with(['category', 'primaryImage'])
                ->when($filters['category'] ?? null, fn ($q, $id) => $q->byCategory($id))
                ->when($filters['search'] ?? null, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->when($filters['tags'] ?? null, function ($q, $tags) {
                    foreach ((array) $tags as $tag) {
                        $q->orWhereJsonContains('tags', $tag);
                    }
                })
                ->when($filters['featured'] ?? null, fn ($q) => $q->featured())
                ->paginate(12);
        });
    }

    public function flushCache(): void
    {
        // Invalide tout le cache applicatif — simple et fiable quel que soit
        // le driver configuré (redis, array, etc.). Si le cache sert d'autres
        // besoins que le catalogue, on pourra passer à un flush par pattern Redis.
        Cache::flush();
    }
}