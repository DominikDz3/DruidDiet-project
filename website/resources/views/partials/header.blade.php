<header class="bg-light shadow-sm border-bottom">
    <h1 class="m-0 home-btn"><a href="{{ route('home') }}" class="text-decoration-none">DruidDiet</a></h1>
            <nav class="d-flex align-items-center gap-4">
                <ul class="nav gap-3 mb-0">
                    <li class="nav-item"><a href="#">O nas</a></li>
                    <li class="nav-item"><a href="#">Diety</a></li>
                    <li class="nav-item"><a href="#catering-section">Catering</a></li>
                    <li class="nav-item"><a href="#">Kontakt</a></li>
                </ul>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Konto</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Moje zamówienia</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Ustawienia</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Wyloguj</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-success">Zaloguj się</a>
                @endauth
            </nav>
</header>