<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = Gallery::active()
            ->when($request->category, fn ($q, $category) => $q->ofCategory($category))
            ->orderBy('sort_order')
            ->get();

        return response()->json($galleries->map(fn (Gallery $g) => [
            'id' => $g->id,
            'title' => $g->title,
            'description' => $g->description,
            'category' => $g->category,
            'url' => $g->url,
            'thumbnail_url' => $g->thumbnail_url,
            'alt' => $g->alt,
        ]));
    }

    public function show(Gallery $gallery)
    {
        if (! $gallery->is_active) {
            abort(404);
        }

        return response()->json([
            'id' => $gallery->id,
            'title' => $gallery->title,
            'description' => $gallery->description,
            'category' => $gallery->category,
            'url' => $gallery->url,
            'thumbnail_url' => $gallery->thumbnail_url,
            'alt' => $gallery->alt,
        ]);
    }
}