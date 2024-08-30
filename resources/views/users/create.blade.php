@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Crear usuario</h1>
@stop

@section('content')
    <p>En este modulo puedes crear un nuevo usuario.</p>
    <br><br><br>

    <div class="container">
        <form autocomplete="off" action="{{ route('usuarios.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Nombres y Apellidos <span class="required">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" placeholder="Introduce tus nombres y Apellidos" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="document">Documento <span class="required">*</span></label>
                    <input type="number" class="form-control @error('document') is-invalid @enderror" id="document"
                        name="document" placeholder="Introduce tus número de documento" required>
                    @error('document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Celular <span class="required">*</span></label>
                    <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone"
                        name="phone" placeholder="Introduce tu número de celular" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Correo electrónico <span class="required">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="Introduce tu correo electrónico" autocomplete="new-email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="emailConfirm">Confirmación correo electrónico <span class="required">*</span></label>
                    <input type="email" class="form-control @error('emailConfirm') is-invalid @enderror" id="emailConfirm"
                        name="emailConfirm" placeholder="Confirma tu correo electrónico" required>
                    @error('emailConfirm')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="birthdate">Fecha de nacimiento <span class="required">*</span></label>
                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate"
                        name="birthdate" placeholder="Introduce tu fecha de nacimiento" required>
                    @error('birthdate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Contraseña <span class="required">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Introduce tu contraseña" autocomplete="new-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save mr-2"></i>Registrar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left mr-2"></i> Regresar
            </a>
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
