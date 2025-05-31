<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DruidDiet - Panel Administratora')</title>

    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <header class="topbar">
            <h1>Panel Administratora</h1>
            <div>
                @auth
                    <span class="me-3">Zalogowany: {{ Auth::user()->name }} {{ Auth::user()->surname }} ({{ Auth::user()->role }})</span>
                @endauth
            </div>
        </header>

        <div class="admin-main">
            <nav class="sidebar">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Użytkownicy
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                        <i class="fas fa-tags me-2"></i> Kody Rabatowe
                    </a>
                    <a href="#" class="nav-link {{-- request()->routeIs('admin.diets*') ? 'active' : '' --}}">
                        <i class="fas fa-utensils me-2"></i> Diety (Wkrótce)
                    </a>
                    <a href="{{ route('admin.caterings.index') }}" class="nav-link {{ request()->routeIs('admin.caterings*') ? 'active' : '' }}">
                        <i class="fas fa-box-open me-2"></i> Kateringi
                    </a>
                    {{-- NOWY LINK DO KOMENTARZY --}}
                    <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments*') ? 'active' : '' }}">
                        <i class="fas fa-comments me-2"></i> Komentarze i Oceny
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart me-2"></i> Zamówienia
                    </a>
                </div>

                <div class="sidebar-bottom">
                    @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Wyloguj
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>