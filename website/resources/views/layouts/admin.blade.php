<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DruidDiet')</title>

    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <header class="topbar">
            <h1>Panel Administratora</h1>
            <div>
                <span class="me-3">Zalogowany: {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                </form>
            </div>
        </header>

        <div class="admin-main">

        <nav class="sidebar">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="#" class="nav-link {{-- request()->routeIs('admin.users*') ? 'active' : '' --}}"> {{-- Zaktualizuj, jeśli masz Użytkowników --}}
                Użytkownicy
            </a>
            <a href="#" class="nav-link {{-- request()->routeIs('admin.diets*') ? 'active' : '' --}}"> {{-- Zaktualizuj, jeśli masz Diety --}}
                Diety
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"> {{-- <--- ZAKTUALIZOWANY LINK --}}
                Zamówienia
            </a>
                        </div>

            <div class="sidebar-bottom">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Wyloguj</button>
                </form>
            </div>
        </nav>

            <main class="content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
