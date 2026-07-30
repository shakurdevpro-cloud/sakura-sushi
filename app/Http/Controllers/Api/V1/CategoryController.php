<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(
            Category::active()->orderBy('sort_order')->get()
        );
    }

    public function show(string $slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();

        return new CategoryResource($category);
    }
}