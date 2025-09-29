<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte General de Geolocalizaci贸n</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo {
            width: 80px;
        }

        h2 {
            text-align: center;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Encabezado con logo a la izquierda y t铆tulo centrado -->
    <table class="header">
        <tr>
            <td style="width: 20%;">
                <img src="{{ public_path('img/logo.png') }}" class="logo">
            </td>
            <td style="width: 60%; text-align: center;">
                <h2>Reporte General de Geolocalizaci贸n</h2>
            </td>
            <td style="width: 20%;">
                {{ \Carbon\Carbon::now()->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Documento</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Latitud</th>
                <th>Longitud</th>
                <th>Mapa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($locations as $loc)
                @php
                    $user = $users->where('id', $loc->user_id)->first();
                @endphp
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->document }}</td>
                    <td>{{ $loc->fecha }}</td>
                    <td>{{ $loc->hora }}</td>
                    <td>{{ $loc->latitude }}</td>
                    <td>{{ $loc->longitude }}</td>
                    <td>
                        <a href="https://www.google.com/maps?q={{ $loc->latitude }},{{ $loc->longitude }}"
                            target="_blank">
                            Ver en mapa
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; font-style: italic;">
                        No hay registros por mostrar
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
