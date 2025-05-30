@extends('layouts.app')

@section('title', 'Nasze Diety - DruidDiet')

{{-- Sekcja @push('styles') została usunięta --}}

@section('content')
<main class="container mt-5">

    {{-- SEKCJA KALKULATORA BMI --}}
    <section class="bmi-calculator-section shadow-sm">
        <h3 class="text-center">Dopasuj dietę do siebie - oblicz BMI!</h3>
        <form method="GET" action="{{ route('diets.index') }}" id="bmiAndFilterForm" class="row g-3 justify-content-center align-items-end">
            {{-- Ukryte pola dla istniejących filtrów ogólnych --}}
            @foreach (collect($currentFilters)->except(['height_bmi', 'weight_bmi', 'page', 'save_bmi_result', 'use_saved_bmi']) as $key => $value)
                @if(is_array($value))
                    @foreach($value as $v_key => $v_value)
                        <input type="hidden" name="{{ $key }}[{{ $v_key }}]" value="{{ $v_value }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div class="col-md-3">
                <label for="height_bmi" class="form-label">Wzrost (m)</label>
                <input type="number" step="0.01" class="form-control form-control-sm" id="height_bmi" name="height_bmi" placeholder="np. 1.75" value="{{ $heightInput ?? old('height_bmi') }}">
            </div>
            <div class="col-md-3">
                <label for="weight_bmi" class="form-label">Waga (kg)</label>
                <input type="number" step="0.1" class="form-control form-control-sm" id="weight_bmi" name="weight_bmi" placeholder="np. 70.5" value="{{ $weightInput ?? old('weight_bmi') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn button btn-sm w-100"><i class="bi bi-calculator"></i> Oblicz i Filtruj</button>
            </div>

            @auth
                <div class="col-12 mt-2 text-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="save_bmi_result" id="save_bmi_result" value="1" {{ (old('save_bmi_result') || ($heightInput && $weightInput && ($currentFilters['save_bmi_result'] ?? false)) ) ? 'checked' : '' }} >
                        <label class="form-check-label small" for="save_bmi_result">
                            Zapisz ten wynik BMI
                        </label>
                    </div>

                    @if(isset($latestUserBmiResult) && $latestUserBmiResult->bmi_value > 0)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="use_saved_bmi" id="use_saved_bmi" value="1" {{ ($currentFilters['use_saved_bmi'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="use_saved_bmi">
                            Użyj ostatniego zapisanego BMI: {{ number_format($latestUserBmiResult->bmi_value, 2) }} ({{ \Carbon\Carbon::parse($latestUserBmiResult->created_at)->format('d.m.Y') }})
                        </label>
                    </div>
                    @endif
                </div>
            @endauth
        </form>

        @if(isset($calculatedBmi) && $calculatedBmi > 0)
            <div class="bmi-result-display">
                @if($currentFilters['use_saved_bmi'] ?? false)
                    Wykorzystano zapisany wynik BMI:
                @else
                    Twoje obliczone BMI:
                @endif
                <strong>{{ number_format($calculatedBmi, 2) }}</strong>
                (Kategoria: {{ $bmiCategory ?? 'Brak danych' }})

                @if(Auth::check() && ($currentFilters['save_bmi_result'] ?? false) && (request()->filled('height_bmi') && request()->filled('weight_bmi')))
                    <br><small class="text-success">Wynik BMI został zapisany.</small>
                @endif
                @if($bmiCategory)
                    <br><small>Wyświetlono diety sugerowane dla kategorii: <strong>{{ $bmiCategory }}</strong>.</small>
                @endif
            </div>
        @elseif ((request()->filled('height_bmi') && request()->filled('weight_bmi')))
            <div class="bmi-result-display text-danger">
                Nie udało się obliczyć BMI. Sprawdź wprowadzone dane.
            </div>
        @elseif (Auth::check() && isset($latestUserBmiResult) && !($currentFilters['use_saved_bmi'] ?? false) && !(request()->filled('height_bmi') && request()->filled('weight_bmi')))
             <div class="bmi-result-display text-info">
                Twój ostatni zapisany wynik BMI to: <strong>{{ number_format($latestUserBmiResult->bmi_value, 2) }}</strong>
                z dnia {{ \Carbon\Carbon::parse($latestUserBmiResult->created_at)->format('d.m.Y') }}. Możesz go użyć zaznaczając powyższą opcję lub wprowadzić nowe dane do obliczenia.
            </div>
        @endif
    </section>

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
            <form method="GET" action="{{ route('diets.index') }}" class="row g-3 align-items-start">
                @if(request()->filled('height_bmi') && request()->filled('weight_bmi') && !request()->boolean('use_saved_bmi'))
                    <input type="hidden" name="height_bmi" value="{{ request('height_bmi') }}">
                    <input type="hidden" name="weight_bmi" value="{{ request('weight_bmi') }}">
                    @if(request()->boolean('save_bmi_result'))
                        <input type="hidden" name="save_bmi_result" value="1">
                    @endif
                @elseif(request()->boolean('use_saved_bmi'))
                    <input type="hidden" name="use_saved_bmi" value="1">
                @endif

                <div class="col-12" id="filterOptionsContainer">
                    <h5 class="mb-3">Filtruj Diety</h5> {{-- Usunięto styl inline --}}
                    <div class="row g-3">
                        @if(!((isset($calculatedBmi) && $calculatedBmi > 0) || ($currentFilters['use_saved_bmi'] ?? false)))
                        <div class="col-md-6">
                            <label class="form-label range-label">Kalorie: <span id="min_calories_value" class="range-value-display">{{ $currentFilters['min_calories'] ?? 500 }}</span> - <span id="max_calories_value" class="range-value-display">{{ $currentFilters['max_calories'] ?? 4000 }}</span> kcal</label>
                            <div class="d-flex gap-2">
                                <input type="range" class="form-range" id="min_calories_range" min="500" max="4000" step="50" value="{{ $currentFilters['min_calories'] ?? 500 }}">
                                <input type="range" class="form-range" id="max_calories_range" min="500" max="4000" step="50" value="{{ $currentFilters['max_calories'] ?? 4000 }}">
                            </div>
                            <input type="hidden" id="min_calories" name="min_calories" value="{{ $currentFilters['min_calories'] ?? 500 }}">
                            <input type="hidden" id="max_calories" name="max_calories" value="{{ $currentFilters['max_calories'] ?? 4000 }}">
                        </div>
                        @else
                        <div class="col-md-6">
                            <p class="text-muted small">Filtrowanie kalorii jest wyłączone, gdy aktywne jest filtrowanie przez BMI.</p>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label range-label">Cena: <span id="min_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['min_price'] ?? 20), 2) }}</span> - <span id="max_price_value" class="range-value-display">{{ number_format(floatval($currentFilters['max_price'] ?? 300), 2) }}</span> zł</label>
                            <div class="d-flex gap-2">
                                <input type="range" class="form-range" id="min_price_range" min="20" max="300" step="5" value="{{ $currentFilters['min_price'] ?? 20 }}">
                                <input type="range" class="form-range" id="max_price_range" min="20" max="300" step="5" value="{{ $currentFilters['max_price'] ?? 300 }}">
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
                                        <option value="{{ $type }}" {{ ($currentFilters['diet_type'] ?? '') == $type ? 'selected' : '' }}>
                                            {{ htmlspecialchars(ucfirst($type)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3" id="sortOptionsContainer">
                    <h5 class="mb-3">Sortuj Diety</h5> {{-- Usunięto styl inline --}}
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

    <section class="diets-list mb-5">
        <h2 class="text-center mb-4"> {{-- Usunięto styl inline --}}
            @if(isset($calculatedBmi) && $calculatedBmi > 0 && $bmiCategory)
                 @if($currentFilters['use_saved_bmi'] ?? false)
                    Diety sugerowane dla zapisanego BMI: {{ number_format($calculatedBmi, 2) }} ({{$bmiCategory}})
                @else
                    Diety sugerowane dla BMI: {{ number_format($calculatedBmi, 2) }} ({{$bmiCategory}})
                @endif
            @else
                Dostępne Diety
            @endif
        </h2>
        <div class="row">
            @if(isset($diets) && $diets->count() > 0)
                @foreach ($diets as $diet)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm diet-card"> {{-- Klasa .diet-card będzie stylizowana w nordic.css --}}
                            <img src="https://via.placeholder.com/300x200.png?text={{ urlencode(htmlspecialchars($diet->title ?? 'Dieta')) }}" class="card-img-top" alt="{{ htmlspecialchars($diet->title ?? 'Dieta') }}">
                            <div class="card-body d-flex flex-column">
                                <h4 class="card-title">{{ htmlspecialchars($diet->title ?? 'Brak tytułu') }}</h4>
                                <p class="card-text"><small><strong>Typ:</strong> {{ htmlspecialchars($diet->type ?? 'N/A') }}</small></p>
                                <p class="card-text"><small><strong>Kalorie:</strong> {{ htmlspecialchars($diet->calories ?? '0') }} kcal</small></p>
                                <div style="flex-grow: 1;">
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
                                        <button type="submit" class="btn button btn-sm">
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
                    <p class="text-center">Nie znaleziono diet spełniających wybrane kryteria.</p>
                </div>
            @endif
        </div>
        @if(isset($diets) && $diets instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $diets->total() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $diets->withQueryString()->links() }}
            </div>
        @endif
    </section>
</main>
@endsection

@push('scripts')
{{-- Skrypty pozostają bez zmian, zakładając, że nie manipulują stylami w sposób konfliktowy --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupRangeSliderPair(minRangeEl, maxRangeEl, minHiddenEl, maxHiddenEl, minDisplayEl, maxDisplayEl, decimalPlaces = 0) {
            if (!minRangeEl || !maxRangeEl || !minHiddenEl || !maxHiddenEl || !minDisplayEl || !maxDisplayEl) {
                console.warn('Brakujące elementy dla setupRangeSliderPair', {minRangeEl, maxRangeEl, minHiddenEl, maxHiddenEl, minDisplayEl, maxDisplayEl});
                return;
            }

            function updateValues(event) {
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

        const minCaloriesRange = document.getElementById('min_calories_range');
        const maxCaloriesRange = document.getElementById('max_calories_range');
        if (minCaloriesRange && maxCaloriesRange) {
            setupRangeSliderPair(
                minCaloriesRange, maxCaloriesRange,
                document.getElementById('min_calories'), document.getElementById('max_calories'),
                document.getElementById('min_calories_value'), document.getElementById('max_calories_value'), 0
            );
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
            const filterParamKeys = ['min_calories', 'max_calories', 'min_price', 'max_price', 'diet_type'];
            const sortParamKeys = ['sort_option'];

            filterParamKeys.forEach(param => {
                if (urlParams.has(param) && urlParams.get(param) !== '' && (param !== 'diet_type' || urlParams.get(param) !== 'all')) {
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
                    if (urlParams.has(param) && urlParams.get(param) !== '' && (param !== 'diet_type' || urlParams.get(param) !== 'all')) showFiltersFromUrl = true;
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
            console.warn('Jeden lub więcej elementów dla przełączania filtrów/sortowania nie zostało znalezionych.');
        }

        const saveBmiCheckbox = document.getElementById('save_bmi_result');
        const useSavedBmiCheckbox = document.getElementById('use_saved_bmi');
        const heightBmiInput = document.getElementById('height_bmi');
        const weightBmiInput = document.getElementById('weight_bmi');

        if (useSavedBmiCheckbox) {
            useSavedBmiCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    if (saveBmiCheckbox) {
                        saveBmiCheckbox.checked = false;
                        saveBmiCheckbox.disabled = true;
                    }
                    if (heightBmiInput) heightBmiInput.disabled = true;
                    if (weightBmiInput) weightBmiInput.disabled = true;
                } else {
                    if (heightBmiInput) heightBmiInput.disabled = false;
                    if (weightBmiInput) weightBmiInput.disabled = false;
                    if (saveBmiCheckbox) {
                       saveBmiCheckbox.disabled = !(heightBmiInput && heightBmiInput.value && weightBmiInput && weightBmiInput.value);
                    }
                }
            });
        }

        function toggleSaveBmiAvailability() {
            if (saveBmiCheckbox) {
                if (heightBmiInput && weightBmiInput && heightBmiInput.value && weightBmiInput.value) {
                    saveBmiCheckbox.disabled = false;
                    if (useSavedBmiCheckbox) useSavedBmiCheckbox.checked = false;
                    if (heightBmiInput) heightBmiInput.disabled = false;
                    if (weightBmiInput) weightBmiInput.disabled = false;
                } else {
                    saveBmiCheckbox.disabled = true;
                }
            }
        }

        if (heightBmiInput) heightBmiInput.addEventListener('input', toggleSaveBmiAvailability);
        if (weightBmiInput) weightBmiInput.addEventListener('input', toggleSaveBmiAvailability);
        toggleSaveBmiAvailability();

        if (useSavedBmiCheckbox && useSavedBmiCheckbox.checked) {
            if (saveBmiCheckbox) {
                saveBmiCheckbox.checked = false;
                saveBmiCheckbox.disabled = true;
            }
            if (heightBmiInput) heightBmiInput.disabled = true;
            if (weightBmiInput) weightBmiInput.disabled = true;
        }
    });
</script>
@endpush