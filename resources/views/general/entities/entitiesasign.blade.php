@extends('adminlte::page')

@section('title', 'Entidades')

@section('content_header')
    <h1>Entidades</h1>
@stop

@section('content')
    <p>En este modulo puedes ver todo el registro de las Entidades, asimismo, puedes crear una nueva o editar las ya
        creadas.</p>
    <br><br><br>

    <div class="row">
        <!-- Select vendedor + buscar -->
        <div class="col-md-12 mb-3">
            <form action="{{ route('entities.asign') }}" method="GET" class="form-row align-items-end">
                <!-- Select vendedor -->
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <label for="filtro_tipo" class="form-label">Vendedor</label>
                    <select name="filtro_tipo" id="filtro_tipo" class="form-control select2">
                        <option value="">Selecciona vendedor</option>
                        <option value="all">
                            todos
                        </option>
                        @foreach ($vendedores as $vendedor)
                            <option value="{{ $vendedor->id }}">
                                {{ $vendedor->document . ' - ' . $vendedor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Botón buscar -->
                <div class="col-12 col-md-2 mb-2 mb-md-0">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </div>
                <!-- Botón regresar -->
                <div class="col-12 col-md-4 text-md-right">
                    <a href="{{ route('entidades.index') }}" class="btn btn-info w-100 w-md-auto">
                        <i class="fas fa-arrow-left mr-1"></i> Regresar
                    </a>
                </div>
            </form>
        </div>

        <!-- Mensaje de éxito -->
        <div class="col-md-12">
            @if (session()->get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session()->get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Mensaje de éxito -->
        @if (session()->get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (isset($vendedorSeleccionado))
            <div class="col-md-12">
                <h5 class="mb-3">
                    Empresas asociadas a: <i>{{ $vendedorSeleccionado->name }}</i>
                </h5>
            </div>
        @endif

        <div class="col-md-12">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-striped text-center">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Entidad</th>
                                    <th>Abreviatura</th>
                                    <th>Nit o código</th>
                                    <th>Sucursal</th>
                                    <th>Estado</th>
                                    <th>Fecha creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entities as $entity)
                                    <tr>
                                        <td><input type="checkbox" class="row-checkbox" value="{{ $entity->id }}"></td>
                                        <td>{{ $entity->name }}</td>
                                        <td>{{ $entity->nemo }}</td>
                                        <td><span class="badge bg-info">{{ $entity->cnitpagador }}</span></td>
                                        <td>{{ $entity->sucursal }}</td>
                                        <td>
                                            @if ($entity->status === 1)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($entity->created_at)
                                                {{ $entity->created_at->format('d/m/Y h:i A') }}
                                            @else
                                                <i class="text-muted">No disponible</i>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-muted">No hay entidades disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                @if (Auth::user()->hasRole('Administrador'))
                    <div class="mb-3" id="downloadContainer" style="display: none;">
                        <form id="exportarForm" method="POST" action="{{ route('entidades.exportar') }}">
                            @csrf
                            <input type="hidden" name="seleccionados" id="seleccionados">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Exportar Excel
                            </button>
                        </form>
                    </div>
                @endif
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
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const downloadContainer = document.getElementById('downloadContainer');

        document.getElementById('exportarForm').addEventListener('submit', function(e) {
            const seleccionados = [];
            document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                seleccionados.push(cb.value);
            });
            document.getElementById('seleccionados').value = JSON.stringify(seleccionados);
        });

        function toggleDownloadVisibility() {
            const anyChecked = [...checkboxes].some(cb => cb.checked);
            downloadContainer.style.display = anyChecked ? 'block' : 'none';
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleDownloadVisibility();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleDownloadVisibility);
        });
    </script>
@stop
