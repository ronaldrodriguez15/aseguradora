@extends('adminlte::page')

@section('title', 'Entidades')

@section('content_header')
<h1>Editar Entidad</h1>
@stop

@section('content')
<p>En este modulo puedes editar o gestionar el registro seleccionado.</p>
<br><br><br>

<div class="container">
    <form autocomplete="off" action="{{ route('entidades.update', $entity['id']) }}" method="post">
        @method('PUT')
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nombre de la entidad <span class="required">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $entity['name'] }}" placeholder="Introduce el nombre de la entidad" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="type_entity">Abreviatura <span class="required">*</span></label>
                <input type="text" class="form-control @error('nemo') is-invalid @enderror" id="nemo" name="nemo" value="{{ $entity['nemo'] }}" placeholder="Introduce la abreviatura de la entidad" required>
                @error('nemo')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="cnitpagador">NIT o código <span class="required">*</span></label>
                <input type="number" class="form-control @error('cnitpagador') is-invalid @enderror" id="cnitpagador" name="cnitpagador" value="{{ $entity['cnitpagador'] }}" placeholder="0000000" required>
                @error('cnitpagador')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <a href="{{ route('entidades.index') }}" class="btn btn-info">
            <i class="fas fa-arrow-left mr-2"></i> Regresar
        </a>
        <button type="submit" type="button" class="btn btn-success">
            <i class="fas fa-save mr-2"></i> Guardar Entidad
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
