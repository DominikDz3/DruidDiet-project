<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DruidDiet - Odżywianie w Zgodzie z Naturą')</title>
    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@stack('styles')
</head>
<body>
    <header class="bg-light shadow-sm border-bottom">
            <h1 class="m-0">DruidDiet</h1>
            <nav class="d-flex align-items-center gap-4">
                <ul class="nav gap-3 mb-0">
                    <li class="nav-item"><a href="#">O nas</a></li>
                    <li class="nav-item"><a href="{{ route('diets.index') }}">Diety</a></li>
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
                    <a href="{{ route('login') }}" class="button login-button">Zaloguj się</a>
                @endauth
            </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>Odkryj Moc Natury z DruidDiet</h2>
                <p>Zdrowe odżywianie inspirowane pradawną mądrością.</p>
                <a href="#" class="button">Dowiedz się więcej</a>
            </div>
        </section>

        <section class="about mb-5">
            <h3>Nasza Filozofia</h3>
            <p>W DruidDiet wierzymy w powrót do korzeni – do naturalnych, nieprzetworzonych produktów. Nasze diety czerpią inspirację z obfitości lasów, pól i rzek, by dostarczyć Twojemu organizmowi wszystkiego, czego potrzebuje do pełni zdrowia i witalności.</p>
            <div class="row text-center mt-4">
                <div class="col-md-4">
                    <img src="{{ asset('img/leaf.png') }}" alt="Liść" class="mb-2" style="height: 64px;">
                    <h4>Naturalne Składniki</h4>
                    <p>Stawiamy na produkty pochodzące prosto z natury, bez sztucznych dodatków i konserwantów.</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('img/tree.png') }}" alt="Drzewo" class="mb-2" style="height: 64px;">
                    <h4>Zrównoważony Rozwój</h4>
                    <p>Dbamy o środowisko, wybierając dostawców, którzy podzielają nasze wartości.</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('img/sun.png') }}" alt="Słońce" class="mb-2" style="height: 64px;">
                    <h4>Energia Słońca i Ziemi</h4>
                    <p>Nasze posiłki dostarczają energii, której potrzebujesz do aktywnego życia.</p>
                </div>
            </div>
        </section>

        <section class="diets mb-5">
            <h3>Nasze Diety</h3>
            <div class="row text-center mt-4">
                <div class="col-md-4">
                    <h4>Dieta Leśnego Druida</h4>
                    <p>Bogata w warzywa leśne, grzyby, orzechy i jagody.</p>
                    <a href="#" class="button">Zobacz plan</a>
                </div>
                <div class="col-md-4">
                    <h4>Dieta Rzecznego Wojownika</h4>
                    <p>Opiera się na rybach, owocach morza i roślinach wodnych.</p>
                    <a href="#" class="button">Zobacz plan</a>
                </div>
                <div class="col-md-4">
                    <h4>Dieta Słonecznego Pielgrzyma</h4>
                    <p>Skupia się na zbożach, warzywach okopowych i owocach sezonowych.</p>
                    <a href="#" class="button">Zobacz plan</a>
                </div>
            </div>
        </section>

        <section class="catering mb-5" id="catering-section">
            <h3>DruidDiet Catering</h3>
            <p>Oferujemy również spersonalizowane plany cateringowe, dostosowane do Twoich indywidualnych potrzeb i preferencji. Ciesz się zdrowymi i smacznymi posiłkami, które dostarczymy prosto pod Twoje drzwi.</p>
            <div class="row mt-4 text-center">
                <div class="col-md-6">
                    <h4>Catering Indywidualny</h4>
                    <p>Dostosowana dieta do Twojego stylu życia i celów.</p>
                    <a href="#" class="button">Dowiedz się więcej</a>
                </div>
                <div class="col-md-6">
                    <h4>Catering Firmowy</h4>
                    <p>Zdrowe posiłki dla pracowników Twojej firmy.</p>
                    <a href="#" class="button">Dowiedz się więcej</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="text-center py-4 border-top">
        <p>&copy; 2025 DruidDiet. Wszelkie prawa zastrzeżone.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
