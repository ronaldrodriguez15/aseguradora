@extends('adminlte::page')

@section('title', 'Salarios Minimos')

@section('content_header')
    <h1>Gestión de Salarios Minimos</h1>
@stop

@section('content')
    <p>En este modulo puedes ver todo el registro de los salarios que son utilizados al momento de realizar una afiliación
    </p>
    <br><br><br>

    <div class="row justify-content-center">
        <div class="col-md-12 text-right mb-5">
            <a class="btn btn-success" id="btnNuevaCiudad">
                <i class="fas fa-plus-circle mr-2"></i>Nuevo salario
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
                                    <th>Año</th>
                                    <th>Valor Salario</th>
                                    <th>creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($wages as $salary)
                                    <tr>
                                        <td>{{ $salary['año'] }}</td>
                                        <td>{{ '$ ' . $salary['valor'] }}</td>
                                        <td>{{ $salary['created_at']->format('Y-m-d - H:m') }}</td>
                                        <td>
                                            <div class="button-container">
                                                <form action="{{ route('ciudades.destroy', $salary['id']) }}" method="POST"
                                                    id="formDelete-{{ $salary['id'] }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        data-id="{{ $salary['id'] }}" onclick="confirmDelete(this)"
                                                        title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
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
    <div class="modal fade" id="modalNuevaCiudad" tabindex="-1" role="dialog" aria-labelledby="modalNuevaSedeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaCiudadLabel">Nuevo Salario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" action="{{ route('salarios.store') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="year">Año <span class="required">*</span></label>
                                <select class="form-control @error('year') is-invalid @enderror" id="year"
                                    name="year" required>
                                    <option value="">Selecciona el año</option>
                                    @for ($i = date('Y'); $i >= 1900; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="salary">Valor del salario <span class="required">*</span></label>
                                <input type="text" class="form-control @error('salary') is-invalid @enderror"
                                    id="salary" name="salary" placeholder="Introduce el valor del salario" required
                                    oninput="formatSalary(this)">
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button" class="btn btn-success">
                        <i class="fas fa-save mr-2"></i> Guardar Salario
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
    <script>
        function formatSalary(input) {
            let value = input.value.replace(/\D/g, '');

            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            input.value = value;
        }
    </script>
@stop
