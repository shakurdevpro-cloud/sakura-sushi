{{-- resources/views/admin/gallery/create.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Nouvelle image</title></head>
<body>
    <h1>Nouvelle image</h1>

    @if ($errors->any())
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Titre</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>Description</label>
        <textarea name="description">{{ old('description') }}</textarea>

        <label>Catégorie</label>
        <select name="category" required>
            <option value="dishes">Plats</option>
            <option value="interior">Intérieur</option>
            <option value="kitchen">Cuisine</option>
            <option value="events">Événements</option>
        </select>

        <label>Texte alternatif</label>
        <input type="text" name="alt" value="{{ old('alt') }}">

        <label>Image (JPEG, PNG, WebP — 5MB max)</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>

        <label><input type="checkbox" name="is_active" value="1" checked> Active</label>

        <button type="submit">Créer</button>
    </form>
</body>
</html>