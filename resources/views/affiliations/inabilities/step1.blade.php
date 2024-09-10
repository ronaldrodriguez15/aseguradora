@extends('adminlte::page')

@section('title', 'Proceso de afiliación')

@section('content_header')
    <h1>Proceso de afiliación</h1>
    <br>
@stop
@section('content')
    <div class="container">
        <form autocomplete="off" action="{{ route('usuarios.store') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header pastel-blue-color">
                    <div class="row">
                        <div class="col-md-6"><span>Datos generales de la afiliación</span></div>
                        <div class="col-md-6 text-right"><span>{{ now()->format('d/m/Y') }}</span><i
                                class="fas fa-calendar-week ml-2"></i></div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="no_solicitud">No de Solicitud <span class="required">*</span></label>
                            <input type="number" class="form-control @error('no_solicitud') is-invalid @enderror"
                                id="no_solicitud" name="no_solicitud" placeholder="Introduce el número de solicitud"
                                required>
                            @error('no_solicitud')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="aseguradora">Aseguradora <span class="required">*</span></label>
                            <select class="form-control @error('aseguradora') is-invalid @enderror" id="aseguradora"
                                name="aseguradora" required>
                                <option value="">Selecciona la aseguradora</option>
                                @foreach ($insurers as $insurer)
                                    <option value="{{ $insurer['id'] }}" data-poliza="{{ $insurer['no_poliza'] }}">
                                        {{ $insurer['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('aseguradora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="no_poliza">No de Poliza <span class="required">*</span></label>
                            <input type="text" class="form-control @error('no_poliza') is-invalid @enderror"
                                id="no_poliza" name="no_poliza" placeholder="Número de poliza" required disabled readonly>
                            @error('no_poliza')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="asesor_code">Código del asesor <span class="required">*</span></label>
                            <select class="form-control @error('asesor_code') is-invalid @enderror" id="asesor_code"
                                name="asesor_code" required>
                                <option value="">Selecciona el còdigo del asesor</option>
                                @foreach ($asesors as $asesor)
                                    <option value="{{ $asesor['asesor_code'] }}" data-name="{{ $asesor['name'] }}">
                                        {{ $asesor['asesor_code'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="nombre_asesor">Nombre del asesor <span class="required">*</span></label>
                            <input type="text" class="form-control @error('nombre_asesor') is-invalid @enderror"
                                id="nombre_asesor" name="nombre_asesor" placeholder="Nombre del asesor" required disabled
                                readonly>
                            @error('nombre_asesor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <br>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="phone">Código del asesor <span class="required">*</span></label>
                            <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" placeholder="Introduce el código" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="email">Nombre del asesor <span class="required">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Introduce el Nombre completo" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre_eps">Nombre EPS cliente <span class="required">*</span></label>
                            <input type="nombre_eps" class="form-control @error('nombre_eps') is-invalid @enderror"
                                id="nombre_eps" name="nombre_eps" placeholder="Escribe el nombre de la EPS" required>
                            @error('nombre_eps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha_nacimiento_asesor">Fecha de nacimiento <span class="required">*</span></label>
                            <input type="date"
                                class="form-control @error('fecha_nacimiento_asesor') is-invalid @enderror"
                                id="fecha_nacimiento_asesor" name="fecha_nacimiento_asesor"
                                placeholder="Selecciona la fecha del cliente" required>
                            @error('fecha_nacimiento_asesor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email_corporativo">Correo electronico corporativo <span
                                    class="required">*</span></label>
                            <input type="email" class="form-control @error('email_corporativo') is-invalid @enderror"
                                id="email_corporativo" name="email_corporativo" placeholder="alguien@example.com"
                                required>
                            @error('email_corporativo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">Descuento EPS <span class="required">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="text" class="form-control" name="descuento_eps" id="descuento_eps"
                                    required>
                            </div>

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="numero_dias">Nùmero de dias <span class="required">*</span></label>
                            <input type="number" class="form-control @error('numero_dias') is-invalid @enderror"
                                id="numero_dias" name="numero_dias" placeholder="escribe el número de dias" required>
                            @error('numero_dias')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="pierdes">Tu pierdes</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text is-invalid">$</span>
                                </div>
                                <input type="text" class="form-control is-invalid" readonly disabled>
                            </div>
                            @error('descuento_eps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-2">
                            <label for="edad" class="text-success">Edad</label>
                            <input type="number" class="form-control is-valid" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('incapacidades.index') }}" class="btn btn-info">
                        <i class="fas fa-arrow-left mr-2"></i> Regresar
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-success">
                        Siguiente<i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tu archivo de script personalizado -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('input').attr('autocomplete', 'off');
        });
    </script>
    <script src="{{ asset('js/step1.js') }}"></script>
@stop
