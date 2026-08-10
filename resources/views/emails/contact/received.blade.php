{{-- resources/views/emails/contact/received.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <p>Nouveau message de contact reçu :</p>
    <p><strong>De :</strong> {{ $contactMessage->name }} ({{ $contactMessage->email }})</p>
    <p><strong>Téléphone :</strong> {{ $contactMessage->phone ?? '-' }}</p>
    <p><strong>Sujet :</strong> {{ $contactMessage->subject ?? '-' }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ $contactMessage->message }}</p>
</body>
</html>