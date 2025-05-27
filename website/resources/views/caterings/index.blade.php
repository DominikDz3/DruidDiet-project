@extends('layouts.app') 

@section('title', 'Nasze Kateringi - DruidDiet') 

@push('styles')

<style>
    .catering-card {
        border: 1px solid #e9ecef;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        background-color: #fff;
    }
    .catering-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .catering-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    .card-title {
        color: #4a6b5a;
    }
    .button { /* Twoja klasa .button, zachowujemy ją */
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
    .price-tag {
        color: #333;
    }
    .filters-and-sort {
        background-color: #f8f9fa;
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
        margin-bottom: 0;
        border-width: 0;
        transition: max-height 0.4s cubic-bezier(0.25, 0.1, 0.25, 1),
                    opacity 0.3s ease-out,
                    padding-top 0.4s ease-out,
                    padding-bottom 0.4s ease-out,
                    margin-bottom 0.4s ease-out,
                    border-width 0.1s ease-out;
    }
    .filters-and-sort.open {
        max-height: 1000px; /* Możesz zwiększyć, jeśli filtry zajmują więcej miejsca */
        opacity: 1;
        padding-top: 1rem;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        border-width: 1px;
    }
    .filters-and-sort .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        color: #4a6b5a;
    }
    .filters-and-sort .form-control-sm, .filters-and-sort .form-select-sm, .filters-and-sort .form-range {
        font-size: 0.9rem;
    }
    .toggle-buttons .btn {
        color: #4a6b5a;
        border-color: #4a6b5a;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
    .toggle-buttons .btn:hover {
        background-color: #4a6b5a;
        color: #fff;
    }
    .toggle-buttons .btn.active {
        background-color: #4a6b5a;
        color: #fff !important;    
        border-color: #4a6b5a;  
    }
    #filterOptionsContainer, #sortOptionsContainer {
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease-in-out 0.1s;
    }
    #filterOptionsContainer.visible, #sortOptionsContainer.visible {
        display: block;
        opacity: 1;
    }
    .range-value-display {
        font-weight: bold;
        color: #8b5a2b;
    }
    .form-range {
        margin-top: 0.1rem;
        width: 100%;
    }
    .form-label.range-label { 
         display: block;
         margin-bottom: 0.1rem;
    }
    .pagination .page-link {
        color: #4a6b5a;
    }
    .pagination .page-item.active .page-link {
        background-color: #4a6b5a;
        border-color: #4a6b5a;
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
    }
</style>
@endpush

