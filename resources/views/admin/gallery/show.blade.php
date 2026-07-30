{{-- resources/views/admin/gallery/show.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>{{ $gallery->title }}</title></head>
<body>
    <h1>{{ $gallery->title }}</h1>

    @if (session('status'))<p>{{ session('status') }}</p>@endif

    <img src="{{ $gallery->url }}" alt="{{ $gallery->alt }}" width="400">

    <p><strong>Catégorie :</strong> {{ $gallery->category }}</p>
    <p><strong>Description :</strong> {{ $gallery->description }}</p>
    <p><strong>Active :</strong> {{ $gallery->is_active ? 'Oui' : 'Non' }}</p>

    <a href="{{ route('admin.gallery.edit', $gallery) }}">Modifier</a> |
    <a href="{{ route('admin.gallery.index') }}">Retour à la liste</a>
</body>
</html>