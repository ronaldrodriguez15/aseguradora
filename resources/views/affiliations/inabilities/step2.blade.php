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
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning rounded" role="progressbar" aria-valuenow="33.3" aria-valuemin="0" aria-valuemax="100" style="width: 33.3%; height: 100%;"></div>
        <div class="progress-text" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #000000; font-weight: bold; font-size: 18px;">
            Paso 2 de 6
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
    <form autocomplete="off" action="{{ route('incapacidades.formStepTree') }}" method="post" id="formStep2">
        @csrf
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="row">
                    <div class="col-md-6"><span>Información forma de pago</span></div>
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
                <br><br>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="forma_pago">Forma de pago <span class="required">*</span></label>
                        <select class="form-control @error('forma_pago') is-invalid @enderror" id="forma_pago"
                            name="forma_pago" required>
                            <option value="">Selecciona la forma de pago</option>
                            <option value="debito_automatico">Debito automatico</option>
                            <option value="mensual_libranza">Mensual Libranza</option>
                        </select>
                        @error('forma_pago')
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
                                placeholder="Escribe el N de cuenta" required>
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
                                placeholder="Repite el N de cuenta" required onpaste="return false">
                        </div>
                        <div class="invalid-feedback">Los números de cuenta no coinciden.</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="banco">Banco <span class="required">*</span></label>
                        <select class="form-control @error('banco') is-invalid @enderror" id="banco"
                            name="banco" required>
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
                            name="ciudad_banco" required>
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
<script src="{{ asset('js/step2.js') }}"></script>
@stop
