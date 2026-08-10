<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <p>Bonjour {{ $name ?? '' }},</p>
    <p>Merci de vous être inscrit à la newsletter Sakura Sushi. Cliquez sur le lien ci-dessous pour confirmer votre abonnement :</p>
    <p><a href="{{ $confirmUrl }}">Confirmer mon abonnement</a></p>
    <p>Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
</body>
</html>