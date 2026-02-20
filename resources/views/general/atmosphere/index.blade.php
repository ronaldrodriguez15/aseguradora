@extends('adminlte::page')

@section('title', 'Ambiente')

@section('content_header')
    <h1>Ambiente</h1>
@stop

@section('content')
    <p>En este modulo puedes cambiar el ambiente de las APIs integradas en el sistema.</p>
    <br><br><br>

    <div class="row justify-content-center">
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
            <form action="{{ route('ambiente.store') }}" method="POST" class="form-row align-items-center"
                id="ambienteForm">
                @csrf
                <div class="form-group col-md-4">
                    <label for="ambiente">Ambiente Via Firma <span class="required">*</span></label>
                    <select class="form-control @error('ambiente') is-invalid @enderror" id="ambiente" name="ambiente">
                        <option value="development"
                            {{ isset($atmosphere) && $atmosphere->name === 'development' ? 'selected' : '' }}>Sandbox
                        </option>
                        <option value="production"
                            {{ isset($atmosphere) && $atmosphere->name === 'production' ? 'selected' : '' }}>Producción
                        </option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-success mt-4" id="submitButton">
                        <i class="fas fa-save mr-2"></i>Guardar
                    </button>
                </div>
            </form>
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
    <script>
        document.getElementById('submitButton').addEventListener('click', function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Ten en cuenta que este cambio es bastante importante para las firmas!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar cambios',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, envía el formulario
                    document.getElementById('ambienteForm').submit();
                }
            });
        });
    </script>
@stop
