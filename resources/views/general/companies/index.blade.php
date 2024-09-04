@extends('adminlte::page')

@section('title', 'Empresas')

@section('content_header')
<h1>Empresas</h1>
@stop

@section('content')
<p>En este modulo puedes ver todo el registro de las Empresas, asimismo, puedes crear una nueva o editar las ya creadas.</p>
<br><br><br>

<div class="row justify-content-center">
    <div class="col-md-12 text-right mb-5">
        <a class="btn btn-success" id="btnNuevaCiudad">
            <i class="fas fa-plus-circle mr-2"></i>Nueva Empresas
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
                                <th>Nombres</th>
                                <th>Fecha creación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company['name'] }}</td>
                                <td>{{ $company['created_at']->format('Y-m-d - H:m') }}</td>
                                <td>
                                    @if($company['status'] === 1)
                                    <span class="badge badge-success">Activo</span>
                                    @else
                                    <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($company['status'] === 1)
                                    <div class="button-container">
                                        <form action="{{ route('empresas.destroy', $company['id']) }}" method="POST" id="formDelete-{{ $company['id'] }}">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $company['id'] }}" onclick="confirmDelete(this)" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaCiudadLabel">Empresas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" action="{{ route('empresas.store') }}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name">Nombre <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Introduce el nombre de la Empresa" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" type="button" class="btn btn-success">
                    <i class="fas fa-save mr-2"></i> Guardar Empresa
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
