<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DruidDiet')</title>
    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg w-100">
    <div class="w-100 d-flex justify-content-between align-items-center px-3">
        <a class="navbar-brand">DruidDiet</a>        
        <div class="d-flex align-items-center gap-3">
            <a class="nav-link" href="{{ route('home') }}">Strona główna</a>
            @if(Auth::check())
                <span class="nav-link">Witaj, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Wyloguj</button>
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}">Zaloguj się</a>
            @endif
        </div>
    </div>
</nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} DruidDiet</p>
    </footer>
