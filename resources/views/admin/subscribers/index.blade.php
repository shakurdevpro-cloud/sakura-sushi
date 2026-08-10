<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Abonnés newsletter — Admin</title></head>
<body>
    <h1>Abonnés newsletter</h1>

    @if (session('status'))<p>{{ session('status') }}</p>@endif

    <form action="{{ route('admin.subscribers.export') }}" method="POST">
        @csrf
        <button type="submit">Exporter en CSV</button>
    </form>

    <table border="1" cellpadding="6">
        <thead><tr><th>Email</th><th>Nom</th><th>Source</th><th>Confirmé</th><th>Désabonné</th></tr></thead>
        <tbody>
            @foreach ($subscribers as $s)
                <tr>
                    <td>{{ $s->email }}</td>
                    <td>{{ $s->name ?? '-' }}</td>
                    <td>{{ $s->source ?? '-' }}</td>
                    <td>{{ $s->confirmed_at ? 'Oui' : 'Non' }}</td>
                    <td>{{ $s->unsubscribed_at ? 'Oui' : 'Non' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $subscribers->links() }}
</body>
</html>