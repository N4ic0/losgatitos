<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Motel Los Gatitos - Hotel de lujo en Santiago. Suites y departamentos con estacionamiento privado, TV Smart y WiFi premium. Atención 24 horas.')">
    <title>@yield('title', 'Motel Los Gatitos')</title>
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="Motel Los Gatitos">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_CL">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Motel Los Gatitos')">
    <meta property="og:description" content="@yield('meta_description', 'Motel Los Gatitos - Hotel de lujo en Santiago. Suites y departamentos con estacionamiento privado, TV Smart y WiFi premium. Atención 24 horas.')">
    <meta property="og:image" content="@yield('og_image', url('/img/habitaciones.jpeg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Motel Los Gatitos')">
    <meta name="twitter:description" content="@yield('meta_description', 'Motel Los Gatitos - Hotel de lujo en Santiago. Suites y departamentos con estacionamiento privado, TV Smart y WiFi premium. Atención 24 horas.')">
    <meta name="twitter:image" content="@yield('og_image', url('/img/habitaciones.jpeg'))">
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Hotel",
        "name": "Motel Los Gatitos",
        "description": "Motel de lujo en Santiago. Suites y departamentos para una experiencia íntima con estacionamiento privado, TV Smart y WiFi premium.",
        "url": "https://www.motellosgatitos.cl",
        "image": "https://www.motellosgatitos.cl/img/habitaciones.jpeg",
        "telephone": "+56-2-2234-5678",
        "priceRange": "$$",
        "currenciesAccepted": "CLP",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Av. Macul 4849",
            "addressLocality": "Macul",
            "addressRegion": "Región Metropolitana",
            "addressCountry": "CL"
        },
        "geo": {
            "@@type": "GeoCoordinates",
            "latitude": -33.497724,
            "longitude": -70.600260
        },
        "openingHoursSpecification": {
            "@@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
            "opens": "00:00",
            "closes": "23:59"
        },
        "amenityFeature": [
            {
                "@@type": "LocationFeatureSpecification",
                "name": "Estacionamiento privado",
                "value": true
            },
            {
                "@@type": "LocationFeatureSpecification",
                "name": "WiFi Premium",
                "value": true
            },
            {
                "@@type": "LocationFeatureSpecification",
                "name": "TV Smart",
                "value": true
            }
        ]
    }
    </script>
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

{{-- 
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href*="reservar"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (link.hasAttribute('data-no-check')) return;
            var href = link.getAttribute('href');
            fetch('/verificar-reserva')
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.permitida) {
                        window.location.href = href;
                    } else {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Reservas no disponibles',
                            html: data.mensaje,
                            timer: 5000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    }
                })
                .catch(function() {
                    window.location.href = href;
                });
        });
    });
});
</script>
--}}
</body>
</html>
