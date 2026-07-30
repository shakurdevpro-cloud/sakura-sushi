<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'category', 'path', 'alt', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Convention : la miniature est stockée à côté de l'original avec le suffixe _thumb
    public function getThumbnailPathAttribute(): string
    {
        $info = pathinfo($this->path);

        return ($info['dirname'] !== '.' ? $info['dirname'] . '/' : '') . $info['filename'] . '_thumb.' . $info['extension'];
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->thumbnail_path);
    }
}