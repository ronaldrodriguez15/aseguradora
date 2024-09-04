@extends('adminlte::page')

@section('title', 'Bancos')

@section('content_header')
<h1>Bancos</h1>
@stop

@section('content')
<p>En este modulo puedes ver todo el registro de los bancos, asimismo, puedes crear uno nuevo o editar los ya creados.</p>
<br><br><br>

<div class="row justify-content-center">
    <div class="col-md-12 text-right mb-5">
        <a class="btn btn-success" id="btnNuevaCiudad">
            <i class="fas fa-plus-circle mr-2"></i>Nuevo Banco
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
                                <th>Tipo de entidad</th>
                                <th>Área</th>
                                <th>Encargado</th>
                                <th>Cargo</th>
                                <th>Télefono</th>
                                <th>Correo</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($banks as $bank)
                            <tr>
                                <td>{{ $bank['name'] }}</td>
                                <td>{{ $bank['type_entity'] }}</td>
                                <td>{{ $bank['area'] }}</td>
                                <td>{{ $bank['official_in_charge'] }}</td>
                                <td>{{ $bank['employment'] }}</td>
                                <td>{{ $bank['phone'] }}</td>
                                <td>{{ $bank['email'] }}</td>
                                <td>{{ $bank['address'] }}</td>
                                <td>
                                    @if($bank['status'] === 1)
                                    <span class="badge badge-success">Activo</span>
                                    @else
                                    <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bank['status'] === 1)
                                    <div class="button-container">
                                        <a class="btn btn-success btn-sm" href="{{ route('bancos.edit', $bank['id']) }}" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('bancos.destroy', $bank['id']) }}" method="POST" id="formDelete-{{ $bank['id'] }}">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $bank['id'] }}" onclick="confirmDelete(this)" title="Eliminar">
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaCiudadLabel">Ciudad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" action="{{ route('bancos.store') }}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Entidad <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Introduce el nombre de la entidad" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="type_entity">Tipo de entidad <span class="required">*</span></label>
                            <input type="text" class="form-control @error('type_entity') is-invalid @enderror" id="type_entity" name="type_entity" placeholder="Introduce el tipo de la entidad" required>
                            @error('type_entity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="area">Área <span class="required">*</span></label>
                            <input type="text" class="form-control @error('area') is-invalid @enderror" id="area" name="area" placeholder="Introduce el àrea" required>
                            @error('area')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="official_in_charge">Funcionario encargado <span class="required">*</span></label>
                            <input type="text" class="form-control @error('official_in_charge') is-invalid @enderror" id="official_in_charge" name="official_in_charge" placeholder="Introduce el nombre del funcionario" required>
                            @error('official_in_charge')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="employment">Cargo <span class="required">*</span></label>
                            <input type="text" class="form-control @error('employment') is-invalid @enderror" id="employment" name="employment" placeholder="Describe el cargo" required>
                            @error('employment')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Télefono <span class="required">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Número túlefonico" required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="mobile">Celular <span class="required">*</span></label>
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="Nùmero celular" required>
                            @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Correo Electronico <span class="required">*</span></label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="alguien@example.com" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="address">Dirección de la entidad <span class="required">*</span></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Introduce la dirección de la entidad" required>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" type="button" class="btn btn-success">
                    <i class="fas fa-save mr-2"></i> Guardar Banco
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
