@extends('adminlte::page')

@section('title', 'Incapacidades')

@section('content_header')
    <h1>Afiliación terminada</h1>
@stop

@section('content')
    <p>Felicidades, la afiliación ha culminado con exito, descarga el PDF que genera el registro de la afiliación.
    </p>
    <br><br>
    <div class="container">
        <!-- Línea de progreso -->
        <div class="progress" style="position: relative; width: 100%; height: 30px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success rounded" role="progressbar"
                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; height: 100%;"></div>
            <div class="progress-text text-center"
                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #000000; font-weight: bold;">
                Paso 6 de 6 (Formulario terminado)
            </div>
        </div>
        <br>
        <div class="mt-2 mb-2">
            <!-- Controller response -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="row">
                    <div class="col-md-6"><span>Información de la afiliación</span></div>
                    <div class="col-md-6 text-right"><span>{{ now()->format('d/m/Y') }}</span><i
                            class="fas fa-calendar-week ml-2"></i></div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="tu_pierdes" class="text-danger">Tu pierdes!!!</label>
                        <input type="text" class="form-control form-control-lg is-invalid" id="tu_pierdes"
                            name="tu_pierdes" placeholder="000000" value="{{ $tu_pierdes }}" disabled required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="te_pagamos" class="text-info">Nosotros te pagamos!!!</label>
                        <input type="text" class="form-control form-control-lg border-info" id="te_pagamos"
                            name="te_pagamos" placeholder="000000" value="{{ $te_pagamos }}" disabled required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="val_total_desc_mensual" class="text-warning">Valor total de descuento mensual</label>
                        <input type="text" class="form-control form-control-lg is-warning" id="val_total_desc_mensual"
                            name="val_total_desc_mensual" placeholder="000000" value="{{ $val_total_desc_mensual }}"
                            disabled required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="edad" class="text-success">Edad del funcionario</label>
                        <input type="text" class="form-control form-control-lg is-valid" id="edad" name="edad"
                            placeholder="000000" value="{{ $edad }}" disabled required>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card mb-6">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <a class="btn btn-info" href="#">
                            Volver a inicio <i class="fas fa-home mrl-2"></i>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="loader-wrapper" id="loader" style="display: none;">
                            <div class="loader">
                                <div class="loader-inner ball-pulse-rise">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <a class="btn btn-danger mb-4" href="{{ route('incapacidades.generarPDF', $inabilityId) }}">
                            <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Estasseguro)
                        </a>
                        @if ($aseguradora === 'Positiva Seguros')
                            <a class="btn btn-danger mb-4"
                                href="{{ route('incapacidades.generarPDFpositiva', $inabilityId) }}">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Positiva)
                            </a>
                        @elseif ($aseguradora === 'Confianza Seguros')
                            <a class="btn btn-danger mb-4"
                                href="{{ route('incapacidades.generarPDFconfianza', $inabilityId) }}" target="_blank">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Confianza)
                            </a>
                        @endif
                        @if ($pago === 'mensual_libranza')
                            <a class="btn btn-danger mb-4"
                                href="{{ route('incapacidades.generarPDFLibranza', $inabilityId) }}">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (libranza)
                            </a>
                        @else
                            <a class="btn btn-danger mb-4"
                                href="{{ route('incapacidades.generarPDFdebito', $inabilityId) }}">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (debito)
                            </a>
                        @endif
                        <a class="btn btn-primary btn-lg" id="iniciar-firmado" data-id="{{ $inabilityId }}"
                            href="#">
                            <i class="fas fa-signature mr-2"></i> Iniciar firmado con Via Firma
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
@stop

@section('css')
    <style>
        @media (max-width: 576px) {
            .progress {
                height: 40px;
                /* Aumentar la altura */
            }

            .progress-text {
                font-size: 12px;
                /* Reducir el tamaño del texto */
                padding: 0;
                /* Reducir cualquier padding adicional */
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tu archivo de script personalizado -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/step7.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#iniciar-firmado').on('click', function(e) {
                e.preventDefault(); // Evita el comportamiento predeterminado del enlace

                var id = $(this).data('id'); // Obtener el ID desde el botón

                // Mostrar el loader usando SweetAlert
                Swal.fire({
                    title: 'Cargando...',
                    html: 'Por favor espera mientras se procesan los documentos.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Realiza la solicitud AJAX
                $.ajax({
                    url: '{{ route('sendToViaFirma') }}', // Ruta de la API
                    method: 'POST',
                    data: {
                        id: id, // Asegúrate de que el ID se está pasando aquí
                        _token: '{{ csrf_token() }}' // Incluye el token CSRF para Laravel
                    },
                    success: function(response) {
                        // Maneja la respuesta exitosa
                        console.log(response); // Muestra la respuesta en la consola
                        Swal.fire('Éxito', `ID validado: ${response.id}`, 'success');
                    },
                    error: function(xhr) {
                        // Maneja los errores
                        Swal.fire('Error', (xhr.responseJSON.error ||
                            'Ocurrió un error inesperado.'), 'error');
                    },
                    complete: function() {
                        // Cerrar el loader
                        Swal.close();
                    }
                });
            });
        });
    </script>
@stop
