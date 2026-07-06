<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Motel Los Gatitos - Hotel de lujo en Santiago. Reserve su habitación para una experiencia inolvidable.">
    <title>@yield('title', 'Motel Los Gatitos')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/img/icono.png">
</head>
<body class="bg-black font-sans antialiased">
    @include('components.header-landing')

    <main>
        @yield('content')
    </main>

    @include('components.footer-landing')

    @stack('scripts')
</body>
</html>
