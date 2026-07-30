<?php

namespace App\Services;

use App\Models\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class GalleryService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = ImageManager::usingDriver(Driver::class);
    }

    public function store(array $data, UploadedFile $file): Gallery
    {
        $relativePath = $this->processAndSaveImage($file);

        return Gallery::create([
            ...$data,
            'path' => $relativePath,
        ]);
    }

    public function replaceImage(Gallery $gallery, UploadedFile $file): Gallery
    {
        Storage::disk('public')->delete([$gallery->path, $gallery->thumbnail_path]);

        $relativePath = $this->processAndSaveImage($file);

        $gallery->update(['path' => $relativePath]);

        return $gallery->fresh();
    }

    public function delete(Gallery $gallery): void
    {
        Storage::disk('public')->delete([$gallery->path, $gallery->thumbnail_path]);
        $gallery->delete();
    }

    public function reorder(array $order): void
    {
        foreach ($order as $position => $id) {
            Gallery::where('id', $id)->update(['sort_order' => $position]);
        }
    }

    protected function processAndSaveImage(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $relativePath = 'gallery/' . $filename;
        $thumbPath = 'gallery/' . pathinfo($filename, PATHINFO_FILENAME) . '_thumb.' . $file->getClientOriginalExtension();

        Storage::disk('public')->makeDirectory('gallery');

        $fullPath = Storage::disk('public')->path($relativePath);
        $fullThumbPath = Storage::disk('public')->path($thumbPath);

        $original = $this->manager->decode($file->getRealPath());
        $original->scaleDown(width: 1200);
        $original->save($fullPath);

        $thumbnail = $this->manager->decode($file->getRealPath());
        $thumbnail->scaleDown(width: 400);
        $thumbnail->save($fullThumbPath);

        return $relativePath;
    }
}