@section('content')
{{-- Oryginalna zawartość tagu <main> z pliku caterings/index.blade.php trafia tutaj --}}
<main class="container mt-5">
    <div class="d-flex justify-content-end gap-2 mb-3 toggle-buttons">
        <button class="btn btn-outline-secondary btn-sm" type="button" id="toggleFiltersButton">
            <i class="bi bi-funnel"></i> Filtry
        </button>
        <button class="btn btn-outline-secondary btn-sm" type="button" id="toggleSortButton">
            <i class="bi bi-sort-down"></i> Sortowanie
        </button>
    </div>

    <section class="filters-and-sort border rounded shadow-sm" id="filtersAndSortSection">
        <div class="filters-and-sort-content p-3">
            {{-- Zakładam, że zmienne $currentFilters i $cateringTypes są przekazywane z kontrolera --}}
            <form method="GET" action="{{ route('caterings.index') }}" class="row g-3 align-items-start">
                <div class="col-12" id="filterOptionsContainer">
                    <h5 class="mb-3" style="color: #4a6b5a;">Filtruj Kateringi</h5>
                    <div class="row g-3">
                        <div class="col-md-6"> {{-- Zmieniono z col-md-4 na col-md-6 dla lepszego układu przy jednym filtrze ceny --}}
                            <label class="form-label range-label">Cena: <span id="min_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['min_price'] ?? 500), 2) }}</span> - <span id="max_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['max_price'] ?? 3000), 2) }}</span> zł</label>
                            <div class="d-flex gap-2">
                                <input type="range" class="form-range" id="min_price_range" min="500" max="3000" step="50" value="{{ $currentFilters['min_price'] ?? 500 }}">
                                <input type="range" class="form-range" id="max_price_range" min="500" max="3000" step="50" value="{{ $currentFilters['max_price'] ?? 3000 }}">
                            </div>
                            <input type="hidden" id="min_price" name="min_price" value="{{ $currentFilters['min_price'] ?? 500 }}">
                            <input type="hidden" id="max_price" name="max_price" value="{{ $currentFilters['max_price'] ?? 3000 }}">
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0"> {{-- Zmieniono z col-md-12 na col-md-6 i dodano mt-md-0 --}}
                            <label for="catering_type" class="form-label">Typ Kateringu:</label>
                            <select class="form-select form-select-sm" id="catering_type" name="catering_type">
                                <option value="all" {{ ($currentFilters['catering_type'] ?? 'all') == 'all' ? 'selected' : '' }}>Wszystkie typy</option>
                                @if(isset($cateringTypes) && !$cateringTypes->isEmpty())
                                    @foreach($cateringTypes as $type)
                                        <option value="{{ $type }}" {{ ($currentFilters['catering_type'] ?? '') == $type ? 'selected' : '' }}>
                                            {{ htmlspecialchars(ucfirst($type)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3" id="sortOptionsContainer">
                    <h5 class="mb-3" style="color: #4a6b5a;">Sortuj Kateringi</h5>
                    <div class="row g-3">
                        <div class="col-md-8 col-lg-6">
                            <label for="sort_option" class="form-label">Sortuj według:</label>
                            <select class="form-select form-select-sm" id="sort_option" name="sort_option">
                                <option value="title_asc" {{ ($currentFilters['sort_option'] ?? 'title_asc') == 'title_asc' ? 'selected' : '' }}>Nazwa (A-Z)</option>
                                <option value="title_desc" {{ ($currentFilters['sort_option'] ?? '') == 'title_desc' ? 'selected' : '' }}>Nazwa (Z-A)</option>
                                <option value="price_asc" {{ ($currentFilters['sort_option'] ?? '') == 'price_asc' ? 'selected' : '' }}>Cena (Rosnąco)</option>
                                <option value="price_desc" {{ ($currentFilters['sort_option'] ?? '') == 'price_desc' ? 'selected' : '' }}>Cena (Malejąco)</option>
                                {{-- Usunięto sortowanie po kaloriach, bo cateringi ich nie mają --}}
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

    <section class="caterings-list mb-5">
        <h2 class="text-center mb-4" style="color: #4a6b5a;">Dostępne Kateringi</h2>
        <div class="row">
            @if(isset($caterings) && $caterings->count() > 0)
                @foreach ($caterings as $catering)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm catering-card">
                            @if ($catering->photo)
                                <img src="{{ asset($catering->photo) }}" class="img-fluid" alt="{{ htmlspecialchars($catering->title ?? 'Katering') }}">
                            @else
                                <img src="https://via.placeholder.com/800x400.png?text={{ urlencode(htmlspecialchars($catering->title ?? 'Katering')) }}" class="img-fluid" alt="{{ htmlspecialchars($catering->title ?? 'Katering') }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h4 class="card-title">
                                    <a href="{{ route('caterings.show', $catering->catering_id) }}" style="text-decoration: none; color: inherit;">
                                        {{($catering->title ?? 'Brak tytułu') }}
                                     </a>
                                </h4>
                                <p class="card-text"><small><strong>Typ:</strong> {{ htmlspecialchars($catering->type ?? 'N/A') }}</small></p>
                                <div style="flex-grow: 1;">
                                    <p class="card-text">{{ nl2br(htmlspecialchars($catering->description ?? 'Brak opisu.')) }}</p>
                                    @if (!empty($catering->allergens))
                                        <p class="card-text text-danger"><small><strong>Alergeny:</strong> {{ htmlspecialchars($catering->allergens) }}</small></p>
                                    @endif
                                </div>
                                <p class="card-text fw-bold fs-5 mt-auto pt-2 price-tag">{{ htmlspecialchars(number_format($catering->price ?? 0, 2, ',', ' ')) }} zł</p>
                                
                                {{-- FORMULARZ DODAWANIA DO KOSZYKA --}}
                                <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $catering->catering_id }}">
                                    <input type="hidden" name="product_type" value="catering">
                                    <div class="input-group">
                                        <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="max-width: 70px;" aria-label="Ilość">
                                        <button type="submit" class="btn button btn-sm"> {{-- Używam Twojej klasy .button --}}
                                            <i class="bi bi-cart-plus"></i> Dodaj
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-center">Nie znaleziono kateringów spełniających wybrane kryteria.</p>
                </div>
            @endif
        </div>
        {{-- Paginacja, jeśli $caterings jest obiektem paginowanym --}}
        @if(isset($caterings) && $caterings instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $caterings->total() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $caterings->withQueryString()->links() }}
            </div>
        @endif
    </section>
</main>
@endsection

