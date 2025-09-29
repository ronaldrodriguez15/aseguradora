@extends('adminlte::page')

@section('title', 'Geolocalización')

@section('content_header')
    <h1>Geolocalización</h1>
@stop

@section('content')
    <p>En este módulo puedes ver los usuarios que tienes asignados en tiempo real, es importante que los usuarios permitan
        su ubicación en tiempo real desde el dispositivo donde se encuentren.
    </p>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-body p-2">
                                <form class="w-100" action="{{ route('geolocations.report') }}" method="POST"
                                    target="_blank">
                                    @csrf
                                    <div class="mb-2">
                                        <label for="users_geolocation" class="fw-bold d-block">
                                            Selecciona una opción para generar reporte de geolocalización
                                        </label>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <!-- Fecha inicio -->
                                        <div class="col-12 col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">Fecha inicial</span>
                                                <input type="date" name="fecha_desde" class="form-control"
                                                    value="{{ old('fecha_desde') }}" required>
                                            </div>
                                        </div>

                                        <!-- Fecha fin -->
                                        <div class="col-12 col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">Fecha final</span>
                                                <input type="date" name="fecha_hasta" class="form-control"
                                                    value="{{ old('fecha_hasta') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2">
                                        <!-- Select usuarios -->
                                        <div class="col-12 col-md-8">
                                            <select id="users_geolocation" name="user_id" class="form-control">
                                                <option value="all">Todos los usuarios</option>
                                                @foreach ($usersReport as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('user_id') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->name . ' (C.C ' . $value->document . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Botón generar -->
                                        <div class="col-12 col-md-4">
                                            <button type="submit" class="btn btn-danger w-100" style="min-width: 140px;">
                                                <i class="fas fa-file-pdf"></i> Generar Reporte
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-right mb-3">
                        <button id="btnActualizarUbicacion" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i> Actualizar ubicación
                        </button>
                    </div>
                    <!-- Columna izquierda: lista -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white">
                                Usuarios en el mapa
                            </div>
                            <div class="card-body p-2" style="max-height: 500px; overflow-y: auto;">
                                <ul id="users-list" class="list-group list-group-flush"></ul>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha: mapa -->
                    <div class="col-md-8">
                        <div class="card shadow-xl border-0 rounded-4 h-100 overflow-hidden">
                            <div
                                class="card-header bg-gradient-danger text-white d-flex justify-content-between align-items-center p-3">
                                <h5 class="mb-0 fw-bold"><i class="fas fa-map-marked-alt mr-2"></i>Mapa de Ubicaciones en
                                    Tiempo Real</h5>
                                <span class="badge bg-success bg-opacity-75 text-white px-3 py-2 rounded-pill">En
                                    Vivo</span>
                            </div>
                            <div class="card-body p-0 position-relative">
                                <div id="map"
                                    style="height: 600px; width: 100%; border-radius: 0 0 1rem 1rem; transition: all 0.3s ease;">
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <button class="btn btn-sm btn-light shadow-sm" title="Actualizar mapa">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="debug" class="text-muted small p-2 bg-light rounded-bottom"></div>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <style>
        .card {
            transition: box-shadow 0.3s ease;
            background: linear-gradient(145deg, #ffffff, #f0f2f5);
        }

        .card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .card-header.bg-gradient-dark {
            background: linear-gradient(45deg, #2c3e50, #34495e);
            border-bottom: none;
        }

        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #1e3a8a, #3b82f6);
            border-bottom: none;
        }

        .list-group-item {
            transition: transform 0.2s ease, background-color 0.2s ease;
            background-color: #ffffff;
            border: none;
        }

        .list-group-item:hover {
            transform: translateX(5px);
            background-color: #e9ecef;
        }

        .badge {
            transition: background-color 0.3s ease;
        }

        .badge:hover {
            background-color: #000 !important;
        }

        #map {
            background: #e5e7eb;
            transition: opacity 0.3s ease;
        }

        #map:hover {
            opacity: 0.98;
        }

        .btn-light {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
        }

        .btn-light:hover {
            background-color: #ffffff;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f3f5;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #6b7280;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
    </style>

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        var map = L.map('map').setView([4.60971, -74.08175], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var markers = {};
        var usersListElements = {};
        var markersGroup = L.markerClusterGroup();
        map.addLayer(markersGroup);

        let lastLat = null;
        let lastLng = null;
        const MIN_DISTANCE_METERS = 8;

        function getDistanceInMeters(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function addOrUpdateMarker(userId, lat, lng, nombre, documento) {
            let popupContent = `<b>${nombre}</b><br>Documento: ${documento}`;
            if (markers[userId]) {
                markers[userId].setLatLng([lat, lng]).bindPopup(popupContent);
            } else {
                let marker = L.marker([lat, lng]).bindPopup(popupContent);
                markers[userId] = marker;
                markersGroup.addLayer(marker);
            }
        }

        function addOrUpdateUserList(userId, nombre, documento) {
            let usersList = document.getElementById("users-list");
            let content = `
            <div class="avatar bg-primary text-white rounded-circle mr-2"
                 style="width: 35px; height: 35px; display:flex; justify-content:center; align-items:center;">
                ${nombre.charAt(0).toUpperCase()}
            </div>
            <div>
                <strong>${nombre}</strong><br>
                <small class="text-muted">Doc: ${documento}</small>
            </div>
        `;
            if (usersListElements[userId]) {
                usersListElements[userId].innerHTML = content;
            } else {
                let li = document.createElement("li");
                li.id = `user-${userId}`;
                li.className = "list-group-item d-flex align-items-center";
                li.innerHTML = content;
                usersList.appendChild(li);
                usersListElements[userId] = li;
            }
        }

        function startTracking() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    (position) => {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;

                        if (lastLat !== null && lastLng !== null) {
                            const distance = getDistanceInMeters(lastLat, lastLng, lat, lng);
                            if (distance < MIN_DISTANCE_METERS) {
                                return;
                            }
                        }

                        lastLat = lat;
                        lastLng = lng;

                        fetch("{{ route('store-hour-location') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    latitude: lat,
                                    longitude: lng
                                })
                            }).then(res => console.log("✅ Guardada nueva ubicación:", lat, lng))
                            .catch(err => console.error("❌ Error enviando ubicación:", err));
                    },
                    (error) => {
                        console.error("❌ Error obteniendo ubicación:", error);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("Geolocalización no soportada en este navegador.");
            }
        }

        function loadUsersLocations() {
            fetch("{{ route('get-locations') }}")
                .then(res => res.json())
                .then(response => {
                    if (Array.isArray(response.users)) {
                        let bounds = [];
                        response.users.forEach(user => {
                            if (user.latitude && user.longitude) {
                                addOrUpdateMarker(user.id, user.latitude, user.longitude, user.name, user
                                    .document);
                                addOrUpdateUserList(user.id, user.name, user.document);
                                bounds.push([user.latitude, user.longitude]);
                            }
                        });
                    }
                })
                .catch(err => console.error("Error cargando ubicaciones:", err));
        }

        // --- Botón de actualizar ubicación manual ---
        const btn = document.getElementById("btnActualizarUbicacion");
        if (btn) {
            btn.addEventListener("click", function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Ubicación actualizada correctamente',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            startTracking();
            loadUsersLocations();
            setInterval(loadUsersLocations, 5000);
        });
    </script>
@stop
