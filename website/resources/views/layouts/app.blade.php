<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DruidDiet - Odżywianie w Zgodzie z Naturą')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nordic.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.removeAttribute('data-theme');
            }
        })();
    </script>
    @stack('head_styles')
</head>
<body class="d-flex flex-column min-vh-100">
    @include('partials.header')

    <main class="flex-fill">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Dołączamy nasz skrypt przełącznika motywu --}}
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    @stack('scripts')
</body>
</html>