@extends('adminlte::page')

@section('title', 'Proceso de afiliación')

@section('content_header')
<h1>Proceso de afiliación</h1>
<br>
@stop
@section('content')
<div class="container">
    <!-- Línea de progreso -->
    <div class="progress" style="position: relative; width: 100%; height: 30px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success rounded" role="progressbar" aria-valuenow="83.6" aria-valuemin="0" aria-valuemax="100" style="width: 83.6%; height: 100%;"></div>
        <div class="progress-text" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #000000; font-weight: bold; font-size: 18px;">
            Paso 5 de 6
        </div>
    </div>
    <br>
    <div class="mt-2 mb-2">
        <!-- Controller response -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('incapacidades.formStepSix') }}" method="post" id="formStep5">
        @csrf
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="row">
                    <div class="col-md-6"><span>Referencias laborales y afiliación mascotas</span></div>
                    <div class="col-md-6 text-right"><span>{{ now()->format('d/m/Y') }}</span><i
                            class="fas fa-calendar-week ml-2"></i></div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="tu_pierdes" class="text-danger">Tu pierdes!!!</label>
                        <input type="text" class="form-control form-control-lg is-invalid" id="tu_pierdes"
                            name="tu_pierdes" placeholder="000000" value="{{ $tu_pierdes }}" disabled required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="te_pagamos" class="text-info">Nosotros te pagamos!!!</label>
                        <input type="text" class="form-control form-control-lg border-info" id="te_pagamos"
                            name="te_pagamos" placeholder="000000" value="{{ $te_pagamos }}" disabled required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="val_total_desc_mensual" class="text-warning">Valor total de descuento
                            mensual</label>
                        <input type="text" class="form-control form-control-lg is-warning"
                            id="val_total_desc_mensual" name="val_total_desc_mensual" placeholder="000000"
                            value="{{ $val_total_desc_mensual }}" disabled required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="edad" class="text-success">Edad del funcionario</label>
                        <input type="text" class="form-control form-control-lg is-valid" id="edad"
                            name="edad" placeholder="000000" value="{{ $edad }}" disabled required>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="col-md-6"><span>Primer referencia laboral</span></div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombres_apellidos_r1">Nombres y apellidos <span class="required">*</span></label>
                        <input type="text" class="form-control" id="nombres_apellidos_r1" name="nombres_apellidos_r1" placeholder="Nombres y apellidos completos" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono_r1">Teléfono de contacto <span class="required">*</span></label>
                        <input type="number" class="form-control" id="telefono_r1" name="telefono_r1" placeholder="Digita el No de teléfono" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4" id="entidad-group-r1">
                        <label for="entidad_r1">Entidad pagadora / sucursal <span class="required">*</span></label>
                        <select class="form-control @error('entidad_r1') is-invalid @enderror" id="entidad_r1" name="entidad_r1">
                            <option value="">Selecciona la entidad</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company['name'] }}">{{ $company['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="seleccion_opcion_r1">¿Otro?</label>
                        <select class="form-control" id="seleccion_opcion_r1" name="seleccion_opcion_r1">
                            <option value="no" selected>No</option>
                            <option value="si">Sí</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" id="cual-group-r1" style="display: none;">
                        <label for="cual_r1">¿Cuál? <span class="required">*</span></label>
                        <input type="text" class="form-control" id="cual_r1" name="cual_r1" placeholder="Escribe la entidad">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="col-md-6"><span>Segunda referencia laboral</span></div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombres_apellidos_r2">Nombres y apellidos <span class="required">*</span></label>
                        <input type="text" class="form-control" id="nombres_apellidos_r2" name="nombres_apellidos_r2" placeholder="Nombres y apellidos completos" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono_r2">Teléfono de contacto <span class="required">*</span></label>
                        <input type="number" class="form-control" id="telefono_r2" name="telefono_r2" placeholder="Digita el No de teléfono" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4" id="entidad-group-r2">
                        <label for="entidad_r2">Entidad pagadora / sucursal <span class="required">*</span></label>
                        <select class="form-control @error('entidad_r2') is-invalid @enderror" id="entidad_r2" name="entidad_r2">
                            <option value="">Selecciona la entidad</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company['name'] }}">{{ $company['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="seleccion_opcion_r2">¿Otro?</label>
                        <select class="form-control" id="seleccion_opcion_r2" name="seleccion_opcion_r2" >
                            <option value="no" selected>No</option>
                            <option value="si">Sí</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" id="cual-group-r2" style="display: none;">
                        <label for="cual_r2">¿Cuál? <span class="required">*</span></label>
                        <input type="text" class="form-control" id="cual_r2" name="cual_r2" placeholder="Escribe la entidad">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="col-md-6"><span>Tercer referencia laboral</span></div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombres_apellidos_r3">Nombres y apellidos <span class="required">*</span></label>
                        <input type="text" class="form-control" id="nombres_apellidos_r3" name="nombres_apellidos_r3" placeholder="Nombres y apellidos completos" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono_r3">Teléfono de contacto <span class="required">*</span></label>
                        <input type="number" class="form-control" id="telefono_r3" name="telefono_r3" placeholder="Digita el No de teléfono" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4" id="entidad-group-r3">
                        <label for="entidad_r3">Entidad pagadora / sucursal <span class="required">*</span></label>
                        <select class="form-control @error('entidad_r3') is-invalid @enderror" id="entidad_r3" name="entidad_r3">
                            <option value="">Selecciona la entidad</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company['name'] }}">{{ $company['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="seleccion_opcion_r3">¿Otro?</label>
                        <select class="form-control" id="seleccion_opcion_r3" name="seleccion_opcion_r3" >
                            <option value="no" selected>No</option>
                            <option value="si">Sí</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" id="cual-group-r3" style="display: none;">
                        <label for="cual_r3">¿Cuál? <span class="required">*</span></label>
                        <input type="text" class="form-control" id="cual_r3" name="cual_r3" placeholder="Escribe la entidad">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="col-md-6"><span>Afiliación de mascotas</span></div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tienes_mascotas">¿Tienes mascotas? <span class="required">*</span></label>
                        <select class="form-control @error('tienes_mascotas') is-invalid @enderror" id="tienes_mascotas" name="tienes_mascotas" required>
                            <option value="">Selecciona</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                        @error('tienes_mascotas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6" id="proteger_mascotas_container" style="display: none;">
                        <label for="proteger_mascotas">¿Proteger mascotas? <span class="required">*</span></label>
                        <select class="form-control @error('proteger_mascotas') is-invalid @enderror" id="proteger_mascotas" name="proteger_mascotas">
                            <option value="">Selecciona</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                        @error('proteger_mascotas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <br><br>
                <div id="mascotas" style="display: none;">
                    <!-- Contenido de las mascotas -->
                    <h4>Mascota 1</h4>
                    <br>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nombre_m1">Nombres <span class="required">*</span></label>
                            <input type="text" class="form-control" id="nombre_m1" name="nombre_m1" placeholder="Nombre de tu mascota" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tipo_m1">Tipo de mascota <span class="required">*</span></label>
                            <select class="form-control @error('tipo_m1') is-invalid @enderror" id="tipo_m1" name="tipo_m1" required>
                                <option value="">Selecciona</option>
                                <option value="gato">Gato</option>
                                <option value="perro">Perro</option>
                                <option value="conejo">Conejo</option>
                                <option value="hamster">Hamster</option>
                            </select>
                            @error('tipo_m1')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="raza_m1">Raza</label>
                            <input type="text" class="form-control" id="raza_m1" name="raza_m1" placeholder="Coloca la entidad" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="color_m1">Color</label>
                            <input type="text" class="form-control" id="color_m1" name="color_m1" placeholder="Escribe el color de tu mascota" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="genero_m1">Género</label>
                            <select class="form-control @error('genero_m1') is-invalid @enderror" id="genero_m1" name="genero_m1" required>
                                <option value="">Selecciona</option>
                                <option value="hembra">Hembra</option>
                                <option value="macho">Macho</option>
                            </select>
                            @error('genero_m1')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="edad_m1">Edad</label>
                            <input type="number" class="form-control" id="edad_m1" name="edad_m1" placeholder="Coloca la edad, solo números" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="valor_prima_m1">Valor de prima <span class="required">*</span></label>
                            <input type="number" class="form-control" id="valor_prima_m1" name="valor_prima_m1" placeholder="Digita el valor" required>
                        </div>
                    </div>
                    <br><br>
                    <h4>Mascota 2</h4>
                    <br>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nombre_m2">Nombres</label>
                            <input type="text" class="form-control" id="nombre_m2" name="nombre_m2" placeholder="Nombre de tu mascota">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tipo_m2">Tipo de mascota</label>
                            <select class="form-control @error('tipo_m2') is-invalid @enderror" id="tipo_m2" name="tipo_m2">
                                <option value="">Selecciona</option>
                                <option value="gato">Gato</option>
                                <option value="perro">Perro</option>
                                <option value="conejo">Conejo</option>
                                <option value="hamster">Hamster</option>
                            </select>
                            @error('tipo_m2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="raza_m2">Raza</label>
                            <input type="text" class="form-control" id="raza_m2" name="raza_m2" placeholder="Coloca la entidad">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="color_m2">Color</label>
                            <input type="text" class="form-control" id="color_m2" name="color_m2" placeholder="Escribe el color de tu mascota">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="genero_m2">Género</label>
                            <select class="form-control @error('genero_m2') is-invalid @enderror" id="genero_m2" name="genero_m2">
                                <option value="">Selecciona</option>
                                <option value="hembra">Hembra</option>
                                <option value="macho">Macho</option>
                            </select>
                            @error('genero_m2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="edad_m2">Edad</label>
                            <input type="number" class="form-control" id="edad_m2" name="edad_m2" placeholder="Coloca la edad, solo números" >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="valor_prima_m2">Valor de prima</label>
                            <input type="number" class="form-control" id="valor_prima_m2" name="valor_prima_m2" placeholder="Digita el valor" >
                        </div>
                    </div>
                    <br><br>
                    <h4>Mascota 3</h4>
                    <br>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nombre_m3">Nombres</label>
                            <input type="text" class="form-control" id="nombre_m3" name="nombre_m3" placeholder="Nombre de tu mascota">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tipo_m3">Tipo de mascota</label>
                            <select class="form-control @error('tipo_m3') is-invalid @enderror" id="tipo_m3" name="tipo_m3">
                                <option value="">Selecciona</option>
                                <option value="gato">Gato</option>
                                <option value="perro">Perro</option>
                                <option value="conejo">Conejo</option>
                                <option value="hamster">Hamster</option>
                            </select>
                            @error('tipo_m3')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="raza_m3">Raza</label>
                            <input type="text" class="form-control" id="raza_m3" name="raza_m3" placeholder="Coloca la entidad" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="color_m3">Color</label>
                            <input type="text" class="form-control" id="color_m3" name="color_m3" placeholder="Escribe el color de tu mascota" >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="genero_m3">Género</label>
                            <select class="form-control @error('genero_m3') is-invalid @enderror" id="genero_m3" name="genero_m3" >
                                <option value="">Selecciona</option>
                                <option value="hembra">Hembra</option>
                                <option value="macho">Macho</option>
                            </select>
                            @error('genero_m3')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="edad_m3">Edad</label>
                            <input type="number" class="form-control" id="edad_m3" name="edad_m3" placeholder="Coloca la edad, solo números" >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="valor_prima_m3">Valor de prima</label>
                            <input type="number" class="form-control" id="valor_prima_m3" name="valor_prima_m3" placeholder="Digita el valor"
                            >
                        </div>
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
<script src="{{ asset('js/step6.js') }}"></script>
@stop
