@extends('layouts.app')

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
        }
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        /* Ujednolicony styl dla nagłówków sekcji promocyjnych i podglądowych */
        .promoted-caterings h3, .promoted-diets h3, .diets-preview h3, .catering-preview h3, .about h3 {
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
            height: 200px; /* Utrzymujemy stałą wysokość */
            object-fit: cover; /* Kluczowe dla równego wypełnienia przy różnych proporcjach */
            background-color: #f8f9fa; /* Tło dla placeholderów lub gdy obrazek nie pokrywa całości (przy contain) */
        }
        .card-title {
            color: #4a6b5a;
            min-height: 2.5em; /* Przykładowa minimalna wysokość dla 2 linii tekstu, aby wyrównać karty */
        }
        .card-text.flex-grow-1 {
             min-height: 4.5em; /* Przykładowa minimalna wysokość dla opisu */
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
            <h3 class="text-center">Polecane na Dzisiaj: {{ ucfirst($promotedCateringDisplayType) }}!</h3>
            <div class="row mt-4">
                @foreach($promotedCaterings as $catering)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ (!empty($catering->photo) && is_string($catering->photo)) ? asset($catering->photo) : 'https://via.placeholder.com/300x200.png?text='.urlencode($catering->title) }}" class="card-img-top" alt="{{ $catering->title }}">
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

    @if(isset($promotedDiets) && $promotedDiets->count() > 0)
    <section class="promoted-diets my-5 py-5">
        <div class="container">
            <h3 class="text-center">Polecana Dieta Dnia: {{ ucfirst($promotedDietDisplayType) }}!</h3>
            <div class="row mt-4">
                @foreach($promotedDiets as $diet)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ (!empty($diet->photo) && is_string($diet->photo)) ? asset($diet->photo) : 'https://via.placeholder.com/300x200.png?text='.urlencode($diet->title) }}" class="card-img-top" alt="{{ $diet->title }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $diet->title }}</h5>
                                <p class="card-text"><small class="text-muted">Typ: {{ $diet->type }}</small></p>
                                <p class="card-text"><small class="text-muted">Kalorie: {{ $diet->calories }} kcal</small></p>
                                <p class="card-text flex-grow-1"><small>{{ Str::limit($diet->description, 70) }}</small></p>
                                <p class="card-text fw-bold fs-5 mt-auto pt-2">{{ number_format($diet->price, 2, ',', ' ') }} zł</p>
                                <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $diet->diet_id }}">
                                    <input type="hidden" name="product_type" value="diet">
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

    <section class="catering-preview my-5 bg-light py-5" id="catering-section">
        <div class="container">
            <h3 class="text-center">DruidDiet Katering</h3>
            <p class="text-center col-md-8 mx-auto">Oferujemy również spersonalizowane plany kateringowe, dostosowane do Twoich indywidualnych potrzeb i preferencji. Ciesz się zdrowymi i smacznymi posiłkami, które dostarczymy prosto pod Twoje drzwi.</p>
        </div>

    </section>
</main>
@endsection

@push('scripts')
@endpush
