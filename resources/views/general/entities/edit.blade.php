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

            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <h6><b>Nombre de la entidad:</b> {{ $entity['name'] }} </h6>
                            <h6><b>Abreviatura:</b> <span class="text-info">{{ $entity['nemo'] }}</span> </h6>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cnitpagador">NIT o código <span class="required">*</span></label>
                            <input type="number" class="form-control @error('cnitpagador') is-invalid @enderror"
                                id="cnitpagador" name="cnitpagador" value="{{ $entity['cnitpagador'] }}"
                                placeholder="0000000" required>
                            @error('cnitpagador')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sucursal">Sucursal <span class="required">*</span></label>
                            <input type="number" class="form-control @error('sucursal') is-invalid @enderror"
                                id="sucursal" name="sucursal" value="{{ $entity['sucursal'] }}"
                                placeholder="0000000" required>
                            @error('sucursal')
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
                </div>
            </div>
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
