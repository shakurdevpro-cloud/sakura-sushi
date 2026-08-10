<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Message de {{ $contactMessage->name }}</title></head>
<body>
    <h1>Message de {{ $contactMessage->name }}</h1>
    <p><strong>Email :</strong> {{ $contactMessage->email }}</p>
    <p><strong>Téléphone :</strong> {{ $contactMessage->phone ?? '-' }}</p>
    <p><strong>Sujet :</strong> {{ $contactMessage->subject ?? '-' }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ $contactMessage->message }}</p>
    <a href="{{ route('admin.contact-messages.index') }}">Retour à la liste</a>
</body>
</html>