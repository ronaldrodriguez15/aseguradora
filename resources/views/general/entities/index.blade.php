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

        <div class="row align-items-end mb-3">
            <!-- Buscador -->
            <div class="col-12 col-lg-8">
                <form action="{{ route('entidades.index') }}" method="GET" class="form-row g-2 align-items-end">
                    <!-- Campo -->
                    <div class="col-12 col-md-4">
                        <label for="filtro_tipo" class="form-label">Seleccionar campo</label>
                        <select name="filtro_tipo" id="filtro_tipo" class="form-control select2">
                            <option value="">Ninguno</option>
                            @foreach ($camposEntidad as $key => $campo)
                                <option value="{{ $key }}">{{ $campo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Buscar -->
                    <div class="col-12 col-md-4">
                        <label for="buscar" class="form-label">Buscar</label>
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar...">
                    </div>
                    <!-- Botón buscar -->
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-primary w-100" title="Buscar">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Botones de acción -->
            @if (Auth::user()->hasRole('Administrador'))
                <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-end gap-2">
                        <a href="{{ route('entidades.create') }}" class="btn btn-success d-inline-flex align-items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Nueva Entidad
                        </a>
                        <a href="{{ route('entities.asign') }}"
                            class="btn btn-info d-inline-flex align-items-center mt-2 ml-2">
                            <i class="fas fa-building mr-2"></i> Consulta Entidades Asociadas
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Mensaje de éxito --}}
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

        <div class="col-md-12">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="text-center">
                                <tr class="table-dark text-white">
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Entidad</th>
                                    <th>Abreviatura</th>
                                    <th>Nit o código</th>
                                    <th>Sucursal</th>
                                    <th>Estado</th>
                                    <th>Fecha creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($entities as $entity)
                                    <tr>
                                        <td><input type="checkbox" class="row-checkbox" value="{{ $entity['id'] }}"></td>

                                        @if (Auth::user()->name_entity === 1 ||
                                                Auth::user()->hasRole('Administrador') ||
                                                Auth::user()->hasRole('Jefe de ventas'))
                                            <td>{{ $entity['name'] }}</td>
                                        @else
                                            <td class="text-muted"><i>No disponible</i></td>
                                        @endif

                                        @if (Auth::user()->nemo === 1 || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                            <td>{{ $entity['nemo'] }}</td>
                                        @else
                                            <td class="text-muted"><i>No disponible</i></td>
                                        @endif

                                        @if (Auth::user()->cnitpagador === 1 ||
                                                Auth::user()->hasRole('Administrador') ||
                                                Auth::user()->hasRole('Jefe de ventas'))
                                            <td><span class="badge bg-info">{{ $entity['cnitpagador'] }}</span></td>
                                        @else
                                            <td class="text-muted"><i>No disponible</i></td>
                                        @endif

                                        @if (Auth::user()->sucursal === 1 || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                            <td>{{ $entity['sucursal'] }}</td>
                                        @else
                                            <td class="text-muted"><i>No disponible</i></td>
                                        @endif

                                        <td>
                                            @if ($entity['status'] === 1 || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($entity->created_at)
                                                {{ $entity->created_at->format('d/m/Y h:i A') }}
                                            @else
                                                <i class="text-muted">No disponible</i>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($entity['status'] === '1')
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item text-primary"
                                                                href="{{ route('entidades.show', $entity['id']) }}">
                                                                <i class="fas fa-eye me-1"></i> Ver detalles
                                                            </a>
                                                        </li>

                                                        @if (Auth::user()->permisos_entidades === '1' || Auth::user()->hasRole('Administrador'))
                                                            <li>
                                                                <a class="dropdown-item text-success"
                                                                    href="{{ route('entidades.edit', $entity['id']) }}">
                                                                    <i class="fas fa-edit me-1"></i> Editar
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (Auth::user()->hasRole('Administrador'))
                                                            <li>
                                                                <form
                                                                    action="{{ route('entidades.destroy', $entity['id']) }}"
                                                                    method="POST" id="formDelete-{{ $entity['id'] }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger delete-btn"
                                                                        data-id="{{ $entity['id'] }}"
                                                                        onclick="confirmDelete(this)">
                                                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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

    <!-- Modal -->
    <div class="modal fade" id="modalNuevaCiudad" tabindex="-1" role="dialog" aria-labelledby="modalNuevaSedeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaCiudadLabel">Entidades</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" action="{{ route('entidades.store') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Nombre de la entidad <span class="required">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Introduce el nombre de la entidad"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nemo">Abreviatura <span class="required">*</span></label>
                                <input type="text" class="form-control @error('nemo') is-invalid @enderror"
                                    id="nemo" name="nemo" placeholder="Introduce la abreviatura de la entidad"
                                    required>
                                @error('nemo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cnitpagador">NIT (código) <span class="required">*</span></label>
                                <input type="number" class="form-control @error('cnitpagador') is-invalid @enderror"
                                    id="cnitpagador" name="cnitpagador" placeholder="000" required>
                                @error('cnitpagador')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="sucursal">Sucursal <span class="required">*</span></label>
                                <input type="number" class="form-control @error('sucursal') is-invalid @enderror"
                                    id="sucursal" name="sucursal" placeholder="000000" required>
                                @error('sucursal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button" class="btn btn-success">
                        <i class="fas fa-save mr-2"></i> Guardar entidad
                    </button>
                    </form>
                </div>
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
    <!-- Script para manejo de selección -->
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
