<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'search', 'tags', 'featured']);

        return new ProductCollection($this->productService->getActiveProducts($filters));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new ProductResource($product);
    }
}