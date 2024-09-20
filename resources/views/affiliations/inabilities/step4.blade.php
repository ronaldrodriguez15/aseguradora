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
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning rounded" role="progressbar" aria-valuenow="66.6" aria-valuemin="0" aria-valuemax="100" style="width: 66.6%; height: 100%;"></div>
        <div class="progress-text" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #000000; font-weight: bold; font-size: 18px;">
            Paso 4 de 6
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
    <form autocomplete="off" action="{{ route('incapacidades.formStepFive') }}" method="post" id="formStep4">
        @csrf
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="row">
                    <div class="col-md-6"><span>Productos del funcionario</span></div>
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
        <div class="card">
            <div class="card-body">
                <br>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="servicios_prevision_exequial">Servicios de previsión exequial <span class="required">*</span></label>
                        <select class="form-control @error('servicios_prevision_exequial') is-invalid @enderror" id="servicios_prevision_exequial"
                            name="servicios_prevision_exequial" required>
                            <option value="si">Si</option>
                            <option value="no">No</option>
                        </select>
                        @error('servicios_prevision_exequial')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="beneficiario_diario_inc_temp">Beneficio diario por incapacidad temporal <span class="required">*</span></label>
                        <select class="form-control @error('beneficiario_diario_inc_temp') is-invalid @enderror" id="beneficiario_diario_inc_temp"
                            name="beneficiario_diario_inc_temp" required>
                            <option value="si">Si</option>
                            <option value="no">No</option>
                        </select>
                        @error('beneficiario_diario_inc_temp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="serv_prevision_exequial_mascotas">Servicios de previsión exequial mascotas <span class="required">*</span></label>
                        <select class="form-control @error('serv_prevision_exequial_mascotas') is-invalid @enderror" id="serv_prevision_exequial_mascotas"
                            name="serv_prevision_exequial_mascotas" required>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('serv_prevision_exequial_mascotas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="serv_prevision_salud">Servicios de previsión salud mascotas<span class="required">*</span></label>
                        <select class="form-control @error('serv_prevision_salud') is-invalid @enderror" id="serv_prevision_salud"
                            name="serv_prevision_salud" required>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('serv_prevision_salud')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="otro">¿Otro? <span class="required">*</span></label>
                        <select class="form-control @error('otro') is-invalid @enderror" id="otro" name="otro" required>
                            <option value="si">Sí</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('otro')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="cual">¿Cuál? <span class="required">*</span></label>
                        <input type="text" class="form-control @error('cual') is-invalid @enderror" id="cual" name="cual">
                        @error('cual')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header pastel-blue-color">
                <div class="row">
                    <div class="col-md-6"><span>Declaración de asegurabilidad</span></div>
                </div>
            </div>
            <div class="card-body">
                <br>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cancer">Cancer <span class="required">*</span></label>
                        <select class="form-control @error('cancer') is-invalid @enderror" id="cancer"
                            name="cancer" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('cancer')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="corazon">Corazón <span class="required">*</span></label>
                        <select class="form-control @error('corazon') is-invalid @enderror" id="corazon"
                            name="corazon" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('corazon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="diabetes">Diabetes <span class="required">*</span></label>
                        <select class="form-control @error('diabetes') is-invalid @enderror" id="diabetes"
                            name="diabetes" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('diabetes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="enf_hepaticas">Enfermedades hépaticas <span class="required">*</span></label>
                        <select class="form-control @error('enf_hepaticas') is-invalid @enderror" id="enf_hepaticas"
                            name="enf_hepaticas" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('enf_hepaticas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="enf_neurologicas">Enfermedades neurológicas <span class="required">*</span></label>
                        <select class="form-control @error('enf_neurologicas') is-invalid @enderror" id="enf_neurologicas"
                            name="enf_neurologicas" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('enf_neurologicas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="pulmones">Pulmones <span class="required">*</span></label>
                        <select class="form-control @error('pulmones') is-invalid @enderror" id="pulmones"
                            name="pulmones" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('pulmones')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="presion_arterial">Presión arterial <span class="required">*</span></label>
                        <select class="form-control @error('presion_arterial') is-invalid @enderror" id="presion_arterial"
                            name="presion_arterial" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('presion_arterial')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="rinones">Riñones <span class="required">*</span></label>
                        <select class="form-control @error('rinones') is-invalid @enderror" id="rinones"
                            name="rinones" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('rinones')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="infeccion_vih">Infecciones por V.I.H (Sida) <span class="required">*</span></label>
                        <select class="form-control @error('infeccion_vih') is-invalid @enderror" id="infeccion_vih"
                            name="infeccion_vih" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('infeccion_vih')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="perdida_funcional_anatomica">¿Ha tenido o tiene alguna perdida funcional o anatómica? <span class="required">*</span></label>
                        <select class="form-control @error('perdida_funcional_anatomica') is-invalid @enderror" id="perdida_funcional_anatomica"
                            name="perdida_funcional_anatomica" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('perdida_funcional_anatomica')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="accidentes_labores_ocupacion">¿Ha padecido accidentes que le impidan desempeñar labores propias de su ocupación? <span class="required">*</span></label>
                        <select class="form-control @error('accidentes_labores_ocupacion') is-invalid @enderror" id="accidentes_labores_ocupacion"
                            name="accidentes_labores_ocupacion" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('serv_prevision_exequial_mascotas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="hospitalizacion_intervencion_quirurgica">¿Tiene proyectada alguna hospitalización, examen o intenvención quirúrgica? <span class="required">*</span></label>
                        <select class="form-control @error('hospitalizacion_intervencion_quirurgica') is-invalid @enderror" id="hospitalizacion_intervencion_quirurgica"
                            name="hospitalizacion_intervencion_quirurgica" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('serv_prevision_salud')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="enfermedad_diferente">¿Ha padecido, padece o es tratado actualmente de alguna enfermedad o incapacidad diferente a las enunciadas arriba? <span class="required">*</span></label>
                        <select class="form-control @error('enfermedad_diferente') is-invalid @enderror" id="enfermedad_diferente"
                            name="enfermedad_diferente" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('enfermedad_diferente')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="enf_cerebrovasculares">Enfermedades cerebrovasculares <span class="required">*</span></label>
                        <select class="form-control @error('enf_cerebrovasculares') is-invalid @enderror" id="enf_cerebrovasculares"
                            name="enf_cerebrovasculares" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('enf_cerebrovasculares')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cirugias">¿Alguna cirugia? <span class="required">*</span></label>
                        <select class="form-control @error('cirugias') is-invalid @enderror" id="cirugias"
                            name="cirugias" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('cirugias')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="alcoholismo">Alcoholismo <span class="required">*</span></label>
                        <select class="form-control @error('alcoholismo') is-invalid @enderror" id="alcoholismo"
                            name="alcoholismo" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('alcoholismo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tabaquismo">Tabaquismo <span class="required">*</span></label>
                        <select class="form-control @error('tabaquismo') is-invalid @enderror" id="tabaquismo"
                            name="tabaquismo" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('tabaquismo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="enf_congenitas">Enfermedades congenitas <span class="required">*</span></label>
                        <select class="form-control @error('enf_congenitas') is-invalid @enderror" id="enf_congenitas"
                            name="enf_congenitas" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('enf_congenitas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="enf_colageno">Enfermedades del colageno <span class="required">*</span></label>
                        <select class="form-control @error('enf_colageno') is-invalid @enderror" id="enf_colageno"
                            name="enf_colageno" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('enf_colageno')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="enf_hematologicas">Enfermedades hematologicas <span class="required">*</span></label>
                        <select class="form-control @error('enf_hematologicas') is-invalid @enderror" id="enf_hematologicas"
                            name="enf_hematologicas" required>
                            <option value="">Selecciona</option>
                            <option value="si">Si</option>
                            <option value="no" selected>No</option>
                        </select>
                        @error('enf_hematologicas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row" style="display: none;" id="descripcion_de_enfermedades">
                    <div class="form-group col-md-12">
                        <label for="descripcion_de_enfermedades">En caso de haber contestado afirmativamente alguna de las preguntas anteriores,
                            por favor, a continuación describa los detalles con su respectiva fecha de diagnostico </label>
                        <textarea class="form-control @error('descripcion_de_enfermedades') is-invalid @enderror"
                            name="descripcion_de_enfermedades"
                            rows="4"
                            placeholder="Describe si tienes alguna enfermedad hematológica"></textarea>
                        @error('descripcion_de_enfermedades')
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

</script>

<script src="{{ asset('js/step5.js') }}"></script>
@stop
