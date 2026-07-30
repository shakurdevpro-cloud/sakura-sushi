<?php
// app/Http/Controllers/Admin/GalleryController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Models\Gallery;
use App\Services\GalleryService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function __construct(protected GalleryService $galleryService) {}

    public function index()
    {
        $galleries = Gallery::orderBy('sort_order')->paginate(20);

        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(StoreGalleryRequest $request)
    {
        $data = $request->safe()->except('image');

        $this->galleryService->store($data, $request->file('image'));

        return redirect()->route('admin.gallery.index')->with('status', 'Image ajoutée.');
    }

    public function show(Gallery $gallery)
    {
        return view('admin.gallery.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'in:dishes,interior,kitchen,events'],
            'alt' => ['nullable', 'string', 'max:150'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ]);

        $gallery->update(collect($data)->except('image')->toArray());

        if ($request->hasFile('image')) {
            $this->galleryService->replaceImage($gallery, $request->file('image'));
        }

        return redirect()->route('admin.gallery.show', $gallery)->with('status', 'Mise à jour effectuée.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->galleryService->delete($gallery);

        return redirect()->route('admin.gallery.index')->with('status', 'Image supprimée.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => ['required', 'array']]);

        $this->galleryService->reorder($request->order);

        return response()->json(['message' => 'Ordre mis à jour.']);
    }
}
