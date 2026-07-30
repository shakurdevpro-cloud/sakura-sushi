<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Modifier {{ $category->name }}</title></head>
<body>
    <h1>Modifier : {{ $category->name }}</h1>

    @if ($errors->any())
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label>Nom</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required>

        <label>Description</label>
        <textarea name="description">{{ old('description', $category->description) }}</textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*">
        @if ($category->image)<p>Actuelle : {{ $category->image }}</p>@endif

        <label>Ordre</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}">

        <label><input type="checkbox" name="is_active" value="1" @checked($category->is_active)> Active</label>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>