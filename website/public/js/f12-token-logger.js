document.addEventListener('DOMContentLoaded', function () {
    const tokenHolder = document.getElementById('f12TokenDataHolder');

    if (tokenHolder && tokenHolder.dataset.f12Token) {
        const token = tokenHolder.dataset.f12Token;
        setTimeout(function() {
            console.log(
                "%cTOKEN DO ZMIANY HASŁA (skopiuj i wklej do formularza):",
                "color: blue; font-size: 16px; font-weight: bold;"
            );
            console.log(
                `%c${token}`,
                "color: green; font-size: 14px;"
            );
        }, 150);

    }
});