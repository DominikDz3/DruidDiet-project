@extends('layouts.app')

@section('title', 'Nasze Diety - DruidDiet')

@section('content')
<main class="container mt-5">

    {{-- Przyciski Filtrów i Sortowania --}}
    <div class="d-flex justify-content-end gap-2 mb-3 toggle-buttons">
        <button class="btn btn-outline-secondary btn-sm" type="button" id="toggleFiltersButton"><i class="bi bi-funnel"></i> Filtry</button>
        <button class="btn btn-outline-secondary btn-sm" type="button" id="toggleSortButton"><i class="bi bi-sort-down"></i> Sortowanie</button>
    </div>

    {{-- Sekcja Filtrów i Sortowania --}}
    <section class="filters-and-sort border rounded shadow-sm" id="filtersAndSortSection">
        <div class="filters-and-sort-content p-3">
            <form method="GET" action="{{ route('diets.index') }}" class="row g-3 align-items-start">
                @foreach (collect($currentFilters)->except(['page']) as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v_key => $v_value) <input type="hidden" name="{{ $key }}[{{ $v_key }}]" value="{{ $v_value }}"> @endforeach
                    @elseif($value !== null) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endif
                @endforeach
                <div class="col-12" id="filterOptionsContainer">
                    <h5 class="mb-3">Filtruj Diety</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label range-label">Kalorie: <span id="min_calories_value" class="range-value-display">{{ $currentFilters['min_calories'] ?? 500 }}</span> - <span id="max_calories_value" class="range-value-display">{{ $currentFilters['max_calories'] ?? 4000 }}</span> kcal</label>
                            <div class="d-flex gap-2">
                                <input type="range" class="form-range" id="min_calories_range" name="min_calories_range_visual" min="500" max="4000" step="50" value="{{ $currentFilters['min_calories'] ?? 500 }}">
                                <input type="range" class="form-range" id="max_calories_range" name="max_calories_range_visual" min="500" max="4000" step="50" value="{{ $currentFilters['max_calories'] ?? 4000 }}">
                            </div>
                            <input type="hidden" id="min_calories" name="min_calories" value="{{ $currentFilters['min_calories'] ?? 500 }}">
                            <input type="hidden" id="max_calories" name="max_calories" value="{{ $currentFilters['max_calories'] ?? 4000 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label range-label">Cena: <span id="min_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['min_price'] ?? 20), 2) }}</span> - <span id="max_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['max_price'] ?? 300), 2) }}</span> zł</label>
                            <div class="d-flex gap-2">
                                <input type="range" class="form-range" id="min_price_range" name="min_price_range_visual" min="20" max="300" step="5" value="{{ $currentFilters['min_price'] ?? 20 }}">
                                <input type="range" class="form-range" id="max_price_range" name="max_price_range_visual" min="20" max="300" step="5" value="{{ $currentFilters['max_price'] ?? 300 }}">
                            </div>
                            <input type="hidden" id="min_price" name="min_price" value="{{ $currentFilters['min_price'] ?? 20 }}">
                            <input type="hidden" id="max_price" name="max_price" value="{{ $currentFilters['max_price'] ?? 300 }}">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="diet_type" class="form-label">Typ Diety:</label>
                            <select class="form-select form-select-sm" id="diet_type" name="diet_type">
                                <option value="all" {{ ($currentFilters['diet_type'] ?? 'all') == 'all' ? 'selected' : '' }}>Wszystkie typy</option>
                                @if(isset($dietTypes) && !$dietTypes->isEmpty())
                                    @foreach($dietTypes as $type)
                                        <option value="{{ $type }}" {{ ($currentFilters['diet_type'] ?? '') == $type ? 'selected' : '' }}>{{ htmlspecialchars(ucfirst($type)) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3" id="sortOptionsContainer">
                    <h5 class="mb-3">Sortuj Diety</h5>
                    <div class="row g-3">
                        <div class="col-md-8 col-lg-6">
                            <label for="sort_option" class="form-label">Sortuj według:</label>
                            <select class="form-select form-select-sm" id="sort_option" name="sort_option">
                                <option value="title_asc" {{ ($currentFilters['sort_option'] ?? 'title_asc') == 'title_asc' ? 'selected' : '' }}>Nazwa (A-Z)</option>
                                <option value="title_desc" {{ ($currentFilters['sort_option'] ?? '') == 'title_desc' ? 'selected' : '' }}>Nazwa (Z-A)</option>
                                <option value="price_asc" {{ ($currentFilters['sort_option'] ?? '') == 'price_asc' ? 'selected' : '' }}>Cena (Rosnąco)</option>
                                <option value="price_desc" {{ ($currentFilters['sort_option'] ?? '') == 'price_desc' ? 'selected' : '' }}>Cena (Malejąco)</option>
                                <option value="calories_asc" {{ ($currentFilters['sort_option'] ?? '') == 'calories_asc' ? 'selected' : '' }}>Kalorie (Rosnąco)</option>
                                <option value="calories_desc" {{ ($currentFilters['sort_option'] ?? '') == 'calories_desc' ? 'selected' : '' }}>Kalorie (Malejąco)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary button">Zastosuj Filtry/Sortowanie</button>
                </div>
            </form>
        </div>
    </section>

    {{-- Sekcja diet sugerowanych na podstawie BMI --}}
    @if(isset($bmiDisplayData['value']) && $bmiDisplayData['value'] > 0 && $bmiDisplayData['category'] !== 'Brak danych')
        <section class="suggested-diets-section shadow-sm mt-5 p-3 rounded"> {{-- Można użyć klasy .bmi-info-section lub nowej --}}
            <h3 class="text-center mb-3">
                Diety sugerowane dla Ciebie (BMI: {{ number_format($bmiDisplayData['value'], 2) }} - {{ $bmiDisplayData['category'] }})
            </h3>
            @if($suggestedDiets->count() > 0)
                <div class="row">
                    @foreach ($suggestedDiets as $diet)
                        <div class="col-md-6 col-lg-4 mb-4"> {{-- Zmieniono na col-lg-4, aby pasowało do listy wszystkich diet --}}
                            <div class="card h-100 shadow-sm diet-card">
                                <img src="https://via.placeholder.com/300x200.png?text={{ urlencode(htmlspecialchars($diet->title ?? 'Dieta')) }}" class="card-img-top" alt="{{ htmlspecialchars($diet->title ?? 'Dieta') }}">
                                <div class="card-body d-flex flex-column">
                                    <h4 class="card-title">{{ htmlspecialchars($diet->title ?? 'Brak tytułu') }}</h4>
                                    <p class="card-text"><small><strong>Typ:</strong> {{ htmlspecialchars($diet->type ?? 'N/A') }}</small></p>
                                    <p class="card-text"><small><strong>Kalorie:</strong> {{ htmlspecialchars($diet->calories ?? '0') }} kcal</small></p>
                                    <div class="flex-grow-1">
                                        <p class="card-text">{{ \Illuminate\Support\Str::limit(nl2br(htmlspecialchars($diet->description ?? 'Brak opisu.')), 100) }}</p>
                                    </div>
                                    <p class="card-text fw-bold fs-5 mt-auto pt-2 price-tag">{{ htmlspecialchars(number_format($diet->price ?? 0, 2, ',', ' ')) }} zł</p>
                                    <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $diet->diet_id }}">
                                        <input type="hidden" name="product_type" value="diet">
                                        <div class="input-group">
                                            <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="max-width: 70px;" aria-label="Ilość">
                                            <button type="submit" class="btn button btn-sm"><i class="bi bi-cart-plus"></i> Dodaj</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted">Brak diet idealnie dopasowanych do Twojego BMI w tej chwili. Sprawdź wszystkie nasze diety poniżej!</p>
            @endif
             <p class="text-center mt-3"><small>Chcesz zaktualizować swoje BMI? <a href="{{ route('calculators.index') }}">Przejdź do kalkulatorów</a>.</small></p>
        </section>
        <hr class="my-5 themed-hr">
    @else
        {{-- Komunikat zachęcający do obliczenia BMI, jeśli nie ma aktywnego BMI --}}
        <section class="bmi-info-prompt text-center my-5 p-4 rounded shadow-sm">
            @if(Auth::check())
                <h3 class="mb-3">Spersonalizuj swoje diety!</h3>
                <p class="lead mb-2">Nie masz jeszcze zapisanego wyniku BMI lub nie obliczyłeś go w tej sesji.</p>
                <a href="{{ route('calculators.index') }}" class="button">Oblicz swoje BMI w Panelu Klienta</a>
                <p class="mt-2"><small>Dzięki temu będziemy mogli zaproponować Ci najlepiej dopasowane diety.</small></p>
            @else
                <h3 class="mb-3">Spersonalizuj swoje diety!</h3>
                <p class="lead mb-2">Chcesz zobaczyć spersonalizowane sugestie diet?</p>
                <a href="{{ route('login') }}?redirect={{ urlencode(route('calculators.index')) }}" class="button">Zaloguj się i Oblicz BMI</a>
            @endif
        </section>
    @endif


    {{-- Lista wszystkich diet (lub odfiltrowanych wg kryteriów z formularza) --}}
    <section class="diets-list mb-5">
        <h2 class="text-center mb-4">Wszystkie dostępne Diety</h2>
        <div class="row">
            @forelse ($allDiets as $diet) {{-- Zmieniono $diets na $allDiets --}}
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm diet-card">
                        <img src="https://via.placeholder.com/300x200.png?text={{ urlencode(htmlspecialchars($diet->title ?? 'Dieta')) }}" class="card-img-top" alt="{{ htmlspecialchars($diet->title ?? 'Dieta') }}">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title">{{ htmlspecialchars($diet->title ?? 'Brak tytułu') }}</h4>
                            <p class="card-text"><small><strong>Typ:</strong> {{ htmlspecialchars($diet->type ?? 'N/A') }}</small></p>
                            <p class="card-text"><small><strong>Kalorie:</strong> {{ htmlspecialchars($diet->calories ?? '0') }} kcal</small></p>
                            <div class="flex-grow-1">
                                <p class="card-text">{{ nl2br(htmlspecialchars($diet->description ?? 'Brak opisu.')) }}</p>
                                @if (!empty($diet->elements))
                                <p class="card-text mt-2"><small><strong>Skład:</strong> {{ htmlspecialchars($diet->elements) }}</small></p>
                                @endif
                                @if (!empty($diet->allergens))
                                    <p class="card-text text-danger"><small><strong>Alergeny:</strong> {{ htmlspecialchars($diet->allergens) }}</small></p>
                                @endif
                            </div>
                            <p class="card-text fw-bold fs-5 mt-auto pt-2 price-tag">{{ htmlspecialchars(number_format($diet->price ?? 0, 2, ',', ' ')) }} zł</p>
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $diet->diet_id }}">
                                <input type="hidden" name="product_type" value="diet">
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
                    <p class="text-center">Nie znaleziono diet spełniających wybrane kryteria.</p>
                </div>
            @endforelse
        </div>
        @if(isset($allDiets) && $allDiets instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $allDiets->total() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $allDiets->withQueryString()->links() }}
            </div>
        @endif
    </section>
