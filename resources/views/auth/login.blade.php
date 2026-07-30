{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — Sakura Sushi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Connexion</h1>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <label>
            <input type="checkbox" name="remember"> Se souvenir de moi
        </label>

        <button type="submit">Se connecter</button>
    </form>

    <p>Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
</body>
</html>