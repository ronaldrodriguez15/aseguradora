@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
<h1>Administración de usuarios</h1>
@stop

@section('content')
<p>En este modulo puedes administrar todos los usuarios que tienen acceso al sistema.</p>
<br><br><br>

<div class="row justify-content-center">
    <div class="col-md-12 text-right mb-4">
        <!-- Controller response -->
        <a class="btn btn-success" href="{{ route('usuarios.create') }}">
            <i class="fas fa-plus-circle mr-2"></i>Nuevo usuario
        </a>
    </div>
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
    <div class="col-md-12">
        <div class="card mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead class="text-center">
                            <tr class="table-dark text-white">
                                <th>Nombres y apellidos</th>
                                <th>Celular</th>
                                <th>Correo electrónico</th>
                                <th>Nacimiento</th>
                                <th>Rol</th>
                                <th>Creación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario['name'] }}</td>
                                <td>{{ $usuario['phone'] }}</td>
                                <td>{{ $usuario['email'] }}</td>
                                <td>{{ $usuario['birthdate'] }}</td>
                                <td>
                                    @php
                                    $roles = $usuario->getRoleNames(); 
                                    $roleColor = 'badge-secondary'; 
                                    $roleName = 'Sin definir'; 

                                    if ($roles->isNotEmpty()) {
                                    $roleName = $roles->first(); 
                                    
                                    if ($roleName === 'Administrador') {
                                    $roleColor = 'badge-success'; // Azul
                                    } else {
                                    $roleColor = 'badge-primary'; // Verde
                                    }
                                    }
                                    @endphp
                                    <span class="badge {{ $roleColor }}">{{ $roleName }}</span>
                                </td>
                                <td>{{ $usuario['created_at']->format('Y-m-d') }}</td>
                                <td>
                                    @if($usuario['status'] === '1')
                                    <span class="badge badge-success">Activo</span>
                                    @else
                                    <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($usuario['status'] === '1')
                                    @if($usuario['id'] === Auth::user()->id)
                                    <span class="badge badge-info">Logueado</span>
                                    @else
                                    <div class="button-container">
                                        <a class="btn btn-success btn-sm" href="{{ route('usuarios.edit', $usuario['id']) }}" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('usuarios.destroy', $usuario['id']) }}" method="POST" id="formDelete-{{ $usuario['id'] }}">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $usuario['id'] }}" onclick="confirmDelete(this)" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
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
