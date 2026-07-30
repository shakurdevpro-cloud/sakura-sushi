<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Nouvelle catégorie</title></head>
<body>
    <h1>Nouvelle catégorie</h1>

    @if ($errors->any())
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Nom</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Description</label>
        <textarea name="description">{{ old('description') }}</textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*">

        <label>Ordre</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">

        <label><input type="checkbox" name="is_active" value="1" checked> Active</label>

        <button type="submit">Créer</button>
    </form>
</body>
</html>