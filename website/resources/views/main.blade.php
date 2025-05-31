@extends('layouts.app') {{-- Rozszerzamy główny layout aplikacji --}}

@section('title', 'DruidDiet - Odżywianie w Zgodzie z Naturą')

@push('styles')
    <style>
        .hero {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;

        }

        .hero-content h2 {
            font-size: 2.8rem;
            font-weight: bold;
            color: #4a6b5a;
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .about h3, .promoted-caterings h3, .diets-preview h3, .catering-preview h3 {
            color: #4a6b5a;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }
        .about .col-md-4 .druid-symbol {
            font-size: 4rem;
            color: #4a6b5a;
            margin-bottom: 0.5rem;
            line-height: 1;
        }
        .button {
            display: inline-block;
            background-color: #8b5a2b !important;
            color: #fff !important;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .button:hover {
            background-color: #6b4320 !important;
            transform: scale(1.05);
            color: #fff !important;
        }
        .card {
            border: 1px solid #e9ecef;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            color: #4a6b5a;
        }
    </style>
@endpush

@section('content')
<main>
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Odkryj Moc Natury z DruidDiet</h2>
                <p>Zdrowe odżywianie inspirowane pradawną mądrością.</p>
                <a href="{{ route('caterings.index') }}" class="button">Sprawdź nasze cateringi</a>
            </div>
        </div>
    </section>

    <section class="about my-5">
        <div class="container">
            <h3 class="text-center">Nasza Filozofia</h3>
            <p class="text-center col-md-8 mx-auto">W DruidDiet wierzymy w powrót do korzeni – do naturalnych, nieprzetworzonych produktów. Nasze diety czerpią inspirację z obfitości lasów, pól i rzek, by dostarczyć Twojemu organizmowi wszystkiego, czego potrzebuje do pełni zdrowia i witalności.</p>
            <div class="row text-center mt-4">
                <div class="col-md-4 mb-3">

                    <div class="druid-symbol">&#x1F343;</div>
                    <h4>Naturalne Składniki</h4>
                    <p>Stawiamy na produkty pochodzące prosto z natury, bez sztucznych dodatków i konserwantów.</p>
                </div>
                <div class="col-md-4 mb-3">

                    <div class="druid-symbol">🌳</div>
                    <h4>Zrównoważony Rozwój</h4>
                    <p>Dbamy o środowisko, wybierając dostawców, którzy podzielają nasze wartości.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="druid-symbol">☀️</div>
                    <h4>Energia Słońca i Ziemi</h4>
                    <p>Nasze posiłki dostarczają energii, której potrzebujesz do aktywnego życia.</p>
                </div>
            </div>
        </div>
    </section>


    @if(isset($promotedCaterings) && $promotedCaterings->count() > 0)
    <section class="promoted-caterings my-5 bg-light py-5">
        <div class="container">
            <h3 class="text-center">Polecane na Dzisiaj: {{ ucfirst($todayCateringTypeName) }}!</h3>
            <div class="row mt-4">
                @foreach($promotedCaterings as $catering)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ $catering->photo ? asset($catering->photo) : 'https://via.placeholder.com/300x200.png?text='.urlencode($catering->title) }}" class="card-img-top" alt="{{ $catering->title }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $catering->title }}</h5>
                                <p class="card-text"><small class="text-muted">Typ: {{ $catering->type }}</small></p>
                                <p class="card-text flex-grow-1"><small>{{ Str::limit($catering->description, 70) }}</small></p>
                                <p class="card-text fw-bold fs-5 mt-auto pt-2">{{ number_format($catering->price, 2, ',', ' ') }} zł</p>

                                <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $catering->catering_id }}">
                                    <input type="hidden" name="product_type" value="catering">
                                    <div class="input-group">
                                        <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="max-width: 60px;" aria-label="Ilość">
                                        <button type="submit" class="btn button btn-sm">
                                            <i class="bi bi-cart-plus"></i> Dodaj
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    <section class="diets-preview my-5">
        <div class="container">
            <h3 class="text-center">Nasze Diety</h3>
            <div class="row text-center mt-4">
                <div class="col-md-4 mb-3">
                    <h4>Dieta Leśnego Druida</h4>
                    <p>Bogata w warzywa leśne, grzyby, orzechy i jagody.</p>
                    <a href="{{ route('diets.index') }}" class="button">Zobacz wszystkie diety</a>
                </div>
                <div class="col-md-4 mb-3">
                    <h4>Dieta Rzecznego Wojownika</h4>
                    <p>Opiera się na rybach, owocach morza i roślinach wodnych.</p>
                    <a href="{{ route('diets.index') }}" class="button">Zobacz wszystkie diety</a>
                </div>
                <div class="col-md-4 mb-3">
                    <h4>Dieta Słonecznego Pielgrzyma</h4>
                    <p>Skupia się na zbożach, warzywach okopowych i owocach sezonowych.</p>
                    <a href="{{ route('diets.index') }}" class="button">Zobacz wszystkie diety</a>
                </div>
            </div>
        </div>
    </section>

    <section class="catering-preview my-5 bg-light py-5" id="catering-section">
        <div class="container">
            <h3 class="text-center">DruidDiet Katering</h3>
            <p class="text-center col-md-8 mx-auto">Oferujemy również spersonalizowane plany kateringowe, dostosowane do Twoich indywidualnych potrzeb i preferencji. Ciesz się zdrowymi i smacznymi posiłkami, które dostarczymy prosto pod Twoje drzwi.</p>
            <div class="row mt-4 text-center">
                <div class="col-md-6 mb-3">
                    <h4>Katering Indywidualny</h4>
                    <p>Dostosowana dieta do Twojego stylu życia i celów.</p>
                    <a href="{{ route('caterings.index') }}" class="button">Zobacz wszystkie cateringi</a>
                </div>
                <div class="col-md-6 mb-3">
                    <h4>Katering Firmowy</h4>
                    <p>Zdrowe posiłki dla pracowników Twojej firmy.</p>
                    <a href="{{ route('caterings.index') }}" class="button">Zobacz wszystkie cateringi</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
@endpush
