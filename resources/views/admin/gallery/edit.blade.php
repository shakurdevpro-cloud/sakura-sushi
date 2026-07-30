{{-- resources/views/admin/gallery/edit.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Modifier {{ $gallery->title }}</title></head>
<body>
    <h1>Modifier : {{ $gallery->title }}</h1>

    @if ($errors->any())
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif

    <img src="{{ $gallery->thumbnail_url }}" alt="{{ $gallery->alt }}" width="150">

    <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label>Titre</label>
        <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required>

        <label>Description</label>
        <textarea name="description">{{ old('description', $gallery->description) }}</textarea>

        <label>Catégorie</label>
        <select name="category" required>
            @foreach (['dishes','interior','kitchen','events'] as $cat)
                <option value="{{ $cat }}" @selected($gallery->category === $cat)>{{ $cat }}</option>
            @endforeach
        </select>

        <label>Texte alternatif</label>
        <input type="text" name="alt" value="{{ old('alt', $gallery->alt) }}">

        <label>Remplacer l'image (optionnel)</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp">

        <label><input type="checkbox" name="is_active" value="1" @checked($gallery->is_active)> Active</label>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>