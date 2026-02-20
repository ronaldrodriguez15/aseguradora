@extends('adminlte::page')

@section('title', 'Horario')

@section('content_header')
    <h1>Ambiente</h1>
@stop

@section('content')
    <p>En este módulo puedes cambiar el horario para el ingreso del sistema, asi como activar los dias festivos y
        seleccionar el rango de dias.</p>
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
            <form action="{{ route('horario.store') }}" method="POST" class="form-row align-items-center" id="horarioForm">
                @csrf

                {{-- Día inicial --}}
                <div class="form-group col-md-3">
                    <label for="dia1">Día inicial <span class="required">*</span></label>
                    <select class="form-control @error('dia1') is-invalid @enderror" id="dia1"
                        name="dia1" required>
                        <option value="">Seleccione...</option>
                        @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                            <option value="{{ $dia }}"
                                {{ old('dia1', $schedule->dia1 ?? 'Lunes') == $dia ? 'selected' : '' }}>
                                {{ $dia }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Día final --}}
                <div class="form-group col-md-3">
                    <label for="dia2">Día final <span class="required">*</span></label>
                    <select class="form-control @error('dia2') is-invalid @enderror" id="dia2" name="dia2"
                        required>
                        <option value="">Seleccione...</option>
                        @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                            <option value="{{ $dia }}"
                                {{ old('dia2', $schedule->dia2 ?? 'Viernes') == $dia ? 'selected' : '' }}>
                                {{ $dia }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hora inicial --}}
                <div class="form-group col-md-2">
                    <label for="hora_inicio">Hora inicial <span class="required">*</span></label>
                    <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror" id="hora_inicio"
                        name="hora_inicio" value="{{ old('hora_inicio', $schedule->hora_inicio ?? '07:00') }}" required>
                </div>

                {{-- Hora final --}}
                <div class="form-group col-md-2">
                    <label for="hora_final">Hora final <span class="required">*</span></label>
                    <input type="time" class="form-control @error('hora_final') is-invalid @enderror" id="hora_final"
                        name="hora_final" value="{{ old('hora_final', $schedule->hora_final ?? '17:00') }}" required>
                </div>

                {{-- Habilitar festivos --}}
                <div class="form-group col-md-2 text-center">
                    <label for="festivos" class="d-block">Habilitar festivos</label>
                    <input type="checkbox" id="festivos" name="festivos" value="1"
                        {{ old('festivos', $schedule->festivos ?? 1) ? 'checked' : '' }}>
                </div>

                {{-- Botón Guardar --}}
                <div class="form-group col-md-12 mt-3">
                    <button type="submit" class="btn btn-success">
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
