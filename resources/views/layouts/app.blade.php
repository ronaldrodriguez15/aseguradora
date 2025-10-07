<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EstaSSeguro</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- TailwindCSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Material Tailwind CSS -->
    <link href="https://unpkg.com/@material-tailwind/html@latest/styles/material-tailwind.css" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @livewireStyles

    <!-- Scripts de Laravel -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>


<body class="font-sans antialiased">
    <x-jet-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    @if (Auth::check())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let lastLat = null;
                let lastLng = null;
                const MIN_DISTANCE_METERS = 8;

                // Calcular distancia entre dos coordenadas (Haversine)
                function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                    const R = 6371; // km
                    const dLat = (lat2 - lat1) * Math.PI / 180;
                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const a =
                        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c;
                }

                function sendLocation(lat, lng) {
                    fetch("{{ route('update-location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            latitude: lat,
                            longitude: lng
                        })
                    }).catch(err => console.error("❌ Error enviando ubicación:", err));
                }

                if (navigator.geolocation) {
                    navigator.geolocation.watchPosition(
                        (pos) => {
                            let lat = pos.coords.latitude;
                            let lng = pos.coords.longitude;

                            if (lastLat === null || lastLng === null) {
                                // primera vez → guardar ubicación
                                sendLocation(lat, lng);
                                lastLat = lat;
                                lastLng = lng;
                            } else {
                                let dist = getDistanceFromLatLonInKm(lastLat, lastLng, lat, lng) * 1000; 
                                if (dist >= MIN_DISTANCE_METERS) {
                                    sendLocation(lat, lng);
                                    lastLat = lat;
                                    lastLng = lng;
                                }
                            }
                        },
                        (err) => {
                            console.error("❌ Error obteniendo ubicación:", err);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    console.warn("Geolocalización no soportada en este navegador.");
                }
            });
        </script>
    @endif

    <!-- Material Tailwind JS -->
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/script.js"></script>

</body>

<style>
    .sidebar-dark-danger .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-light-danger .nav-sidebar>.nav-item>.nav-link.active {
        background-color: #c2542d !important;
        color: #fff;
    }
</style>

</html>
