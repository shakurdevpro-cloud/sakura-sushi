<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>{{ $category->name }}</title></head>
<body>
    <h1>{{ $category->name }}</h1>

    @if (session('status'))<p>{{ session('status') }}</p>@endif

    <p><strong>Slug :</strong> {{ $category->slug }}</p>
    <p><strong>Description :</strong> {{ $category->description }}</p>
    <p><strong>Active :</strong> {{ $category->is_active ? 'Oui' : 'Non' }}</p>
    <p><strong>Produits liés :</strong> {{ $category->products()->count() }}</p>

    <a href="{{ route('admin.categories.edit', $category) }}">Modifier</a> |
    <a href="{{ route('admin.categories.index') }}">Retour à la liste</a>
</body>
</html>