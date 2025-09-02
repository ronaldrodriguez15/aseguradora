@extends('adminlte::page')

@section('title', 'Geolocalización')

@section('content_header')
    <h1>Geolocalización</h1>
@stop

@section('content')
    <p>En este módulo puedes ver los usuarios que tienes asignados en tiempo real, es importante que los usuarios permitan
        su ubicación en tiempo real desde el dispositivo donde se encuentren.
    </p>
    <div class="row justify-content-center">
        <div class="col-md-12 text-right mb-5">
            <!-- Botones o acciones opcionales -->
        </div>

        <div class="col-md-12">
            <div class="card-body">
                <div class="row">
                    <!-- Columna izquierda: lista -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white">
                                Usuarios en el mapa
                            </div>
                            <div class="card-body p-2" style="max-height: 500px; overflow-y: auto;">
                                <ul id="users-list" class="list-group list-group-flush">
                                    <!-- Aquí se cargan dinámicamente -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha: mapa -->
                    <div class="col-md-8">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Mapa de Ubicaciones en Tiempo Real</h5>
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="card-body p-0">
                                <div id="map" style="height: 500px; width: 100%; border-radius: 0 0 .5rem .5rem;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        // Inicializar mapa centrado en Bogotá
        var map = L.map('map').setView([4.60971, -74.08175], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var markers = {};

        function addOrUpdateMarker(userId, lat, lng, nombre, documento) {
            let popupContent = `<b>${nombre}</b><br>Documento: ${documento}`;

            if (markers[userId]) {
                markers[userId].setLatLng([lat, lng]).bindPopup(popupContent);
            } else {
                markers[userId] = L.marker([lat, lng]).addTo(map)
                    .bindPopup(popupContent);
            }
        }

        function startTracking() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(function(position) {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;

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
                        })
                        .then(res => res.json())
                        .then(data => console.log("Ubicación guardada:", data))
                        .catch(err => console.error("Error:", err));
                });
            } else {
                alert("Tu navegador no soporta geolocalización.");
            }
        }

        function loadUsersLocations() {
            fetch("{{ route('get-locations') }}")
                .then(res => res.json())
                .then(response => {
                    let usersList = document.getElementById("users-list");
                    usersList.innerHTML = ""; 

                    if (response.message) {
                        Swal.fire({
                            icon: "warning",
                            title: "Atención",
                            text: response.message
                        });
                    }

                    if (Array.isArray(response.users)) {
                        response.users.forEach(user => {
                            if (user.latitude && user.longitude) {
                                addOrUpdateMarker(user.id, user.latitude, user.longitude, user.name, user
                                    .document);

                                let li = document.createElement("li");
                                li.innerHTML = `<b>${user.name}</b> - Doc: ${user.document}`;
                                usersList.appendChild(li);
                            }
                        });
                    }
                })
                .catch(err => console.error("Error cargando ubicaciones:", err));
        }

        startTracking();
        setInterval(loadUsersLocations, 5000); // cada 5 segundos
    </script>


@stop
