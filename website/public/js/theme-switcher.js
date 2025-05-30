// public/js/theme-switcher.js
(function () {
    const themeToggleButton = document.getElementById('theme-toggle-button');
    const sunIcon = document.getElementById('theme-toggle-sun');
    const moonIcon = document.getElementById('theme-toggle-moon');
    const currentTheme = localStorage.getItem('theme');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            if (sunIcon) sunIcon.style.display = 'inline';
            if (moonIcon) moonIcon.style.display = 'none';
        } else {
            document.documentElement.removeAttribute('data-theme');
            if (sunIcon) sunIcon.style.display = 'none';
            if (moonIcon) moonIcon.style.display = 'inline';
        }
    }

    // Initialize theme
    if (currentTheme) {
        applyTheme(currentTheme);
    } else if (prefersDarkScheme.matches) {
        applyTheme('dark');
        localStorage.setItem('theme', 'dark'); // Save preference if detected from system
    } else {
        applyTheme('light'); // Default to light
    }

    if (themeToggleButton) {
        themeToggleButton.addEventListener('click', () => {
            let theme = document.documentElement.hasAttribute('data-theme') && document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            if (theme === 'dark') {
                theme = 'light';
            } else {
                theme = 'dark';
            }
            localStorage.setItem('theme', theme);
            applyTheme(theme);
        });
    }

    // Listen for system preference changes
    prefersDarkScheme.addEventListener('change', (e) => {
        // Only change if no user preference is stored
        if (!localStorage.getItem('theme')) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
})();