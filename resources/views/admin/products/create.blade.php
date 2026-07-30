{{-- resources/views/admin/products/create.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Nouveau produit</title></head>
<body>
    <h1>Nouveau produit</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Catégorie</label>
        <select name="category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <label>Nom</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Description</label>
        <textarea name="description">{{ old('description') }}</textarea>

        <label>Prix (en centimes)</label>
        <input type="number" name="price" value="{{ old('price') }}" required>

        <label>Prix original (optionnel)</label>
        <input type="number" name="price_original" value="{{ old('price_original') }}">

        <label>SKU</label>
        <input type="text" name="sku" value="{{ old('sku') }}">

        <label>Stock (-1 = illimité)</label>
        <input type="number" name="stock" value="{{ old('stock', -1) }}">

        <label>
            <input type="checkbox" name="is_active" value="1" checked> Actif
        </label>

        <label>
            <input type="checkbox" name="is_featured" value="1"> Mis en avant
        </label>

        <label>Images</label>
        <input type="file" name="images[]" multiple accept="image/*">

        <button type="submit">Créer</button>
    </form>
</body>
</html>