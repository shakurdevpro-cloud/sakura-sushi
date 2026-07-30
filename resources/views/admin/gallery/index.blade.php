{{-- resources/views/admin/gallery/index.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Galerie — Admin</title>
</head>

<body>
    <h1>Galerie</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <a href="{{ route('admin.gallery.create') }}">+ Ajouter une image</a>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Miniature</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($galleries as $item)
                <tr>
                    <td><img src="{{ $item->thumbnail_url }}" alt="{{ $item->alt }}" width="100"></td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->is_active ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.gallery.show', $item) }}">Voir</a> |
                        <a href="{{ route('admin.gallery.edit', $item) }}">Modifier</a> |
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $galleries->links() }}
</body>

</html>