@push('scripts')
{{-- Cały kod JavaScript, który był na końcu <body> oryginalnego pliku caterings/index.blade.php, przenosimy tutaj --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupRangeSliderPair(minRangeEl, maxRangeEl, minHiddenEl, maxHiddenEl, minDisplayEl, maxDisplayEl, decimalPlaces = 0) {
            if (!minRangeEl || !maxRangeEl || !minHiddenEl || !maxHiddenEl || !minDisplayEl || !maxDisplayEl) {
                console.warn('Brakujące elementy dla setupRangeSliderPair', {minRangeEl, maxRangeEl, minHiddenEl, maxHiddenEl, minDisplayEl, maxDisplayEl});
                return;
            }

            function updateValues(event) { // Dodano parametr event
                let minVal = parseFloat(minRangeEl.value);
                let maxVal = parseFloat(maxRangeEl.value);

                if (minVal > maxVal) {
                    if (event && event.target === minRangeEl) { 
                        maxRangeEl.value = minVal;
                        maxVal = minVal;
                    } else if (event && event.target === maxRangeEl) {
                        minRangeEl.value = maxVal;
                        minVal = maxVal;
                    }
                }

                minHiddenEl.value = decimalPlaces > 0 ? minVal.toFixed(decimalPlaces) : parseInt(minVal);
                minDisplayEl.textContent = decimalPlaces > 0 ? minVal.toFixed(decimalPlaces) : parseInt(minVal);

                maxHiddenEl.value = decimalPlaces > 0 ? maxVal.toFixed(decimalPlaces) : parseInt(maxVal);
                maxDisplayEl.textContent = decimalPlaces > 0 ? maxVal.toFixed(decimalPlaces) : parseInt(maxVal);
            }

            minRangeEl.addEventListener('input', updateValues);
            maxRangeEl.addEventListener('input', updateValues);

            minDisplayEl.textContent = decimalPlaces > 0 ? parseFloat(minRangeEl.value).toFixed(decimalPlaces) : parseInt(minRangeEl.value);
            maxDisplayEl.textContent = decimalPlaces > 0 ? parseFloat(maxRangeEl.value).toFixed(decimalPlaces) : parseInt(maxRangeEl.value);
        }

        setupRangeSliderPair(
            document.getElementById('min_price_range'), document.getElementById('max_price_range'),
            document.getElementById('min_price'), document.getElementById('max_price'),
            document.getElementById('min_price_value'), document.getElementById('max_price_value'), 2
        );

        const toggleFiltersButton = document.getElementById('toggleFiltersButton');
        const toggleSortButton = document.getElementById('toggleSortButton');
        const filtersAndSortSection = document.getElementById('filtersAndSortSection');
        const filterOptionsContainer = document.getElementById('filterOptionsContainer');
        const sortOptionsContainer = document.getElementById('sortOptionsContainer');

        if (toggleFiltersButton && toggleSortButton && filtersAndSortSection && filterOptionsContainer && sortOptionsContainer) {
            function updateButtonActiveState() {
                toggleFiltersButton.classList.toggle('active', filterOptionsContainer.classList.contains('visible'));
                toggleSortButton.classList.toggle('active', sortOptionsContainer.classList.contains('visible'));
            }

            function showMainSectionIfAnySubSectionVisible() {
                if (filterOptionsContainer.classList.contains('visible') || sortOptionsContainer.classList.contains('visible')) {
                    filtersAndSortSection.classList.add('open');
                } else {
                    filtersAndSortSection.classList.remove('open');
                }
            }

            function toggleSubSection(sectionToShow, sectionToHide) {
                const wasVisible = sectionToShow.classList.contains('visible');
                filterOptionsContainer.classList.remove('visible');
                sortOptionsContainer.classList.remove('visible');

                if (!wasVisible) {
                    sectionToShow.classList.add('visible');
                }
                showMainSectionIfAnySubSectionVisible();
                updateButtonActiveState();
            }

            const urlParams = new URLSearchParams(window.location.search);
            let isAnyFilterActive = false;
            // Zaktualizowano filterParamKeys dla cateringów
            const filterParamKeys = ['min_price', 'max_price', 'catering_type']; 
            const sortParamKeys = ['sort_option'];

            filterParamKeys.forEach(param => {
                if (urlParams.has(param) && urlParams.get(param) !== '' && (param !== 'catering_type' || urlParams.get(param) !== 'all')) {
                    isAnyFilterActive = true;
                }
            });
             sortParamKeys.forEach(param => {
                if (urlParams.has(param) && urlParams.get(param) !== '' && urlParams.get(param) !== 'title_asc') {
                    isAnyFilterActive = true;
                }
            });

            if (isAnyFilterActive) {
                filtersAndSortSection.classList.add('open');
                let showFiltersFromUrl = false;
                filterParamKeys.forEach(param => {
                    if (urlParams.has(param) && urlParams.get(param) !== '' && (param !== 'catering_type' || urlParams.get(param) !== 'all')) showFiltersFromUrl = true;
                });
                if (showFiltersFromUrl) filterOptionsContainer.classList.add('visible');

                let showSortFromUrl = false;
                sortParamKeys.forEach(param => {
                    if (urlParams.has(param) && urlParams.get(param) !== '' && urlParams.get(param) !== 'title_asc') showSortFromUrl = true;
                });
                if (showSortFromUrl) sortOptionsContainer.classList.add('visible');

                if (!filterOptionsContainer.classList.contains('visible') && !sortOptionsContainer.classList.contains('visible')) {
                    filterOptionsContainer.classList.add('visible');
                }
                updateButtonActiveState();
            }

            toggleFiltersButton.addEventListener('click', function () {
                toggleSubSection(filterOptionsContainer, sortOptionsContainer);
            });

            toggleSortButton.addEventListener('click', function () {
                toggleSubSection(sortOptionsContainer, filterOptionsContainer);
            });
        } else {
             console.warn('Jeden lub więcej elementów dla przełączania filtrów/sortowania nie zostało znalezionych na stronie kateringów.');
        }
    });
</script>
@endpush