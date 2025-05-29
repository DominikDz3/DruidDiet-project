document.addEventListener('DOMContentLoaded', function () {
    function setupRangeSliderPair(minRangeEl, maxRangeEl, minInputName, maxInputName, minDisplayEl, maxDisplayEl, decimalPlaces = 0) {
        const minHiddenEl = document.querySelector(`input[name="${minInputName}"]`);
        const maxHiddenEl = document.querySelector(`input[name="${maxInputName}"]`);

        if (!minRangeEl || !maxRangeEl || !minHiddenEl || !maxHiddenEl || !minDisplayEl || !maxDisplayEl) {
            console.warn(`Brakujące elementy dla setupRangeSliderPair (${minInputName}/${maxInputName})`);
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
            maxHiddenEl.value = decimalPlaces > 0 ? maxVal.toFixed(decimalPlaces) : parseInt(maxVal);

            minDisplayEl.textContent = decimalPlaces > 0 ? minVal.toFixed(decimalPlaces) : parseInt(minVal);
            maxDisplayEl.textContent = decimalPlaces > 0 ? maxVal.toFixed(decimalPlaces) : parseInt(maxVal);
        }

        minRangeEl.addEventListener('input', updateValues);
        maxRangeEl.addEventListener('input', updateValues);

        updateValues(null);
    }

    setupRangeSliderPair(
        document.getElementById('min_calories_range'),
        document.getElementById('max_calories_range'),
        'min_calories',
        'max_calories',
        document.getElementById('min_calories_value'),
        document.getElementById('max_calories_value'),
        0
    );

    setupRangeSliderPair(
        document.getElementById('min_price_range'),
        document.getElementById('max_price_range'),
        'min_price',
        'max_price',
        document.getElementById('min_price_value'),
        document.getElementById('max_price_value'),
        2
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
        const filterParamKeys = ['min_calories', 'max_calories', 'min_price', 'max_price', 'catering_type'];
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
        console.warn('Jeden lub więcej elementów dla przełączania filtrów/sortowania nie zostało znalezionych.');
    }
});