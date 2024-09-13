@extends('adminlte::page')

@section('title', 'Proceso de afiliación')

@section('content_header')
<h1>Proceso de afiliación</h1>
<br>
@stop
@section('content')
<div class="container">
    <form autocomplete="off" action="{{ route('incapacidades.formStepTree') }}" method="post" id="formStep2">
        @csrf
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="row">
                    <div class="col-md-6"><span>Datos del asegurado</span></div>
                    <div class="col-md-6 text-right"><span>{{ now()->format('d/m/Y') }}</span><i
                            class="fas fa-calendar-week ml-2"></i></div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="tu_pierdes" class="text-danger">Tu pierdes!!!</label>
                        <input type="text" class="form-control form-control-lg is-invalid"
                            id="tu_pierdes" name="tu_pierdes" placeholder="000000" value="{{ $tu_pierdes }}" disabled required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="te_pagamos" class="text-info">Nosotros te pagamos!!!</label>
                        <input type="text" class="form-control form-control-lg border-info"
                            id="te_pagamos" name="te_pagamos" placeholder="000000" value="{{ $te_pagamos }}" disabled required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="val_total_desc_mensual" class="text-warning">Valor total de descuento mensual</label>
                        <input type="text" class="form-control form-control-lg is-warning"
                            id="val_total_desc_mensual" name="val_total_desc_mensual" placeholder="000000" value="{{ $val_total_desc_mensual }}" disabled required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="edad" class="text-success">Edad del funcionario</label>
                        <input type="text" class="form-control form-control-lg is-valid"
                            id="edad" name="edad" placeholder="000000" value="{{ $edad }}" disabled required>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card" id="debito_automatico_fields">
            <div class="card-body">
                <br>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="primer_apellido">Primer apellido <span class="required">*</span></label>
                        <input type="text" class="form-control @error('primer_apellido') is-invalid @enderror"
                            id="primer_apellido" name="primer_apellido" placeholder="Introduce el primer apellido" required>
                        @error('primer_apellido')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="segundo_apellido">Segundo apellido <span class="required">*</span></label>
                        <input type="text" class="form-control @error('segundo_apellido') is-invalid @enderror"
                            id="segundo_apellido" name="segundo_apellido" placeholder="Introduce el segundo apellido" required>
                        @error('segundo_apellido')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombres_completos">Nombres completos <span class="required">*</span></label>
                        <input type="text" class="form-control @error('nombres_completos') is-invalid @enderror"
                            id="nombres_completos" name="nombres_completos" placeholder="Introduce tus nombres" required>
                        @error('nombres_completos')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tipo_identificacion">Tipo de identifiación <span class="required">*</span></label>
                        <select class="form-control @error('tipo_identificacion') is-invalid @enderror" id="tipo_identificacion"
                            name="tipo_identificacion" required>
                            <option value="">Selecciona el tipo</option>
                            <option value="cedula_ciudadania">Cédula de ciudadanía</option>
                            <option value="cedula_extranjeria">Cédula de extranjería</option>
                            <option value="pasaporte">Pasaporte</option>
                        </select>
                        @error('tipo_identificacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="no_identificacion">No identificación <span class="required">*</span></label>
                        <input type="number" class="form-control @error('no_identificacion') is-invalid @enderror"
                            id="no_identificacion" name="no_identificacion" placeholder="sin comas ni puntos" required>
                        @error('no_identificacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ciudad_expedicion">Ciudad de expedición <span class="required">*</span></label>
                        <select class="form-control @error('ciudad_expedicion') is-invalid @enderror" id="ciudad_expedicion"
                            name="ciudad_expedicion" required>
                            <option value="">Selecciona la ciudad</option>
                            @foreach ($cities as $city)
                            <option value="{{ $city['name'] }}">
                                {{ $city['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('ciudad_expedicion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="genero">Género <span class="required">*</span></label>
                        <select class="form-control @error('genero') is-invalid @enderror" id="genero"
                            name="genero" required>
                            <option value="">Selecciona el género</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="otro">Otro</option>
                        </select>
                        @error('genero')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fecha_nacimiento_asegurado">Fecha de nacimiento del asegurado <span class="required">*</span></label>
                        <input type="date"
                            class="form-control @error('fecha_nacimiento_asegurado') is-invalid @enderror"
                            id="fecha_nacimiento_asegurado" name="fecha_nacimiento_asegurado"
                            placeholder="Selecciona la fecha del asegurado" required>
                        @error('fecha_nacimiento_asegurado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="direccion_residencia">Dirección de residencia <span class="required">*</span></label>
                        <input type="text" class="form-control @error('direccion_residencia') is-invalid @enderror"
                            id="direccion_residencia" name="direccion_residencia" placeholder="Introduce la dirección de residencia" required>
                        @error('direccion_residencia')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="telefono_fijo">Teléfono fijo</label>
                        <input type="number" class="form-control @error('telefono_fijo') is-invalid @enderror"
                            id="telefono_fijo" name="telefono_fijo" placeholder="sin comas ni puntos">
                        @error('telefono_fijo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="celular">Teléfono celular</label>
                        <input type="number" class="form-control @error('celular') is-invalid @enderror"
                            id="celular" name="celular" placeholder="sin comas ni puntos">
                        @error('celular')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ciudad_residencia">Ciudad de residencia <span class="required">*</span></label>
                        <select class="form-control @error('ciudad_residencia') is-invalid @enderror" id="ciudad_residencia"
                            name="ciudad_residencia" required>
                            <option value="">Selecciona la ciudad</option>
                            @foreach ($cities as $city)
                            <option value="{{ $city['name'] }}">
                                {{ $city['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('ciudad_residencia')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fuente_recursos">Fuente de recursos <span class="required">*</span></label>
                        <input type="text" class="form-control @error('fuente_recursos') is-invalid @enderror"
                            id="fuente_recursos" name="fuente_recursos" value="SALARIO" placeholder="Introduce la fuente de recursos" required>
                        @error('fuente_recursos')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ocupacion_asegurado">Ocupación <span class="required">*</span></label>
                        <input type="text" class="form-control @error('ocupacion_asegurado') is-invalid @enderror"
                            id="ocupacion_asegurado" name="ocupacion_asegurado" placeholder="Introduce la ocupación" required>
                        @error('ocupacion_asegurado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="entidad_pagadora_sucursal">Entidad pagadora / sucursal <span class="required">*</span></label>
                        <select class="form-control @error('entidad_pagadora_sucursal') is-invalid @enderror" id="entidad_pagadora_sucursal"
                            name="entidad_pagadora_sucursal" required>
                            <option value="">Selecciona la entidad</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company['name'] }}">
                                {{ $company['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('company')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="eps_asegurado">EPS <span class="required">*</span></label>
                        <select class="form-control @error('eps_asegurado') is-invalid @enderror" id="eps_asegurado"
                            name="eps_asegurado" required>
                            <option value="">Selecciona la eps</option>
                            @foreach ($epss as $eps)
                            <option value="{{ $eps['name'] }}">
                                {{ $eps['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('eps_asegurado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="col-md-6"><span>Tercer beneficiario del solicitante (OPCIONAL)</span></div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nombres_s1">Nombres</label>
                        <input type="text" class="form-control"
                            id="nombres_s1" name="nombres_s1" placeholder="Nombres completos">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="apellidos_s1">Apellidos</label>
                        <input type="text" class="form-control"
                            id="apellidos_s1" name="apellidos_s1" placeholder="Apellidos completos">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="genero_s1">Género <span class="required">*</span></label>
                        <select class="form-control @error('genero_s1') is-invalid @enderror" id="genero_s1"
                            name="genero_s1">
                            <option value="">Selecciona el género</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="otro">Otro</option>
                        </select>
                        @error('genero_s1')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="parentesco_s1">Parentesco</label>
                        <input type="text" class="form-control"
                            id="parentesco_s1" name="parentesco_s1" placeholder="Introduce el parentesco">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="edad_s1">Edad</label>
                        <input type="text" class="form-control"
                            id="edad_s1" name="edad_s1" placeholder="Introduce la edad">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="col-md-6"><span>Tercer beneficiario del solicitante (OPCIONAL)</span></div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nombres_s2">Nombres</label>
                        <input type="text" class="form-control"
                            id="nombres_s2" name="nombres_s2" placeholder="Nombres completos">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="apellidos_s2">Apellidos</label>
                        <input type="text" class="form-control"
                            id="apellidos_s2" name="apellidos_s2" placeholder="Apellidos completos">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="genero_s2">Género <span class="required">*</span></label>
                        <select class="form-control @error('genero_s2') is-invalid @enderror" id="genero_s2"
                            name="genero_s2">
                            <option value="">Selecciona el género</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="parentesco_s1">Parentesco</label>
                        <input type="text" class="form-control"
                            id="parentesco_s1" name="parentesco_s1" placeholder="Introduce el parentesco">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="edad_s2">Edad</label>
                        <input type="text" class="form-control"
                            id="edad_s2" name="edad_s2" placeholder="Introduce la edad">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="col-md-6"><span>Tercer beneficiario del solicitante (OPCIONAL)</span></div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nombres_s3">Nombres</label>
                        <input type="text" class="form-control"
                            id="nombres_s3" name="nombres_s3" placeholder="Nombres completos">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="apellidos_s3">Apellidos</label>
                        <input type="text" class="form-control"
                            id="apellidos_s3" name="apellidos_s3" placeholder="Apellidos completos">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="genero_s3">Género <span class="required">*</span></label>
                        <select class="form-control @error('genero_s3') is-invalid @enderror" id="genero_s3"
                            name="genero_s3">
                            <option value="">Selecciona el género</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="parentesco_s1">Parentesco</label>
                        <input type="text" class="form-control"
                            id="parentesco_s1" name="parentesco_s1" placeholder="Introduce el parentesco">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="edad_s3">Edad</label>
                        <input type="text" class="form-control"
                            id="edad_s3" name="edad_s3" placeholder="Introduce la edad">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 text-right mb-5">
            </div>
            <div class="col-md-6 text-right mb-5">
                <button type="submit" class="btn btn-success" id="submitBtn">
                    Siguiente<i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
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
<script src="{{ asset('js/step3.js') }}"></script>
@stop
