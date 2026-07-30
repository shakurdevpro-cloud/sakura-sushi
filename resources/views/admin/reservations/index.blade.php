{{-- resources/views/admin/reservations/index.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Réservations — Admin</title></head>
<body>
    <h1>Réservations</h1>

    @if (session('status'))<p>{{ session('status') }}</p>@endif

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Réf.</th><th>Client</th><th>Date</th><th>Heure</th><th>Convives</th><th>Lieu</th><th>Statut</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $r)
                <tr>
                    <td>{{ $r->reference }}</td>
                    <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                    <td>{{ $r->date->format('d/m/Y') }}</td>
                    <td>{{ $r->getRawOriginal('time') }}</td>
                    <td>{{ $r->guests }}</td>
                    <td>{{ $r->location }}</td>
                    <td>{{ $r->status->value }}</td>
                    <td><a href="{{ route('admin.reservations.show', $r) }}">Voir</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reservations->links() }}
</body>
</html>