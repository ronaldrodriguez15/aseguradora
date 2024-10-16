@extends('adminlte::page')

@section('title', '| Reportes')

@section('content_header')
    <h1>Reportes</h1>
@stop

@section('content')
    <p>En este modulo puedes consultar y generar reportes acerca de las afiliaciones que están en el sistema, asimismo, el
        rol encargado puede descargar los documentos firmados de la afiliación correspondiente.</p>
    <br><br>

    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session()->get('success'))
                <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                    {{ session()->get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
        <div class="col-md-11 mb-5">
            <form autocomplete="off" action="{{ route('reportes.index') }}" method="get">
                @csrf
                <br>
                <div id="debito_automatico_fields">
                    <br>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="fecha_desde">Fecha desde</label>
                            <input type="date" class="form-control @error('fecha_desde') is-invalid @enderror"
                                id="fecha_desde" name="fecha_desde">
                            @error('fecha_desde')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fecha_hasta">Fecha hasta</label>
                            <input type="date" class="form-control @error('fecha_hasta') is-invalid @enderror"
                                id="fecha_hasta" name="fecha_hasta">
                            @error('fecha_hasta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="vendedor">Vendedor</label>
                            <select class="form-control @error('vendedor') is-invalid @enderror" id="vendedor"
                                name="vendedor">
                                <option value="">Selecciona un vendedor</option>
                                @foreach ($asesors as $asesor)
                                    <option value="{{ $asesor['name'] }}">{{ $asesor['name'] }}</option>
                                @endforeach
                            </select>
                            @error('vendedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="aseguradora">Aseguradora</label>
                            <select class="form-control @error('aseguradora') is-invalid @enderror" id="aseguradora"
                                name="aseguradora">
                                <option value="">Selecciona una aseguradora</option>
                                @foreach ($insurers as $insurer)
                                    <option value="{{ $insurer['id'] }}">{{ $insurer['name'] }}</option>
                                @endforeach
                            </select>
                            @error('aseguradora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="numero_afiliacion">Número de afiliación</label>
                            <input type="text" class="form-control @error('numero_afiliacion') is-invalid @enderror"
                                id="numero_afiliacion" name="numero_afiliacion"
                                placeholder="Introduce el número de afiliación">
                            @error('numero_afiliacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fecha_afiliacion">Fecha de afiliación</label>
                            <input type="date" class="form-control @error('fecha_afiliacion') is-invalid @enderror"
                                id="fecha_afiliacion" name="fecha_afiliacion">
                            @error('fecha_afiliacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cedula">Cédula</label>
                            <input type="text" class="form-control @error('cedula') is-invalid @enderror" id="cedula"
                                name="cedula" placeholder="Introduce la cédula">
                            @error('cedula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="entidad">Entidad</label>
                            <select class="form-control @error('entidad') is-invalid @enderror" id="entidad"
                                name="entidad">
                                <option value="">Selecciona una entidad</option>
                                @foreach ($entities as $entity)
                                    <option value="{{ $entity['name'] }}">{{ $entity['name'] }}</option>
                                @endforeach
                            </select>
                            @error('entidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="ciudad">Ciudad</label>
                            <select class="form-control @error('ciudad') is-invalid @enderror" id="ciudad"
                                name="ciudad">
                                <option value="">Selecciona la ciudad</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
                                @endforeach
                            </select>
                            @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-2">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control @error('edad') is-invalid @enderror" id="edad"
                                name="edad" placeholder="Introduce la edad">
                            @error('edad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-7 align-self-end text-right">
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="fas fa-search mr-2"></i> Consultar Afiliaciones
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Token CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="col-md-11">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="col-md-11">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="text-center">
                                <tr class="bg-dark text-white">
                                    <th>
                                        <input type="checkbox" id="select-all" />
                                    </th>
                                    <th>Fecha afiliación</th>
                                    <th>Consecutivo</th>
                                    <th>Entidad</th>
                                    <th>Nombres</th>
                                    <th>Cédula</th>
                                    <th>Valor</th>
                                    <th>Asesor</th>
                                    <th>Estado firma</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse ($inabilities as $inability)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="record-checkbox"
                                                value="{{ $inability['id'] }}" />
                                        </td>
                                        <td>{{ $inability['created_at']->format('Y-m-d') }}</td>
                                        <td>{{ $inability['no_solicitud'] }}</td>
                                        <td>{{ $inability['insurer_name'] }}</td>
                                        <td>{{ $inability['nombres_completos'] }}</td>
                                        <td>{{ $inability['no_identificacion'] }}</td>
                                        <td class="text-warning">{{ $inability['val_total_desc_mensual'] }}</td>
                                        <td>{{ $inability['nombre_asesor'] }}</td>
                                        <td>
                                            @if ($inability['estado_firmado'] == 'Sin firmar')
                                                <span class="badge badge-danger">{{ $inability['estado_firmado'] }}</span>
                                            @elseif($inability['estado_firmado'] == 'Pendiente')
                                                <span class="badge badge-info">{{ $inability['estado_firmado'] }}</span>
                                            @elseif($inability['estado_firmado'] == 'Firmado')
                                                <span
                                                    class="badge badge-success">{{ $inability['estado_firmado'] }}</span>
                                            @else
                                                <span
                                                    class="badge badge-warning">{{ $inability['estado_firmado'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No hay información para mostrar</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <!-- Token CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="col-md-11">
            <div class="mt-4" id="button-container" style="display: none;">
                <a class="btn btn-success mb-4" id="descargar-plano" href="#">
                    Descargar Plano Focus <i class="fas fa-file-excel ml-2"></i>
                </a>
                <a class="btn btn-success mb-4" id="descargar-seguimiento" href="#">
                    Descargar Seguimiento Ventas <i class="fas fa-file-excel ml-2"></i>
                </a>                
                <a class="btn btn-danger mb-4" id="descargar-pdfs" href="#">
                    Descargar PDFs <i class="fas fa-file-pdf ml-2"></i>
                </a>
            </div>
        </div>

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tu archivo de script personalizado -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/reports.js') }}"></script>
    <script>
        // Formatear la cédula mientras se escribe
        document.getElementById('cedula').addEventListener('input', function(e) {
            let cedula = e.target.value.replace(/\D/g, ''); // Elimina todo lo que no sean dígitos
            cedula = cedula.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Añade los puntos
            e.target.value = cedula; // Actualiza el valor en el input
        });

        // Seleccionar todos los checkboxes
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.record-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                toggleButtons();
            });
        });

        // Verificar el estado de los checkboxes
        document.querySelectorAll('.record-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleButtons);
        });

        // Función para mostrar u ocultar los botones
        function toggleButtons() {
            const checkboxes = document.querySelectorAll('.record-checkbox');
            const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            // Mostrar u ocultar el contenedor de botones
            document.getElementById('button-container').style.display = isAnyChecked ? 'block' : 'none';
        }

        // Inicialmente ocultar los botones
        toggleButtons();
    </script>
@stop
