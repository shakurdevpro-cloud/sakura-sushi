<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Messages de contact — Admin</title></head>
<body>
    <h1>Messages de contact</h1>

    <table border="1" cellpadding="6">
        <thead><tr><th>Nom</th><th>Email</th><th>Sujet</th><th>Reçu le</th><th>Lu</th><th></th></tr></thead>
        <tbody>
            @foreach ($messages as $m)
                <tr>
                    <td>{{ $m->name }}</td>
                    <td>{{ $m->email }}</td>
                    <td>{{ $m->subject ?? '-' }}</td>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $m->is_read ? 'Oui' : 'Non' }}</td>
                    <td><a href="{{ route('admin.contact-messages.show', $m) }}">Voir</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $messages->links() }}
</body>
</html>