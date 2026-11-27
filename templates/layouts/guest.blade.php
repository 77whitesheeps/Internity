<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Internity') | Internity</title>

    <!-- Google Fonts: Inter (falls back to system fonts when offline) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Vite bundle: local Bootstrap 5, Bootstrap Icons, Internity CSS & Vanilla JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
