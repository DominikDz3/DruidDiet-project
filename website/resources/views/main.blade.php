@extends('layouts.app') {{-- Zakładając, że app.blade.php jest w resources/views/layouts/ --}}

@section('title', 'DruidDiet - Odżywianie w Zgodzie z Naturą') {{-- Możesz to też ustawić w kontrolerze --}}

@section('content')
    <section class="hero">
        <div class="hero-content">
            <h2>Odkryj Moc Natury z DruidDiet</h2>
            <p>Zdrowe odżywianie inspirowane pradawną mądrością.</p>
            <a href="{{ route('caterings.index') }}" class="button">Sprawdź nasze diety</a>
        </div>
    </section>

    <section class="about my-5"> {{-- Używam my-5 zamiast mb-5 dla spójnego marginesu góra/dół --}}
        <div class="container"> {{-- Dodaję kontener dla lepszego wyrównania --}}
            <h3 class="text-center">Nasza Filozofia</h3>
            <p class="text-center">W DruidDiet wierzymy w powrót do korzeni – do naturalnych, nieprzetworzonych produktów. Nasze diety czerpią inspirację z obfitości lasów, pól i rzek, by dostarczyć Twojemu organizmowi wszystkiego, czego potrzebuje do pełni zdrowia i witalności.</p>
            <div class="row text-center mt-4">
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('img/leaf.png') }}" alt="Liść" class="mb-2" style="height: 64px;">
                    <h4>Naturalne Składniki</h4>
                    <p>Stawiamy na produkty pochodzące prosto z natury, bez sztucznych dodatków i konserwantów.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('img/tree.png') }}" alt="Drzewo" class="mb-2" style="height: 64px;">
                    <h4>Zrównoważony Rozwój</h4>
                    <p>Dbamy o środowisko, wybierając dostawców, którzy podzielają nasze wartości.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('img/sun.png') }}" alt="Słońce" class="mb-2" style="height: 64px;">
                    <h4>Energia Słońca i Ziemi</h4>
                    <p>Nasze posiłki dostarczają energii, której potrzebujesz do aktywnego życia.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="diets my-5 bg-light py-5"> {{-- Dodaję bg-light i py-5 dla lepszego wyglądu --}}
        <div class="container">
            <h3 class="text-center">Nasze Diety</h3>
            <div class="row text-center mt-4">
                <div class="col-md-4 mb-3">
                    <h4>Dieta Leśnego Druida</h4>
                    <p>Bogata w warzywa leśne, grzyby, orzechy i jagody.</p>
                    <a href="{{ route('diets.index') }}" class="button">Zobacz plan</a>
                </div>
                <div class="col-md-4 mb-3">
                    <h4>Dieta Rzecznego Wojownika</h4>
                    <p>Opiera się na rybach, owocach morza i roślinach wodnych.</p>
                    <a href="{{ route('diets.index') }}" class="button">Zobacz plan</a>
                </div>
                <div class="col-md-4 mb-3">
                    <h4>Dieta Słonecznego Pielgrzyma</h4>
                    <p>Skupia się na zbożach, warzywach okopowych i owocach sezonowych.</p>
                    <a href="{{ route('diets.index') }}" class="button">Zobacz plan</a>
                </div>
            </div>
        </div>
    </section>

    <section class="catering my-5" id="catering-section">
        <div class="container">
            <h3 class="text-center">DruidDiet Katering</h3>
            <p class="text-center">Oferujemy również spersonalizowane plany kateringowe, dostosowane do Twoich indywidualnych potrzeb i preferencji. Ciesz się zdrowymi i smacznymi posiłkami, które dostarczymy prosto pod Twoje drzwi.</p>
            <div class="row mt-4 text-center">
                <div class="col-md-6 mb-3">
                    <h4>Katering Indywidualny</h4>
                    <p>Dostosowana dieta do Twojego stylu życia i celów.</p>
                    <a href="{{ route('caterings.index') }}" class="button">Dowiedz się więcej</a>
                </div>
                <div class="col-md-6 mb-3">
                    <h4>Katering Firmowy</h4>
                    <p>Zdrowe posiłki dla pracowników Twojej firmy.</p>
                    <a href="{{ route('caterings.index') }}" class="button">Dowiedz się więcej</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    {{-- Tutaj możesz dodać style specyficzne tylko dla strony głównej, jeśli potrzebujesz --}}
    <style>
        /* Przykładowy dodatkowy styl dla main.blade.php */
        .hero {
            min-height: 70vh; /* Zapewnia, że sekcja hero jest odpowiednio wysoka */
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@push('scripts')
    {{-- Tutaj możesz dodać skrypty JS specyficzne tylko dla strony głównej --}}
@endpush