{{-- resources/views/admin/products/edit.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Modifier {{ $product->name }}</title></head>
<body>
    <h1>Modifier : {{ $product->name }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf @method('PUT')

        <label>Catégorie</label>
        <select name="category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected($category->id === $product->category_id)>{{ $category->name }}</option>
            @endforeach
        </select>

        <label>Nom</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

        <label>Description</label>
        <textarea name="description">{{ old('description', $product->description) }}</textarea>

        <label>Prix (en centimes)</label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" required>

        <label>Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}">

        <label>
            <input type="checkbox" name="is_active" value="1" @checked($product->is_active)> Actif
        </label>

        <label>
            <input type="checkbox" name="is_featured" value="1" @checked($product->is_featured)> Mis en avant
        </label>

        <button type="submit">Enregistrer</button>
    </form>

    <h2>Images</h2>
    <ul>
        @foreach ($product->images as $image)
            <li>
                {{ $image->path }} {{ $image->is_primary ? '(principale)' : '' }}
                <form action="{{ route('admin.products.images.destroy', [$product, $image]) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>
            </li>
        @endforeach
    </ul>

    <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="images[]" multiple accept="image/*">
        <button type="submit">Ajouter des images</button>
    </form>
</body>
</html>