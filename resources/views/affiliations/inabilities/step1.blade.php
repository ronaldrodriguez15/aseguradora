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
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger rounded" role="progressbar"
            aria-valuenow="16.6" aria-valuemin="0" aria-valuemax="100" style="width: 16.6%; height: 100%;"></div>
        <div class="progress-text"
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #000000; font-weight: bold; font-size: 18px;">
            Paso 1 de 6
        </div>
    </div>
    <br>
    <div class="mt-2 mb-2">
        <!-- Controller response -->
        @if (session()->get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if (session()->get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session()->get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    </div>
    <form autocomplete="off" action="{{ route('incapacidades.formStepTwo') }}" method="post" id="formStep1">
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
                    <div class="form-group col-md-6">
                        <label for="aseguradora">Aseguradora <span class="required">*</span></label>
                        <select class="form-control @error('aseguradora') is-invalid @enderror" id="aseguradora"
                            name="aseguradora" required>
                            <option value="">Selecciona la aseguradora</option>
                            @foreach ($insurers as $insurer)
                            <option value="{{ $insurer['id'] }}" data-poliza="{{ $insurer['no_poliza'] }}"
                                data-identificador="{{ $insurer['identificador'] }}">
                                {{ $insurer['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('aseguradora')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="identificador">Consecutivo <span class="required">*</span></label>
                        <input type="text" class="form-control @error('identificador') is-invalid @enderror"
                            id="identificador" name="identificador" placeholder="00000" required readonly>
                        @error('identificador')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="no_poliza">No de Poliza <span class="required">*</span></label>
                        <input type="text" class="form-control @error('no_poliza') is-invalid @enderror"
                            id="no_poliza" name="no_poliza" placeholder="00000" required readonly>
                        @error('no_poliza')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="asesor_code">Código del asesor <span class="required">*</span></label>
                        <select class="form-control @error('codigo_asesor') is-invalid @enderror" id="asesor_code"
                            name="codigo_asesor" required>
                            <option value="">Selecciona el código del asesor</option>
                            @foreach ($asesors as $asesor)
                            <option value="{{ $asesor['asesor_code'] }}" data-name="{{ $asesor['name'] }}">
                                {{ $asesor['asesor_code'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('codigo_asesor')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-8">
                        <label for="nombre_asesor">Nombre del asesor <span class="required">*</span></label>
                        <input type="text" class="form-control @error('nombre_asesor') is-invalid @enderror"
                            id="nombre_asesor" name="nombre_asesor" placeholder="Nombre del asesor" required readonly>
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

                    <div class="form-group col-md-6">
                        <label for="nombre_eps">EPS Cliente <span class="required">*</span></label>
                        <select class="form-control @error('aseguradora') is-invalid @enderror" id="nombre_eps"
                            name="nombre_eps" required>
                            <option value="">Selecciona la EPS</option>
                            @foreach ($epss as $eps)
                            <option value="{{ $eps['name'] }}">
                                {{ $eps['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('nombre_eps')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fecha_nacimiento_asesor">Fecha de nacimiento <span
                                class="required">*</span></label>
                        <input type="date"
                            class="form-control @error('fecha_nacimiento_asesor') is-invalid @enderror"
                            id="fecha_nacimiento_asesor" max="2005-12-31" name="fecha_nacimiento_asesor"
                            placeholder="Selecciona la fecha del cliente" required>
                        @error('fecha_nacimiento_asesor')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-2">
                        <label for="edad">Edad</label>
                        <input type="number" id="edad" name="edad" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email_corporativo">Correo electrónico <span class="required">*</span></label>
                        <input type="email" class="form-control" id="email_corporativo" name="email_corporativo"
                            placeholder="alguien@example.com" required>
                        <div class="invalid-feedback">Los correos electrónicos no coinciden.</div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email_confirmacion">Confirmación del correo electrónico <span
                                class="required">*</span></label>
                        <input type="email" class="form-control" id="email_confirmacion" name="email_confirmacion"
                            placeholder="alguien@example.com" required>
                        <div class="invalid-feedback">Los correos electrónicos no coinciden.</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="numero_dias">Número de días <span class="required">*</span></label>
                        <input type="number" class="form-control @error('numero_dias') is-invalid @enderror"
                            id="numero_dias" name="numero_dias" placeholder="No de días" value="3" required>
                        @error('numero_dias')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="desea_valor">¿Desea valor adicional? <span class="required">*</span></label>
                        <select class="form-control @error('desea_valor') is-invalid @enderror" id="desea_valor"
                            name="desea_valor" required>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                        @error('desea_valor')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="valor_ibc_basico">Valor IBC Básico <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="valor_ibc_basico" id="valor_ibc_basico"
                                placeholder="sin comas ni puntos" required>
                        </div>
                        @error('valor_ibc_basico')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="descuento_eps">Descuento EPS <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="descuento_eps" id="descuento_eps"
                                placeholder="calculo automático" required readonly>
                        </div>
                        @error('descuento_eps')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="valor_adicional">Valor adicional <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="valor_adicional" id="valor_adicional"
                                placeholder="sin comas ni puntos" readonly required>
                        </div>
                        @error('valor_adicional')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tu_pierdes" class="text-danger">TU PIERDES!!!</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control border-danger" name="tu_pierdes"
                                id="tu_pierdes" placeholder="Calculo automatico" readonly>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="te_pagamos" class="text-success">NOSOTROS TE PAGAMOS!!!</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control border-success" name="te_pagamos"
                                id="te_pagamos" placeholder="Calculo automatico" required readonly>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="total">Total <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="total" id="total"
                                placeholder="sin comas ni puntos" readonly>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="amparo_basico">Amparo basico (muerte por cualquier causa) <span
                                class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="amparo_basico" id="amparo_basico"
                                value="1.000.000,00" placeholder="sin comas ni puntos" required readonly>
                        </div>
                        @error('amparo_basico')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="val_prevexequial_eclusivo">Valor previ-exequial exclusivo <span
                                class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="val_prevexequial_eclusivo"
                                id="val_prevexequial_eclusivo" value="2400" placeholder="sin comas ni puntos"
                                required readonly>
                        </div>
                        @error('val_prevexequial_eclusivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="prima_pago_prima_seguro">Prima de pago Prima de seguro <span
                                class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="prima_pago_prima_seguro"
                                id="prima_pago_prima_seguro" placeholder="sin comas ni puntos" required readonly>
                        </div>
                        @error('prima_pago_prima_seguro')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="forma_pago">Forma de pago <span class="required">*</span></label>
                        <select class="form-control @error('forma_pago') is-invalid @enderror" id="forma_pago" name="forma_pago" required>
                            <option value="">Selecciona la forma de pago</option>
                            <option value="debito_automatico">Debito automático</option>
                            <option value="mensual_libranza">Mensual Libranza</option>
                        </select>
                        @error('forma_pago')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="gastos_administrativos">Gastos administrativos <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control" name="gastos_administrativos" id="gastos_administrativos" value="0" placeholder="00000" required readonly>
                        </div>
                        @error('gastos_administrativos')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="val_total_desc_mensual" class="text-warning">Valor total descuento mensual
                            <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control border-warning-custom"
                                name="val_total_desc_mensual" id="val_total_desc_mensual" placeholder="00000"
                                required readonly>
                        </div>
                        @error('val_total_desc_mensual')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card" id="debito_automatico_fields" style="display: none">
            <div class="card-body">
                <br>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tipo_cuenta">Tipo de cuenta <span class="required">*</span></label>
                        <select class="form-control @error('tipo_cuenta') is-invalid @enderror" id="tipo_cuenta"
                            name="tipo_cuenta">
                            <option value="ahorros">Ahorros</option>
                            <option value="corriente">Corriente</option>
                        </select>
                        @error('tipo_cuenta')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="no_cuenta">Número de cuenta <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="no_cuenta" id="no_cuenta"
                                placeholder="Escribe el N de cuenta">
                        </div>
                        <div class="invalid-feedback">Los números de cuenta no coinciden.</div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="r_no_cuenta">Repite el número de cuenta <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="r_no_cuenta" id="r_no_cuenta"
                                placeholder="Repite el N de cuenta" onpaste="return false">
                        </div>
                        <div class="invalid-feedback">Los números de cuenta no coinciden.</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="banco">Banco <span class="required">*</span></label>
                        <select class="form-control @error('banco') is-invalid @enderror" id="banco"
                            name="banco">
                            <option value="">Selecciona el banco</option>
                            @foreach ($banks as $bank)
                            <option value="{{ $bank['id'] }}">
                                {{ $bank['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('banco')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ciudad_banco">Ciudad del Banco <span class="required">*</span></label>
                        <select class="form-control @error('bank_id') is-invalid @enderror" id="ciudad_banco"
                            name="ciudad_banco">
                            <option value="">Selecciona el banco</option>
                            @foreach ($cities as $city)
                            <option value="{{ $city['name'] }}">
                                {{ $city['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('ciudad_banco')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 mb-5">
                <a href="{{ route('incapacidades.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left mr-2"></i> Regresar
                </a>
            </div>
            <div class="col-md-6 text-right mb-5">
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
<script src="{{ asset('js/step11.js') }}"></script>
<script src="{{ asset('js/step12.js') }}"></script>
<script src="{{ asset('js/step13.js') }}"></script>
<script src="{{ asset('js/step14.js') }}"></script>
@stop
