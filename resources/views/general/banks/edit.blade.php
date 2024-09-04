@extends('adminlte::page')

@section('title', 'Bancos')

@section('content_header')
<h1>Editar Banco</h1>
@stop

@section('content')
<p>En este modulo puedes editar o gestionar el registro seleccionado.</p>
<br><br><br>

<div class="container">
    <form autocomplete="off" action="{{ route('bancos.store') }}" method="post">
        @method('PUT')
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Entidad <span class="required">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $bank['name'] }}" placeholder="Introduce el nombre de la entidad" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="type_entity">Tipo de entidad <span class="required">*</span></label>
                <input type="text" class="form-control @error('type_entity') is-invalid @enderror" id="type_entity" name="type_entity" value="{{ $bank['type_entity'] }}" placeholder="Introduce el tipo de la entidad" required>
                @error('type_entity')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="area">Área <span class="required">*</span></label>
                <input type="text" class="form-control @error('area') is-invalid @enderror" id="area" name="area" value="{{ $bank['area'] }}" placeholder="Introduce el àrea" required>
                @error('area')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="official_in_charge">Funcionario encargado <span class="required">*</span></label>
                <input type="text" class="form-control @error('official_in_charge') is-invalid @enderror" id="official_in_charge" name="official_in_charge" value="{{ $bank['official_in_charge'] }}" placeholder="Introduce el nombre del funcionario" required>
                @error('official_in_charge')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="employment">Cargo <span class="required">*</span></label>
                <input type="text" class="form-control @error('employment') is-invalid @enderror" id="employment" name="employment" value="{{ $bank['employment'] }}" placeholder="Describe el cargo" required>
                @error('employment')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="phone">Télefono <span class="required">*</span></label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ $bank['phone'] }}" placeholder="Número túlefonico" required>
                @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="mobile">Celular <span class="required">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ $bank['mobile'] }}" placeholder="Nùmero celular" required>
                @error('mobile')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="email">Correo Electronico <span class="required">*</span></label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $bank['email'] }}" placeholder="alguien@example.com" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="address">Dirección de la entidad <span class="required">*</span></label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ $bank['address'] }}" placeholder="Introduce la dirección de la entidad" required>
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <a href="{{ route('bancos.index') }}" class="btn btn-info">
            <i class="fas fa-arrow-left mr-2"></i> Regresar
        </a>
        <button type="submit" type="button" class="btn btn-success">
            <i class="fas fa-save mr-2"></i> Guardar Banco
        </button>
    </form>
</div>

@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('input').attr('autocomplete', 'off');
    });
</script>
@stop
