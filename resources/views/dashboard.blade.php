@extends('adminlte::page')

@section('title', 'Panel')

@section('content_header')
    <h1>Panel de administrador</h1>
@stop

@section('content')
    <p>Bienvenido a este panel de administración, acá podrás ver la información principal del sistema, estadísticas y
        gráficas relacionadas a la información de los módulos.</p>
    <br>
    <div class="row">
        @if (Auth::user()->hasRole('Administrador'))
            
            <div class="col-lg-4 col-sm-12 col-12">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Aseguradoras</h3>
                        <p><b>{{ $totalAseguradoras }}</b></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <a href="{{ route('aseguradoras.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Afiliaciones</h3>
                        <p><b>{{ $totalAfiliaciones }}</b></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <a href="{{ route('incapacidades.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Usuarios</h3>
                        <p><b>{{ $totalUsuarios }}</b></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('usuarios.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif

    </div>
@stop

@section('css')
    <style>
        /* Ajuste de texto y cajas en pantallas pequeñas */
        @media (max-width: 576px) {
            .small-box .inner h3 {
                font-size: 18px;
                /* Reducir tamaño de h3 */
            }

            .small-box .inner p {
                font-size: 14px;
                /* Reducir tamaño de p */
            }

            .small-box {
                padding: 10px;
                /* Reducir padding en pantallas pequeñas */
            }

            .small-box-footer {
                font-size: 14px;
                /* Ajustar el tamaño del footer */
            }

            /* Ajuste de los iconos para pantallas pequeñas */
            .small-box .icon {
                font-size: 40px;
                /* Ajustar tamaño del icono para pantallas pequeñas */
                top: 10px;
                /* Ajustar la posición superior */
                right: 10px;
                /* Asegurar que el icono no se salga de la caja */
            }
        }

        /* Mantener el tamaño del icono en pantallas grandes */
        .small-box .icon {
            font-size: 60px;
            position: absolute;
            top: 15px;
            right: 10px;
            z-index: 0;
            opacity: 0.3;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
