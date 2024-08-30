@extends('adminlte::page')

@section('title', 'Panel')

@section('content_header')
<h1>Panel de administrador</h1>
@stop

@section('content')
<p>Bienvenido a este panel de administración, acá podrás ver la información principal del sistema, estadísticas y gráficas relacionadas a la información de los módulos.</p>
<br>
<div class="row">
    <div class="col-lg-4 col-sm-12 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Aseguradoras</h3>
                <p>Total Aseguradoras</p>
            </div>
            <div class="icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <a href="#" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Productividad</h3>
                <p>Porductividad total</p>
            </div>
            <div class="icon">
                <i class="fas fa-tasks"></i>
            </div>
            <a href="#" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Usuarios</h3>
                <p>Total Usuarios</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@stop
