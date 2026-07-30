{{-- resources/views/admin/products/index.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Produits — Admin</title></head>
<body>
    <h1>Produits</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <a href="{{ route('admin.products.create') }}">+ Nouveau produit</a>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Actif</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->price_formatted }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->is_active ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.products.show', $product) }}">Voir</a> |
                        <a href="{{ route('admin.products.edit', $product) }}">Modifier</a> |
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
</body>
</html>