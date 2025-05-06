<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DruidDiet')</title>
    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100..900&family=Roboto:wght@100..900&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <header>
        <h1>DruidDiet</h1>
        <nav>
            <ul>
                <li><a href="#">O nas</a></li>
                <li><a href="#">Diety</a></li>
                <li><a href="#catering-section">Catering</a></li>
                <li><a href="#">Kontakt</a></li>
                @auth
                    @if(Auth::user()->role === 'user')
                        <li><span>Witaj, {{ Auth::user()->name }}</span></li>
                        <li><a href="{{ route('user.dashboard') }}" class="login-button">Panel klienta</a></li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}" class="login-button">Zaloguj się</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2025 DruidDiet. Wszelkie prawa zastrzeżone.</p>
    </footer>

    @stack('scripts')
</body>
</html>
