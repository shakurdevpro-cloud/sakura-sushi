{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription — Sakura Sushi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Inscription</h1>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="name">Nom</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>

        <label for="phone">Téléphone</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone') }}">

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
</body>
</html>