</main>
@endsection

@push('scripts')
{{-- Skrypty JS dla suwaków i przełączania filtrów/sortowania pozostają bez zmian --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupRangeSliderPair(minRangeEl, maxRangeEl, minHiddenEl, maxHiddenEl, minDisplayEl, maxDisplayEl, decimalPlaces = 0) {
            if (!minRangeEl || !maxRangeEl || !minHiddenEl || !maxHiddenEl || !minDisplayEl || !maxDisplayEl) { return; }
            function updateValues(event) {
                let minVal = parseFloat(minRangeEl.value); let maxVal = parseFloat(maxRangeEl.value);
                if (minVal > maxVal) {
                    if (event && event.target === minRangeEl) { maxRangeEl.value = minVal; maxVal = minVal; }
                    else if (event && event.target === maxRangeEl) { minRangeEl.value = maxVal; minVal = maxVal; }
                }
                minHiddenEl.value = decimalPlaces > 0 ? minVal.toFixed(decimalPlaces) : parseInt(minVal);
                minDisplayEl.textContent = decimalPlaces > 0 ? minVal.toFixed(decimalPlaces) : parseInt(minVal);
                maxHiddenEl.value = decimalPlaces > 0 ? maxVal.toFixed(decimalPlaces) : parseInt(maxVal);
                maxDisplayEl.textContent = decimalPlaces > 0 ? maxVal.toFixed(decimalPlaces) : parseInt(maxVal);
            }
            minRangeEl.addEventListener('input', updateValues); maxRangeEl.addEventListener('input', updateValues);
            minHiddenEl.value = decimalPlaces > 0 ? parseFloat(minRangeEl.value).toFixed(decimalPlaces) : parseInt(minRangeEl.value);
            minDisplayEl.textContent = decimalPlaces > 0 ? parseFloat(minRangeEl.value).toFixed(decimalPlaces) : parseInt(minRangeEl.value);
            maxHiddenEl.value = decimalPlaces > 0 ? parseFloat(maxRangeEl.value).toFixed(decimalPlaces) : parseInt(maxRangeEl.value);
            maxDisplayEl.textContent = decimalPlaces > 0 ? parseFloat(maxRangeEl.value).toFixed(decimalPlaces) : parseInt(maxRangeEl.value);
        }
        const minCaloriesRange = document.getElementById('min_calories_range');
        const maxCaloriesRange = document.getElementById('max_calories_range');
        if (minCaloriesRange && maxCaloriesRange) {
             setupRangeSliderPair(minCaloriesRange, maxCaloriesRange, document.getElementById('min_calories'), document.getElementById('max_calories'), document.getElementById('min_calories_value'), document.getElementById('max_calories_value'), 0);
        }
        setupRangeSliderPair(document.getElementById('min_price_range'), document.getElementById('max_price_range'), document.getElementById('min_price'), document.getElementById('max_price'), document.getElementById('min_price_value'), document.getElementById('max_price_value'), 2);

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
                } else { filtersAndSortSection.classList.remove('open'); }
            }
            function toggleSubSection(sectionToShow) {
                const wasVisible = sectionToShow.classList.contains('visible');
                filterOptionsContainer.classList.remove('visible'); sortOptionsContainer.classList.remove('visible');
                if (!wasVisible) { sectionToShow.classList.add('visible'); }
                showMainSectionIfAnySubSectionVisible(); updateButtonActiveState();
            }
            const urlParams = new URLSearchParams(window.location.search); let isAnyFilterActive = false;
            const filterParamKeys = ['min_calories', 'max_calories', 'min_price', 'max_price', 'diet_type'];
            const sortParamKeys = ['sort_option'];
            filterParamKeys.forEach(param => { if (urlParams.has(param) && urlParams.get(param) !== '' && (param !== 'diet_type' || urlParams.get(param) !== 'all')) { isAnyFilterActive = true; } });
            sortParamKeys.forEach(param => { if (urlParams.has(param) && urlParams.get(param) !== '' && urlParams.get(param) !== 'title_asc') { isAnyFilterActive = true; } });
            if (isAnyFilterActive) {
                filtersAndSortSection.classList.add('open'); let showFiltersFromUrl = false;
                filterParamKeys.forEach(param => { if (urlParams.has(param) && urlParams.get(param) !== '' && (param !== 'diet_type' || urlParams.get(param) !== 'all')) showFiltersFromUrl = true; });
                if (showFiltersFromUrl) filterOptionsContainer.classList.add('visible');
                let showSortFromUrl = false;
                sortParamKeys.forEach(param => { if (urlParams.has(param) && urlParams.get(param) !== '' && urlParams.get(param) !== 'title_asc') showSortFromUrl = true; });
                if (showSortFromUrl) sortOptionsContainer.classList.add('visible');
                if (!filterOptionsContainer.classList.contains('visible') && !sortOptionsContainer.classList.contains('visible')) { filterOptionsContainer.classList.add('visible');}
                updateButtonActiveState();
            }
            toggleFiltersButton.addEventListener('click', function () { toggleSubSection(filterOptionsContainer); });
            toggleSortButton.addEventListener('click', function () { toggleSubSection(sortOptionsContainer); });
        }
    });
</script>
@endpush