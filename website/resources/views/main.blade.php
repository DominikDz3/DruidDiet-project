<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DruidDiet - Odżywianie w Zgodzie z Naturą</title>
    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link href="@import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');" rel="stylesheet">
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

        @if(Auth::check() && Auth::user()->role === 'user')
            <li><span>Witaj, {{ Auth::user()->name }}</span></li>
            <li><a href="{{ route('user.dashboard') }}" class="login-button">Panel klienta</a></li>
        @elseif(!Auth::check())
            <li><a href="{{ route('login') }}" class="login-button">Zaloguj się</a></li>
        @endif
    </ul>
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

        <section class="about">
            <h3>Nasza Filozofia</h3>
            <p>W DruidDiet wierzymy w powrót do korzeni – do naturalnych, nieprzetworzonych produktów. Nasze diety czerpią inspirację z obfitości lasów, pól i rzek, by dostarczyć Twojemu organizmowi wszystkiego, czego potrzebuje do pełni zdrowia i witalności.</p>
            <div class="features">
                <div class="feature">
                    <img src="{{ asset('img/leaf.png') }}" alt="Liść">
                    <h4>Naturalne Składniki</h4>
                    <p>Stawiamy na produkty pochodzące prosto z natury, bez sztucznych dodatków i konserwantów.</p>
                </div>
                <div class="feature">
                    <img src="{{ asset('img/tree.png') }}" alt="Drzewo">
                    <h4>Zrównoważony Rozwój</h4>
                    <p>Dbamy o środowisko, wybierając dostawców, którzy podzielają nasze wartości.</p>
                </div>
                <div class="feature">
                    <img src="{{ asset('img/sun.png') }}" alt="Słońce">
                    <h4>Energia Słońca i Ziemi</h4>
                    <p>Nasze posiłki dostarczają energii, której potrzebujesz do aktywnego życia.</p>
                </div>
            </div>
        </section>

        <section class="diets">
            <h3>Nasze Diety</h3>
            <div class="diet-plans">
                <div class="diet-plan">
                    <h4>Dieta Leśnego Druida</h4>
                    <p>Bogata w warzywa leśne, grzyby, orzechy i jagody.</p>
                    <a href="#" class="button-secondary">Zobacz plan</a>
                </div>
                <div class="diet-plan">
                    <h4>Dieta Rzecznego Wojownika</h4>
                    <p>Opiera się na rybach, owocach morza i roślinach wodnych.</p>
                    <a href="#" class="button-secondary">Zobacz plan</a>
                </div>
                <div class="diet-plan">
                    <h4>Dieta Słonecznego Pielgrzyma</h4>
                    <p>Skupia się na zbożach, warzywach okopowych i owocach sezonowych.</p>
                    <a href="#" class="button-secondary">Zobacz plan</a>
                </div>
            </div>
        </section>

        <section class="catering" id="catering-section">
            <h3>DruidDiet Catering</h3>
            <p>Oferujemy również spersonalizowane plany cateringowe, dostosowane do Twoich indywidualnych potrzeb i preferencji. Ciesz się zdrowymi i smacznymi posiłkami, które dostarczymy prosto pod Twoje drzwi.</p>
            <div class="catering-options">
                <div class="catering-option">
                    <h4>Catering Indywidualny</h4>
                    <p>Dostosowana dieta do Twojego stylu życia i celów.</p>
                    <a href="#" class="button-secondary">Dowiedz się więcej</a>
                </div>
                <div class="catering-option">
                    <h4>Catering Firmowy</h4>
                    <p>Zdrowe posiłki dla pracowników Twojej firmy.</p>
                    <a href="#" class="button-secondary">Dowiedz się więcej</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 DruidDiet. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
