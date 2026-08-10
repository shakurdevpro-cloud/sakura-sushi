<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Avis clients — Admin</title></head>
<body>
    <h1>Avis clients</h1>

    @if (session('status'))<p>{{ session('status') }}</p>@endif

    <table border="1" cellpadding="6">
        <thead><tr><th>Note</th><th>Titre</th><th>Corps</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach ($reviews as $r)
                <tr>
                    <td>{{ $r->rating }}/5</td>
                    <td>{{ $r->title ?? '-' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($r->body, 80) }}</td>
                    <td>{{ $r->status->value }}</td>
                    <td>
                        @if ($r->status->value === 'pending')
                            <form action="{{ route('admin.reviews.approve', $r) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit">Approuver</button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $r) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit">Rejeter</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reviews->links() }}
</body>
</html>