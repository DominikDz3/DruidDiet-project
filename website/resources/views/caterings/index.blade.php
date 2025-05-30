@extends('layouts.app')

@section('title', 'Nasze Kateringi - DruidDiet')

{{-- Usunięto @push('styles') z błędnym linkiem do nordic.css --}}

@section('content')
<main class="container mt-5">

    <div class="d-flex justify-content-end gap-2 mb-3 toggle-buttons">
        <button class="btn btn-outline-secondary btn-sm" type="button" id="toggleFiltersButton"><i class="bi bi-funnel"></i> Filtry</button>
        <button class="btn btn-outline-secondary btn-sm" type="button" id="toggleSortButton"><i class="bi bi-sort-down"></i> Sortowanie</button>
    </div>

    <section class="filters-and-sort border rounded shadow-sm" id="filtersAndSortSection">
        <div class="filters-and-sort-content p-3">
            <form method="GET" action="{{ route('caterings.index') }}" class="row g-3 align-items-start">
                <div class="col-12" id="filterOptionsContainer">
                    <h5 class="mb-3">Filtruj Kateringi</h5> {{-- Usunięto styl inline --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label range-label">Kalorie/osobę: <span id="min_calories_value" class="range-value-display">{{ $currentFilters['min_calories'] ?? 500 }}</span> - <span id="max_calories_value" class="range-value-display">{{ $currentFilters['max_calories'] ?? 4000 }}</span> kcal</label>
                            <div class="d-flex gap-2">
                                <input type="range" class="form-range" id="min_calories_range" min="500" max="4000" step="50" name="min_calories" value="{{ $currentFilters['min_calories'] ?? 500 }}">
                                <input type="range" class="form-range" id="max_calories_range" min="500" max="4000" step="50" name="max_calories" value="{{ $currentFilters['max_calories'] ?? 4000 }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label range-label">Cena: <span id="min_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['min_price'] ?? 500), 2) }}</span> - <span id="max_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['max_price'] ?? 3000), 2) }}</span> zł</label>
                            <div class="d-flex gap-2">
                                <input type="range" class="form-range" id="min_price_range" min="500" max="3000" step="50" name="min_price" value="{{ $currentFilters['min_price'] ?? 500 }}">
                                <input type="range" class="form-range" id="max_price_range" min="500" max="3000" step="50" name="max_price" value="{{ $currentFilters['max_price'] ?? 3000 }}">
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="catering_type" class="form-label">Typ Kateringu:</label>
                            <select class="form-select form-select-sm" id="catering_type" name="catering_type">
                                <option value="all" {{ ($currentFilters['catering_type'] ?? 'all') == 'all' ? 'selected' : '' }}>Wszystkie typy</option>
                                @if(isset($cateringTypes) && !$cateringTypes->isEmpty())
                                    @foreach($cateringTypes as $type)
                                        <option value="{{ $type }}" {{ ($currentFilters['catering_type'] ?? '') == $type ? 'selected' : '' }}>{{ htmlspecialchars(ucfirst($type)) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3" id="sortOptionsContainer">
                    <h5 class="mb-3">Sortuj Kateringi</h5> {{-- Usunięto styl inline --}}
                    <div class="row g-3">
                        <div class="col-md-8 col-lg-6">
                            <label for="sort_option" class="form-label">Sortuj według:</label>
                            <select class="form-select form-select-sm" id="sort_option" name="sort_option">
                                <option value="title_asc" {{ ($currentFilters['sort_option'] ?? 'title_asc') == 'title_asc' ? 'selected' : '' }}>Nazwa (A-Z)</option>
                                <option value="title_desc" {{ ($currentFilters['sort_option'] ?? '') == 'title_desc' ? 'selected' : '' }}>Nazwa (Z-A)</option>
                                <option value="price_asc" {{ ($currentFilters['sort_option'] ?? '') == 'price_asc' ? 'selected' : '' }}>Cena (Rosnąco)</option>
                                <option value="price_desc" {{ ($currentFilters['sort_option'] ?? '') == 'price_desc' ? 'selected' : '' }}>Cena (Malejąco)</option>
                                <option value="kcal_per_person_asc" {{ ($currentFilters['sort_option'] ?? '') == 'kcal_per_person_asc' ? 'selected' : '' }}>Kalorie (Rosnąco)</option>
                                <option value="kcal_per_person_desc" {{ ($currentFilters['sort_option'] ?? '') == 'kcal_per_person_desc' ? 'selected' : '' }}>Kalorie (Malejąco)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary button">Zastosuj</button>
                </div>
            </form>
        </div>
    </section>

    <section class="suggested-caterings-section shadow-sm mt-5">
        <h3 class="text-center mb-4"> {{-- Usunięto styl inline --}}
            @if(Auth::check() && isset($latestUserBmiResult) && $latestUserBmiResult->bmi_value > 0)
                Cateringi sugerowane dla Ciebie (BMI: {{ number_format($latestUserBmiResult->bmi_value, 2) }} - {{ $bmiCategory ?? 'Brak kategorii' }})
            @else
                Zaloguj się i oblicz BMI, aby otrzymać spersonalizowane propozycje cateringów!
            @endif
        </h3>
        <div class="row">
            @forelse ($suggestedCaterings as $catering)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm catering-card">
                        <a href="{{ route('caterings.show', $catering->catering_id) }}" class="text-decoration-none">
                            @if ($catering->photo)
                                <img src="{{ asset($catering->photo) }}" class="card-img-top" alt="{{ htmlspecialchars($catering->title ?? 'Catering') }}" >
                            @else
                                <img src="https://via.placeholder.com/300x200.png?text={{ urlencode(htmlspecialchars($catering->title ?? 'Catering')) }}" class="card-img-top" alt="{{ htmlspecialchars($catering->title ?? 'Catering') }}">
                            @endif
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title">
                                <a href="{{ route('caterings.show', $catering->catering_id) }}" class="text-decoration-none">
                                    {{($catering->title ?? 'Brak tytułu') }}
                                </a>
                            </h4>
                            <p class="card-text"><small><strong>Typ:</strong> {{ htmlspecialchars($catering->type ?? 'N/A') }}</small></p>
                            @if($catering->kcal_per_person)
                                <p class="card-text"><small><strong>Kcal/osobę:</strong> {{ htmlspecialchars($catering->kcal_per_person) }} kcal</small></p>
                            @endif
                            <div class="flex-grow-1">
                                <p class="card-text small">{{ \Illuminate\Support\Str::limit(nl2br(htmlspecialchars($catering->description ?? 'Brak opisu.')), 100) }}</p>
                            </div>
                            <p class="card-text fw-bold fs-5 mt-auto pt-2 price-tag">{{ htmlspecialchars(number_format($catering->price ?? 0, 2, ',', ' ')) }} zł</p>
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $catering->catering_id }}">
                                <input type="hidden" name="product_type" value="catering">
                                <div class="input-group">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="max-width: 70px;" aria-label="Ilość">
                                    <button type="submit" class="btn button btn-sm"><i class="bi bi-cart-plus"></i> Dodaj</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">Brak sugerowanych cateringów. Oblicz swoje BMI, aby zobaczyć spersonalizowane propozycje!</p>
                </div>
            @endforelse
        </div>
    </section>

    <hr class="my-5 themed-hr"> {{-- Dodana klasa dla HR --}}

    <section class="caterings-list mb-5">
        <h2 class="text-center mb-4">Wszystkie dostępne Cateringi</h2> {{-- Usunięto styl inline --}}
        <div class="row">
            @forelse ($allCaterings as $catering)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm catering-card">
                        <a href="{{ route('caterings.show', $catering->catering_id) }}" class="text-decoration-none">
                            @if ($catering->photo)
                                <img src="{{ asset($catering->photo) }}" class="card-img-top" alt="{{ htmlspecialchars($catering->title ?? 'Catering') }}">
                            @else
                                <img src="https://via.placeholder.com/300x200.png?text={{ urlencode(htmlspecialchars($catering->title ?? 'Catering')) }}" class="card-img-top" alt="{{ htmlspecialchars($catering->title ?? 'Catering') }}">
                            @endif
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title">
                                <a href="{{ route('caterings.show', $catering->catering_id) }}" class="text-decoration-none">
                                    {{($catering->title ?? 'Brak tytułu') }}
                                </a>
                            </h4>
                            <p class="card-text"><small><strong>Typ:</strong> {{ htmlspecialchars($catering->type ?? 'N/A') }}</small></p>
                            @if($catering->kcal_per_person)
                                <p class="card-text"><small><strong>Kcal/osobę:</strong> {{ htmlspecialchars($catering->kcal_per_person) }} kcal</small></p>
                            @endif
                            <div class="flex-grow-1">
                                <p class="card-text">{{ nl2br(htmlspecialchars($catering->description ?? 'Brak opisu.')) }}</p>
                                @if (!empty($catering->allergens))
                                    <p class="card-text text-danger"><small><strong>Alergeny:</strong> {{ htmlspecialchars($catering->allergens) }}</small></p>
                                @endif
                            </div>
                            <p class="card-text fw-bold fs-5 mt-auto pt-2 price-tag">{{ htmlspecialchars(number_format($catering->price ?? 0, 2, ',', ' ')) }} zł</p>
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $catering->catering_id }}">
                                <input type="hidden" name="product_type" value="catering">
                                <div class="input-group">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="max-width: 70px;" aria-label="Ilość">
                                    <button type="submit" class="btn button btn-sm"><i class="bi bi-cart-plus"></i> Dodaj</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">Nie znaleziono cateringów spełniających wybrane kryteria.</p>
                </div>
            @endforelse
        </div>
        @if(isset($allCaterings) && $allCaterings instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $allCaterings->total() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $allCaterings->withQueryString()->links() }}
            </div>
        @endif
    </section>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('js/catering-filters.js') }}" defer></script>
@endpush