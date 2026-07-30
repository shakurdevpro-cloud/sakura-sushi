<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Catégories — Admin</title></head>
<body>
    <h1>Catégories</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif

    <a href="{{ route('admin.categories.create') }}">+ Nouvelle catégorie</a>

    <table border="1" cellpadding="6">
        <thead><tr><th>ID</th><th>Nom</th><th>Ordre</th><th>Active</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->sort_order }}</td>
                    <td>{{ $category->is_active ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.categories.show', $category) }}">Voir</a> |
                        <a href="{{ route('admin.categories.edit', $category) }}">Modifier</a> |
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
</body>
</html>