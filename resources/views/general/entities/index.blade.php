@extends('adminlte::page')

@section('title', 'Entidades')

@section('content_header')
<h1>Entidades</h1>
@stop

@section('content')
<p>En este modulo puedes ver todo el registro de las Entidades, asimismo, puedes crear una nueva o editar las ya creadas.</p>
<br><br><br>

<div class="row justify-content-center">
    <div class="col-md-12 text-right mb-5">
        <a class="btn btn-success" href="{{ route('entidades.create') }}">
            <i class="fas fa-plus-circle mr-2"></i>Nueva Entidad
        </a>
    </div>
    <div class="col-md-12">
        <!-- Controller response -->
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
                                <th>Entidad</th>
                                <th>Abreviatura</th>
                                <th>Nit o código</th>
                                <th>Sucursal</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($entities as $entity)
                            <tr>
                                <td>{{ $entity['name'] }}</td>
                                <td>{{ $entity['nemo'] }}</td>
                                <td><span class="badge bg-info">{{ $entity['cnitpagador'] }}</span></td>
                                <td>{{ $entity['sucursal'] }}</td>
                                <td>
                                    @if($entity['status'] === 1)
                                    <span class="badge badge-success">Activo</span>
                                    @else
                                    <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($entity['status'] === 1)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item text-success" href="{{ route('entidades.edit', $entity['id']) }}">
                                                    <i class="fas fa-edit me-1"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('entidades.destroy', $entity['id']) }}" method="POST" id="formDelete-{{ $entity['id'] }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item text-danger delete-btn" data-id="{{ $entity['id'] }}" onclick="confirmDelete(this)">
                                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            </li>
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
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalNuevaCiudad" tabindex="-1" role="dialog" aria-labelledby="modalNuevaSedeLabel" aria-hidden="true">
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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Introduce el nombre de la entidad" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nemo">Abreviatura <span class="required">*</span></label>
                            <input type="text" class="form-control @error('nemo') is-invalid @enderror" id="nemo" name="nemo" placeholder="Introduce la abreviatura de la entidad" required>
                            @error('nemo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cnitpagador">NIT (código) <span class="required">*</span></label>
                            <input type="number" class="form-control @error('cnitpagador') is-invalid @enderror" id="cnitpagador" name="cnitpagador" placeholder="000" required>
                            @error('cnitpagador')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sucursal">Sucursal <span class="required">*</span></label>
                            <input type="number" class="form-control @error('sucursal') is-invalid @enderror" id="sucursal" name="sucursal" placeholder="000000" required>
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
@stop
