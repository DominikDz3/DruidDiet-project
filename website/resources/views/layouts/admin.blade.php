<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DruidDiet - Panel Administratora')</title> {{-- Zmieniony domyślny tytuł --}}

    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome dla ikon (opcjonalnie, jeśli chcesz używać w przyciskach) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @stack('styles') {{-- Miejsce na dodatkowe style specyficzne dla widoku --}}
</head>
<body>
    <div class="admin-layout">
        <header class="topbar">
            <h1>Panel Administratora</h1>
            <div>
                @auth {{-- Sprawdzenie czy użytkownik jest zalogowany --}}
                    <span class="me-3">Zalogowany: {{ Auth::user()->name }} {{ Auth::user()->surname }} ({{ Auth::user()->role }})</span>
                    {{-- Usunięto zdublowany formularz wylogowania z topbara, zostaje ten w sidebarze --}}
                @endauth
            </div>
        </header>

        <div class="admin-main">
            <nav class="sidebar">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard {{-- Dodano ikonę --}}
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"> {{-- Zaktualizowany link i warunek active --}}
                        <i class="fas fa-users me-2"></i> Użytkownicy {{-- Dodano ikonę --}}
                    </a>
                    <a href="#" class="nav-link {{-- request()->routeIs('admin.diets*') ? 'active' : '' --}}"> {{-- Zaktualizuj, jeśli masz Diety --}}
                        <i class="fas fa-utensils me-2"></i> Diety {{-- Dodano ikonę (przykład) --}}
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart me-2"></i> Zamówienia {{-- Dodano ikonę --}}
                    </a>
                    {{-- Możesz dodać więcej linków tutaj --}}
                </div>

                <div class="sidebar-bottom">
                    @auth {{-- Formularz wylogowania tylko jeśli użytkownik jest zalogowany --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Wyloguj {{-- Dodano ikonę --}}
                        </button>
                    </form>
                    @endauth
                </div>
            </nav>

            <main class="content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Skrypty Bootstrapa (przeniesione na koniec body dla lepszej wydajności) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') {{-- Miejsce na dodatkowe skrypty specyficzne dla widoku --}}
</body>
</html>
