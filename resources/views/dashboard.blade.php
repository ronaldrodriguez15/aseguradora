@extends('adminlte::page')

@section('title', 'Panel')

@section('content_header')
    <h1>
        @if ($isAdmin)
            Panel de administrador
        @elseif ($isSalesManager)
            Panel de jefe de ventas
        @elseif ($isSales)
            Panel de ventas
        @else
            Panel
        @endif
    </h1>
@stop

@section('content')
    <p class="mb-4">
        Consulta indicadores clave y tendencias recientes de tu operacion.
        @if ($isAdmin)
            Estas viendo datos globales del sistema.
        @elseif($isSalesManager)
            Estas viendo datos de tu equipo comercial.
        @elseif($isSales)
            Estas viendo solo tus resultados.
        @endif
    </p>

    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h4>Aseguradoras</h4>
                    <p><b>{{ $totalAseguradoras }}</b></p>
                </div>
                <div class="icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <a href="{{ route('aseguradoras.index') }}" class="small-box-footer">
                    Mas informacion <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h4>Afiliaciones</h4>
                    <p><b>{{ $totalAfiliaciones }}</b></p>
                </div>
                <div class="icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <a href="{{ route('incapacidades.index') }}" class="small-box-footer">
                    Mas informacion <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h4>{{ $isAdmin ? 'Usuarios activos' : ($isSalesManager ? 'Mi equipo' : 'Mi cuenta') }}</h4>
                    <p><b>{{ $totalUsuarios }}</b></p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('usuarios.index') }}" class="small-box-footer">
                    Mas informacion <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h4>Documentos firmados</h4>
                    <p><b>{{ $documentosFirmados }}</b></p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <a href="{{ route('reportes.index') }}" class="small-box-footer">
                    Ver reportes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center justify-content-between flex-wrap">
                <span>
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Afiliaciones en el mes actual: <strong>{{ $afiliacionesMesActual }}</strong>
                </span>
                <span class="text-muted small">Actualizado al momento de cargar el panel</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tendencia de afiliaciones (ultimos 6 meses)</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-12">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Metodos de pago</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container chart-container-sm">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">{{ $roleChartTitle }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="roleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Top asesores por afiliaciones</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="advisorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .chart-container {
            position: relative;
            height: 320px;
        }

        .chart-container-sm {
            height: 260px;
        }

        @media (max-width: 576px) {
            .small-box .inner h3 {
                font-size: 18px;
            }

            .small-box .inner p {
                font-size: 14px;
            }

            .small-box {
                padding: 10px;
            }

            .small-box-footer {
                font-size: 14px;
            }

            .small-box .icon {
                font-size: 40px;
                top: 10px;
                right: 10px;
            }

            .chart-container,
            .chart-container-sm {
                height: 260px;
            }
        }

        .small-box .icon {
            font-size: 60px;
            position: absolute;
            top: 15px;
            right: 10px;
            z-index: 0;
            opacity: 0.3;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = @json($monthlyLabels);
        const monthlyData = @json($monthlyData);
        const paymentLabels = @json($paymentLabels);
        const paymentData = @json($paymentData);
        const roleLabels = @json($roleChartLabels);
        const roleData = @json($roleChartData);
        const advisorLabels = @json($advisorLabels);
        const advisorData = @json($advisorData);

        const hasSeriesData = (series) => Array.isArray(series) && series.some((value) => Number(value) > 0);

        const defaultBarOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        };

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Afiliaciones',
                    data: monthlyData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.15)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3
                }]
            },
            options: {
                ...defaultBarOptions,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(document.getElementById('paymentChart'), {
            type: 'doughnut',
            data: {
                labels: paymentLabels.length ? paymentLabels : ['Sin datos'],
                datasets: [{
                    data: hasSeriesData(paymentData) ? paymentData : [1],
                    backgroundColor: hasSeriesData(paymentData) ? ['#17a2b8', '#ffc107', '#28a745', '#dc3545', '#6f42c1'] :
                        ['#d9d9d9'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        new Chart(document.getElementById('roleChart'), {
            type: 'bar',
            data: {
                labels: roleLabels.length ? roleLabels : ['Sin datos'],
                datasets: [{
                    data: hasSeriesData(roleData) ? roleData : [0],
                    backgroundColor: '#20c997',
                    borderRadius: 6
                }]
            },
            options: defaultBarOptions
        });

        new Chart(document.getElementById('advisorChart'), {
            type: 'bar',
            data: {
                labels: advisorLabels.length ? advisorLabels : ['Sin datos'],
                datasets: [{
                    data: hasSeriesData(advisorData) ? advisorData : [0],
                    backgroundColor: '#fd7e14',
                    borderRadius: 6
                }]
            },
            options: defaultBarOptions
        });

        function startGlobalTracking() {
            if (navigator.geolocation) {
                setInterval(() => {
                    navigator.geolocation.getCurrentPosition((position) => {
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
                        }).catch((err) => console.error("Error enviando ubicacion:", err));
                    });
                }, 5000);
            }
        }

        startGlobalTracking();
    </script>
@stop
