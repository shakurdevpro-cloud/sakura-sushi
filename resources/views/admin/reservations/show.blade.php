{{-- resources/views/admin/reservations/show.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Réservation {{ $reservation->reference }}</title></head>
<body>
    <h1>Réservation {{ $reservation->reference }}</h1>

    @if (session('status'))<p>{{ session('status') }}</p>@endif
    @if ($errors->any())
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif

    <p><strong>Client :</strong> {{ $reservation->first_name }} {{ $reservation->last_name }} ({{ $reservation->email }}, {{ $reservation->phone }})</p>
    <p><strong>Date :</strong> {{ $reservation->date->format('d/m/Y') }} à {{ $reservation->getRawOriginal('time') }}</p>
    <p><strong>Convives :</strong> {{ $reservation->guests }}</p>
    <p><strong>Lieu :</strong> {{ $reservation->location }}</p>
    <p><strong>Statut :</strong> {{ $reservation->status->value }}</p>
    <p><strong>Occasion :</strong> {{ $reservation->occasion ?? '-' }}</p>
    <p><strong>Demandes spéciales :</strong> {{ $reservation->special_requests ?? '-' }}</p>
    <p><strong>Consentement SMS :</strong> {{ $reservation->sms_consent ? 'Oui' : 'Non' }}</p>

    @if ($reservation->status->value === 'pending')
        <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="confirmed">
            <button type="submit">Confirmer</button>
        </form>

        <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <input type="text" name="cancel_reason" placeholder="Motif d'annulation">
            <button type="submit">Annuler</button>
        </form>
    @endif

    <a href="{{ route('admin.reservations.index') }}">Retour à la liste</a>
</body>
</html>