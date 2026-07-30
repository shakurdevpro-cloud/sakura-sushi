{{-- resources/views/admin/products/show.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>{{ $product->name }}</title></head>
<body>
    <h1>{{ $product->name }}</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <p><strong>Catégorie :</strong> {{ $product->category->name ?? '-' }}</p>
    <p><strong>Slug :</strong> {{ $product->slug }}</p>
    <p><strong>Prix :</strong> {{ $product->price_formatted }}</p>
    <p><strong>Stock :</strong> {{ $product->stock }}</p>
    <p><strong>Actif :</strong> {{ $product->is_active ? 'Oui' : 'Non' }}</p>
    <p><strong>Mis en avant :</strong> {{ $product->is_featured ? 'Oui' : 'Non' }}</p>
    <p><strong>Description :</strong> {{ $product->description }}</p>

    <h2>Images</h2>
    <ul>
        @forelse ($product->images as $image)
            <li>{{ $image->path }} {{ $image->is_primary ? '(principale)' : '' }}</li>
        @empty
            <li>Aucune image</li>
        @endforelse
    </ul>

    <a href="{{ route('admin.products.edit', $product) }}">Modifier</a> |
    <a href="{{ route('admin.products.index') }}">Retour à la liste</a>
</body>
</html>