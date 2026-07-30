<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['category', 'primaryImage'])
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->category, fn ($q, $id) => $q->byCategory($id))
            ->orderBy($request->sort ?? 'sort_order')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->safe()->except('images');
        $images = $request->file('images', []);

        $product = $this->productService->store($data, $images);

        return redirect()->route('admin.products.show', $product)->with('status', 'Produit créé.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->update($product, $request->validated());

        return redirect()->route('admin.products.show', $product)->with('status', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $this->productService->flushCache();

        return redirect()->route('admin.products.index')->with('status', 'Produit supprimé.');
    }

    public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:4096'],
        ]);

        $this->productService->uploadImages($product, $request->file('images'));

        return back()->with('status', 'Images ajoutées.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        $this->productService->deleteImage($image);

        return back()->with('status', 'Image supprimée.');
    }

    public function reorder(Request $request, Product $product)
    {
        $request->validate(['order' => ['required', 'array']]);

        $this->productService->reorderImages($product, $request->order);

        return response()->json(['message' => 'Ordre mis à jour.']);
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);
        $this->productService->flushCache();

        return back()->with('status', 'Statut mis à jour.');
    }
}