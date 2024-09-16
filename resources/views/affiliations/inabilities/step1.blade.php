@extends('adminlte::page')

@section('title', 'Proceso de afiliación')

@section('content_header')
<h1>Proceso de afiliación</h1>
<br>
@stop
@section('content')
<div class="container">
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
                    <div class="form-group col-md-3">
                        <label for="no_solicitud">No de Solicitud <span class="required">*</span></label>
                        <input type="number" class="form-control @error('no_solicitud') is-invalid @enderror"
                            id="no_solicitud" name="no_solicitud" placeholder="000000" required>
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
                        <label for="fecha_nacimiento_asesor">Fecha de nacimiento <span class="required">*</span></label>
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
                        <label for="email_corporativo">Correo electronico <span
                                class="required">*</span></label>
                        <input type="email" class="form-control @error('email_corporativo') is-invalid @enderror"
                            id="email_corporativo" name="email_corporativo" placeholder="alguien@example.com" required>
                        @error('email_corporativo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email_corporativo">Confirmación del correo electronico <span
                                class="required">*</span></label>
                        <input type="email" class="form-control @error('email_corporativo') is-invalid @enderror"
                            id="email_corporativo" name="email_corporativo" placeholder="alguien@example.com" required>
                        @error('email_corporativo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="numero_dias">Número de días <span class="required">*</span></label>
                        <input type="number" class="form-control @error('numero_dias') is-invalid @enderror" id="numero_dias" name="numero_dias" placeholder="No de días" required>
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
                    <div class="form-group col-md-6">
                        <label for="valor_adicional">Valor adicional <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="valor_adicional" id="valor_adicional" placeholder="sin comas ni puntos" readonly required>
                        </div>
                        @error('valor_adicional')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3 d-flex justify-content-end align-items-end mb-4">
                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" id="botonTePagamos" data-target="#tePagamosModal">
                            Calculadora <i class="fas fa-calculator ml-2 mt-1"></i>
                        </button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="total">Total <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="total" id="total" placeholder="sin comas ni puntos" readonly>
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
                                value="1000000" placeholder="sin comas ni puntos" required readonly>
                        </div>
                        @error('amparo_basico')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="val_prevexequial_eclusivo">Valor previ-exequial exclusivo <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="val_prevexequial_eclusivo" id="val_prevexequial_eclusivo" value="2400" placeholder="sin comas ni puntos" required readonly>
                        </div>
                        @error('val_prevexequial_eclusivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="prima_pago_prima_seguro">Prima de pago Prima de seguro <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" name="prima_pago_prima_seguro" id="prima_pago_prima_seguro" placeholder="sin comas ni puntos" required readonly>
                        </div>
                        @error('prima_pago_prima_seguro')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
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
                    <div class="form-group col-md-6">
                        <label for="val_total_desc_mensual" class="text-warning">Valor total de descuento mensual <span class="required">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control border-warning-custom" name="val_total_desc_mensual" id="val_total_desc_mensual" placeholder="00000" required readonly>
                        </div>
                        @error('val_total_desc_mensual')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <!-- Campo oculto para almacenar el resultado -->
        <input type="hidden" id="resultadoPierdesInput" name="tu_pierdes">
        <input type="hidden" id="resultadoPagamosInput" name="te_pagamos">


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

<!-- Modal -->
<div class="modal fade" id="tePagamosModal" tabindex="-1" role="dialog" aria-labelledby="tePagamosModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mx-auto" id="tePagamosModalLabel">Mira cuanto pierdes y cuanto te pagamos</h5>
            </div>
            <div class="modal-body">
                <div>
                    <h5 class="text-danger">TU PIERDES!!!</h5>
                    <div class="text-center">
                        <h2 class="text-danger" id="resultadoPierdes">0</h2> <!-- Aquí se mostrará el resultado -->
                    </div>
                </div>
                <br><br>
                <div>
                    <h4 class="text-success">NOSOTROS TE PAGAMOS!!!</h4>
                    <div class="text-center">
                        <h2 class="text-success" id="resultadoPagamos">0</h2> <!-- Aquí se mostrará el resultado -->
                    </div>
                </div>
            </div>
        </div>
    </div>
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
