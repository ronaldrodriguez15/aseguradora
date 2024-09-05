@extends('adminlte::page')

@section('title', 'Incapacidades')

@section('content_header')
    <h1>Incapacidades</h1>
@stop

@section('content')
    <p>En este modulo puedes ver todo el registro de las Incapacidades, asimismo, puedes ver los detalles de las ya creadas.
    </p>
    <br><br><br>

    <div class="row justify-content-center">
        <div class="col-md-12 text-right mb-4">
            <a class="btn btn-success" href="{{ route('incapacidades.create') }}">
                <i class="fas fa-plus-circle mr-2"></i>Nueva afiliación
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
                                    <th>No Solicitud</th>
                                    <th>Aseguradora</th>
                                    <th>Asesor</th>
                                    <th>Asegurado</th>
                                    <th>EPS del segurado</th>
                                    <th>Fecha diligenciamiento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($inabilities as $inability)
                                    <tr>
                                        <td>{{ $inability['no_solicitud'] }}</td>
                                        <td>{{ $inability['aseguradora'] }}</td>
                                        <td>{{ $inability['nombre_asesor'] }}</td>
                                        <td>{{ $inability['nombres_completos'] }}</td>
                                        <td>{{ $inability['tipo_identificaciòn'] }}</td>
                                        <td>{{ $inability['created_at']->format('Y-m-d - H:m') }}</td>
                                        <td>
                                            @if ($inability['status'] === 1)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($inability['status'] === 1)
                                                <div class="button-container">
                                                    <form action="{{ route('aseguradoras.destroy', $inability['id']) }}"
                                                        method="POST" id="formDelete-{{ $inability['id'] }}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                            data-id="{{ $inability['id'] }}" onclick="confirmDelete(this)"
                                                            title="Eliminar">
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
