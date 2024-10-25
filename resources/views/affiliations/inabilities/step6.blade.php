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
                Formulario completado 100%
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
                        <a class="btn btn-danger mb-4 pdf-btn" id="generar-pdf"
                            data-url="{{ route('incapacidades.generarPDF', $inabilityId) }}">
                            <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Estasseguro)
                        </a>
                        @if ($aseguradora === 'Positiva Seguros')
                            <a class="btn btn-danger mb-4 pdf-btn" id="btn-positiva"
                                data-url="{{ route('incapacidades.generarPDFpositiva', $inabilityId) }}">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Positiva)
                            </a>
                        @elseif ($aseguradora === 'Confianza Seguros')
                            <a class="btn btn-danger mb-4 pdf-btn" id="btn-confianza"
                                href="{{ route('incapacidades.generarPDFconfianza', $inabilityId) }}">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Confianza)
                            </a>
                        @endif

                        @if ($pago === 'mensual_libranza')
                            <a class="btn btn-danger mb-4 pdf-btn" id="btn-libranza"
                                href="{{ route('incapacidades.generarPDFLibranza', $inabilityId) }}">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Libranza)
                            </a>
                        @else
                            <a class="btn btn-danger mb-4 pdf-btn" id="btn-debito"
                                href="{{ route('incapacidades.generarPDFdebito', $inabilityId) }}">
                                <i class="fas fa-file-pdf mr-2"></i>Generar PDF (Debito)
                            </a>
                        @endif

                        <a class="btn btn-primary mb-4" id="iniciar-firmado" data-id="{{ $inabilityId }}" href="#">
                            <i class="fas fa-envelope-open-text mr-2"></i> Iniciar firmado
                        </a>

                        <a class="btn btn-primary mb-4" id="consultar-documentos" data-id="{{ $inabilityId }}"
                            href="#">
                            <i class="fas fa-signature mr-2"></i> Verificar documentos firmados
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentModalLabel">Visualización del Documento Firmado</h5>
                </div>
                <div class="modal-body">
                    <!-- Div que mostrará el documento PDF -->
                    <canvas id="pdfViewerCanvas" style="width: 100%;"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalBtn" class="btn btn-primary">Ir a inicio</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="documentModalGenerate" tabindex="-1" aria-labelledby="documentModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentModalLabel">Visualización del Documento Generado</h5>
                </div>
                <div class="modal-body">
                    <!-- Div que mostrará el documento PDF -->
                    <canvas id="pdfViewerCanvasGenerate" style="width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>


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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <!-- Tu archivo de script personalizado -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/step7.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Limpiar las claves específicas del localStorage cuando la página se carga
            // localStorage.removeItem('firmadoIniciado');
            // localStorage.removeItem('pdfGenerated');
            // localStorage.removeItem('pdfGeneratedPositiva');
            // localStorage.removeItem('pdfGeneratedLibranza');
            // localStorage.removeItem('pdfGeneratedDebito');
            // localStorage.removeItem('pdfGeneratedConfianza');

            // $('#generar-pdf').show();
            // $('#iniciar-firmado').show();
            // $('#btn-positiva').show();
            // $('#btn-libranza').show();
            // $('#btn-debito').show();
            // $('#btn-confianza').show();

            if (localStorage.getItem('firmadoIniciado') === 'true') {
                $('#iniciar-firmado').hide();
            }

            $('#iniciar-firmado').on('click', function(e) {
                e.preventDefault();

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
                    url: '/envio-viafirma',
                    method: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Verificar si hay éxito en la respuesta
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success');

                            // Ocultar el botón y guardar el estado en localStorage
                            $('#iniciar-firmado').hide();
                            localStorage.setItem('firmadoIniciado', 'true');

                            console.log(response
                                .data); // Puedes usar los datos como lo necesites
                        } else {
                            Swal.fire('Error', response.message || 'Ocurrió un error.',
                                'error');
                        }
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

            $('#consultar-documentos').on('click', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                Swal.fire({
                    title: 'Consultando Documentos...',
                    html: 'Por favor espera mientras verificamos los documentos firmados.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/consulta-viafirma',
                    method: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();

                        if (response.firstDocument) {
                            var pdfUrl = '/storage/' + response.firstDocument.document_path;

                            // Cargar el PDF usando PDF.js
                            var loadingTask = pdfjsLib.getDocument(pdfUrl);
                            loadingTask.promise.then(function(pdf) {
                                // Renderizar la primera página del PDF en el canvas
                                pdf.getPage(1).then(function(page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({
                                        scale: scale
                                    });

                                    var canvas = document.getElementById(
                                        'pdfViewerCanvas');
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            });

                            // Abrir el modal
                            $('#documentModal').modal('show');
                        } else {
                            Swal.fire('Error', 'No se encontró el documento firmado.', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error',
                            'Los documentos aún no han sido firmados o ocurrió un error. Por favor, intente nuevamente.',
                            'error');
                    }
                });
            });

            // Acción del botón "Cerrar" para redirigir al usuario
            $('#closeModalBtn').on('click', function() {
                // Limpiar las claves específicas del localStorage que usamos para los botones
                localStorage.removeItem('firmadoIniciado');
                localStorage.removeItem('pdfGenerated');
                localStorage.removeItem('pdfGeneratedPositiva');
                localStorage.removeItem('pdfGeneratedLibranza');
                localStorage.removeItem('pdfGeneratedDebito');
                localStorage.removeItem('pdfGeneratedConfianza');

                $('#generar-pdf').show();
                $('#iniciar-firmado').show();
                $('#btn-positiva').show();
                $('#btn-libranza').show();
                $('#btn-debito').show();
                $('#btn-confianza').show();

                // Redirigir al usuario a la ruta deseada
                window.location.href = "{{ route('incapacidades.index') }}";
            });


            // Deshabilitar cerrar el modal con la X o al hacer clic fuera
            $('#documentModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            if (localStorage.getItem('pdfGenerated') === 'true') {
                $('#generar-pdf').hide(); // Ocultar el botón si ya fue presionado
            }

            $('#generar-pdf').on('click', function(e) {
                // Evita el comportamiento predeterminado del enlace
                e.preventDefault();

                // Verifica si el botón ya ha sido ocultado
                if ($(this).is(':hidden')) {
                    return; // Si el botón ya está oculto, no hacer nada
                }

                var url = $(this).data('url');

                // Cambiar el texto del botón a "Generando..."
                $(this).html('<i class="fas fa-check mr-2"></i>Generando...');

                // Ocultar el botón
                $(this).hide(); // Ocultar el botón

                localStorage.setItem('pdfGenerated', 'true');

                Swal.fire({
                    title: 'Generando PDF...',
                    html: 'Por favor espera mientras generamos el documento.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        Swal.close();

                        if (response.pdf_url) {
                            var pdfUrl = response
                                .pdf_url; // Asegúrate que esta URL sea correcta

                            // Cargar el PDF usando PDF.js
                            var loadingTask = pdfjsLib.getDocument(pdfUrl);
                            loadingTask.promise.then(function(pdf) {
                                // Renderizar la primera página del PDF en el canvas
                                pdf.getPage(1).then(function(page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({
                                        scale: scale
                                    });

                                    var canvas = document.getElementById(
                                        'pdfViewerCanvasGenerate'
                                    ); // Asegúrate de que este canvas exista en tu HTML
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            }).catch(function(error) {
                                console.error('Error al cargar el PDF: ', error);
                                Swal.fire('Error', 'No se pudo cargar el PDF.',
                                    'error');
                            });

                            // Abrir el modal
                            $('#documentModalGenerate').modal(
                                'show'); // Asegúrate de que este modal exista en tu HTML
                        } else {
                            Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON.error ||
                                'Ocurrió un error inesperado.',
                            icon: 'error',
                            allowOutsideClick: false
                        });
                    }
                });
            });

            if (localStorage.getItem('pdfGeneratedPositiva') === 'true') {
                $('#btn-positiva').hide(); // Ocultar el botón si ya fue presionado
            }

            $('#btn-positiva').on('click', function(e) {
                // Evita el comportamiento predeterminado del enlace
                e.preventDefault();

                // Verifica si el botón ya ha sido ocultado
                if ($(this).is(':hidden')) {
                    return; // Si el botón ya está oculto, no hacer nada
                }

                var url = $(this).data('url');

                // Cambiar el texto del botón a "Generando..."
                $(this).html('<i class="fas fa-check mr-2"></i>Generando...');

                // Ocultar el botón
                $(this).hide(); // Ocultar el botón

                localStorage.setItem('pdfGeneratedPositiva', 'true');

                Swal.fire({
                    title: 'Generando PDF...',
                    html: 'Por favor espera mientras generamos el documento.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        Swal.close();

                        // Suponiendo que el PDF se genera y la URL se devuelve
                        if (response.pdf_url) {
                            var pdfUrl = response
                                .pdf_url; // Asegúrate que esta URL sea correcta

                            // Cargar el PDF usando PDF.js
                            var loadingTask = pdfjsLib.getDocument(pdfUrl);
                            loadingTask.promise.then(function(pdf) {
                                // Renderizar la primera página del PDF en el canvas
                                pdf.getPage(1).then(function(page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({
                                        scale: scale
                                    });

                                    var canvas = document.getElementById(
                                        'pdfViewerCanvasGenerate'
                                    ); // Asegúrate de que este canvas exista en tu HTML
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            }).catch(function(error) {
                                console.error('Error al cargar el PDF: ', error);
                                Swal.fire('Error', 'No se pudo cargar el PDF.',
                                    'error');
                            });

                            // Abrir el modal
                            $('#documentModalGenerate').modal(
                                'show'); // Asegúrate de que este modal exista en tu HTML
                        } else {
                            Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON.error ||
                                'Ocurrió un error inesperado.',
                            icon: 'error',
                            allowOutsideClick: false
                        });
                    }
                });
            });

            if (localStorage.getItem('pdfGeneratedLibranza') === 'true') {
                $('#btn-libranza').hide(); // Ocultar el botón si ya fue presionado
            }

            $('#btn-libranza').on('click', function(e) {
                // Evita el comportamiento predeterminado del enlace
                e.preventDefault();

                // Verifica si el botón ya ha sido ocultado
                if ($(this).is(':hidden')) {
                    return; // Si el botón ya está oculto, no hacer nada
                }

                var url = $(this).attr('href'); // Obtiene la URL del atributo href

                // Cambiar el texto del botón a "Generando..."
                $(this).html('<i class="fas fa-check mr-2"></i>Generando...');

                // Ocultar el botón
                $(this).hide(); // Ocultar el botón

                localStorage.setItem('pdfGeneratedLibranza', 'true');

                Swal.fire({
                    title: 'Generando PDF...',
                    html: 'Por favor espera mientras generamos el documento.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        Swal.close();

                        // Suponiendo que el PDF se genera y la URL se devuelve
                        if (response.pdf_url) {
                            var pdfUrl = response
                                .pdf_url; // Asegúrate que esta URL sea correcta

                            // Cargar el PDF usando PDF.js
                            var loadingTask = pdfjsLib.getDocument(pdfUrl);
                            loadingTask.promise.then(function(pdf) {
                                // Renderizar la primera página del PDF en el canvas
                                pdf.getPage(1).then(function(page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({
                                        scale: scale
                                    });

                                    var canvas = document.getElementById(
                                        'pdfViewerCanvasGenerate'
                                    ); // Asegúrate de que este canvas exista en tu HTML
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            }).catch(function(error) {
                                console.error('Error al cargar el PDF: ', error);
                                Swal.fire('Error', 'No se pudo cargar el PDF.',
                                    'error');
                            });

                            // Abrir el modal
                            $('#documentModalGenerate').modal(
                                'show'); // Asegúrate de que este modal exista en tu HTML
                        } else {
                            Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON.error ||
                                'Ocurrió un error inesperado.',
                            icon: 'error',
                            allowOutsideClick: false
                        });
                    }
                });
            });

            if (localStorage.getItem('pdfGeneratedDebito') === 'true') {
                $('#btn-debito').hide(); // Ocultar el botón si ya fue presionado
            }

            $('#btn-debito').on('click', function(e) {
                // Evita el comportamiento predeterminado del enlace
                e.preventDefault();

                // Verifica si el botón ya ha sido ocultado
                if ($(this).is(':hidden')) {
                    return; // Si el botón ya está oculto, no hacer nada
                }

                var url = $(this).attr('href'); // Obtiene la URL del atributo href

                // Cambiar el texto del botón a "Generando..."
                $(this).html('<i class="fas fa-check mr-2"></i>Generando...');

                // Ocultar el botón
                $(this).hide(); // Ocultar el botón

                localStorage.setItem('pdfGeneratedDebito', 'true');

                Swal.fire({
                    title: 'Generando PDF...',
                    html: 'Por favor espera mientras generamos el documento.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        Swal.close();

                        // Suponiendo que el PDF se genera y la URL se devuelve
                        if (response.pdf_url) {
                            var pdfUrl = response
                                .pdf_url; // Asegúrate que esta URL sea correcta

                            // Cargar el PDF usando PDF.js
                            var loadingTask = pdfjsLib.getDocument(pdfUrl);
                            loadingTask.promise.then(function(pdf) {
                                // Renderizar la primera página del PDF en el canvas
                                pdf.getPage(1).then(function(page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({
                                        scale: scale
                                    });

                                    var canvas = document.getElementById(
                                        'pdfViewerCanvasGenerate'
                                    ); // Asegúrate de que este canvas exista en tu HTML
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            }).catch(function(error) {
                                console.error('Error al cargar el PDF: ', error);
                                Swal.fire('Error', 'No se pudo cargar el PDF.',
                                    'error');
                            });

                            // Abrir el modal
                            $('#documentModalGenerate').modal(
                                'show'); // Asegúrate de que este modal exista en tu HTML
                        } else {
                            Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON.error ||
                                'Ocurrió un error inesperado.',
                            icon: 'error',
                            allowOutsideClick: false
                        });
                    }
                });
            });

            if (localStorage.getItem('pdfGeneratedConfianza') === 'true') {
                $('#btn-confianza').hide(); // Ocultar el botón si ya fue presionado
            }

            $('#btn-confianza').on('click', function(e) {
                // Evita el comportamiento predeterminado del enlace
                e.preventDefault();

                // Verifica si el botón ya ha sido ocultado
                if ($(this).is(':hidden')) {
                    return; // Si el botón ya está oculto, no hacer nada
                }

                var url = $(this).attr('href'); // Obtiene la URL del atributo href

                // Cambiar el texto del botón a "Generando..."
                $(this).html('<i class="fas fa-spinner fa-spin mr-2"></i>Generando...');

                // Ocultar el botón
                $(this).hide(); // Ocultar el botón

                localStorage.setItem('pdfGeneratedConfianza', 'true');

                Swal.fire({
                    title: 'Generando PDF...',
                    html: 'Por favor espera mientras generamos el documento.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        Swal.close();

                        // Suponiendo que el PDF se genera y la URL se devuelve
                        if (response.pdf_url) {
                            var pdfUrl = response
                                .pdf_url; // Asegúrate que esta URL sea correcta

                            // Cargar el PDF usando PDF.js
                            var loadingTask = pdfjsLib.getDocument(pdfUrl);
                            loadingTask.promise.then(function(pdf) {
                                // Renderizar la primera página del PDF en el canvas
                                pdf.getPage(1).then(function(page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({
                                        scale: scale
                                    });

                                    var canvas = document.getElementById(
                                        'pdfViewerCanvasGenerate'
                                    ); // Asegúrate de que este canvas exista en tu HTML
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            }).catch(function(error) {
                                console.error('Error al cargar el PDF: ', error);
                                Swal.fire('Error', 'No se pudo cargar el PDF.',
                                    'error');
                            });

                            // Abrir el modal
                            $('#documentModalGenerate').modal(
                                'show'); // Asegúrate de que este modal exista en tu HTML
                        } else {
                            Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON.error ||
                                'Ocurrió un error inesperado.',
                            icon: 'error',
                            allowOutsideClick: false
                        });
                    }
                });
            });
        });
    </script>
@stop
