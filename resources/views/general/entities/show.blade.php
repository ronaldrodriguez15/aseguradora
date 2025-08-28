@extends('adminlte::page')

@section('title', 'Entidades')

@section('content_header')
    <h1>Detalles de la entidad</h1>
@stop

@section('content')
    <p>En este modulo puedes ver todos los detalles de la entidad seleccionada según la asignación que te brinde un
        administrador.</p>
    <br><br><br>

    <div class="row">
        <div class="col-md-12 text-right mb-5">
            <a href="{{ route('entidades.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left mr-2"></i> Regresar
            </a>
            @if (Auth::user()->hasRole('Administrador') || Auth::user()->permisos_entidades === 1)
            <a href="{{ route('entidades.edit', $entity->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Editar entidad
            </a>
            @endif
        </div>
        <div class="col-md-12">
            <!-- Controller response -->
            @if (session()->get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session()->get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="mb-2 text-left">
            @foreach ($history as $version)
                <a href="{{ route('entidades.show', $version->id) }}"
                    class="btn btn-sm {{ $version->id == $entity->id ? 'btn-primary' : 'btn-outline-primary' }}">
                    Registro {{ $loop->iteration }}
                    <small
                        class="text-muted">({{ $version->created_at ? $version->created_at->format('d/m/Y H:i') : 'Sin fecha' }}
                        )</small>
                </a>
            @endforeach
        </div>

        <div class="card mb-5 shadow-sm">
            <div class="card-header bg-white text-white">
                <h5 class="mb-0">Información de la Entidad</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nombre:</strong><br>
                        @if ($entity->name)
                            @if (Auth::user()->name_entity === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->name }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Nemo:</strong><br>
                        @if ($entity->nemo)
                            @if (Auth::user()->nemo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->nemo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <strong>Nit:</strong><br>
                        @if ($entity->cnitpagador)
                            @if (Auth::user()->cnitpagador === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->cnitpagador }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Sucursal:</strong><br>
                        @if ($entity->sucursal)
                            @if (Auth::user()->sucursal === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->sucursal }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Nombre quien apertura:</strong><br>
                        @if ($entity->n_apertura)
                            @if (Auth::user()->n_apertura === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->n_apertura }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Fecha de apertura:</strong><br>
                        @if ($entity->fecha_apertura)
                            @if (Auth::user()->fecha_apertura === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->fecha_apertura }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tipo de empresa:</strong><br>
                        @if ($entity->t_empresa)
                            @if (Auth::user()->t_empresa === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->t_empresa }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Dirección:</strong><br>
                        @if ($entity->direccion)
                            @if (Auth::user()->direccion === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->direccion }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Departamento:</strong><br>
                        @if ($entity->department)
                            @if (Auth::user()->department === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->department }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Ciudad de expedición:</strong><br>
                        @if ($entity->ciudad_expedicion)
                            @if (Auth::user()->ciudad_expedicion === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->ciudad_expedicion }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>PBX:</strong><br>
                        @if ($entity->pbx)
                            @if (Auth::user()->pbx === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->pbx }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Número de empleados de planta:</strong><br>
                        @if ($entity->n_empleados)
                            @if (Auth::user()->n_empleados === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->n_empleados }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Número de empleados contratistas:</strong><br>
                        @if ($entity->n_contratistas)
                            @if (Auth::user()->n_contratistas === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->n_contratistas }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Personas con salario mínimo:</strong><br>
                        @if ($entity->p_salario)
                            @if (Auth::user()->p_salario === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->p_salario }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Número de sedes:</strong><br>
                        @if ($entity->n_sedes)
                            @if (Auth::user()->n_sedes === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->n_sedes }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tipo de orden:</strong><br>
                        @if ($entity->t_orden_empresa)
                            @if (Auth::user()->t_orden_empresa === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->t_orden_empresa }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>ARL de empresa:</strong><br>
                        @if ($entity->arl_empresa)
                            @if (Auth::user()->arl_empresa === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas') || Auth::user()->hasRole('Ventas'))
                                {{ $entity->arl_empresa }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>¿Modalidad Híbrida?:</strong><br>
                        @if ($entity->m_hibrido)
                            @if (Auth::user()->m_hibrido === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->m_hibrido }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Dínamica de asistencia:</strong><br>
                        @if ($entity->d_asistencia)
                            @if (Auth::user()->d_asistencia === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->d_asistencia }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Día de la semana donde hay mayor número de personas:</strong><br>
                        @if ($entity->d_numero_personas)
                            @if (Auth::user()->d_numero_personas === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->d_numero_personas }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Valor de salario mínimo:</strong><br>
                        @if ($entity->v_salario_minimo)
                            @if (Auth::user()->v_salario_minimo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->v_salario_minimo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Valor de salario máximo:</strong><br>
                        @if ($entity->v_salario_maximo)
                            @if (Auth::user()->v_salario_maximo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->v_salario_maximo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Pago de nómina:</strong><br>
                        @if ($entity->pago_nomina)
                            @if (Auth::user()->pago_nomina === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->pago_nomina }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Metodología de socialización:</strong><br>
                        @if ($entity->m_socializacion)
                            @if (Auth::user()->m_socializacion === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->m_socializacion }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Director TTHH - Nombres y apellidos:</strong><br>
                        @if ($entity->tthh_nombres)
                            @if (Auth::user()->tthh_nombres === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->tthh_nombres }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Director TTHH - Cel 1:</strong><br>
                        @if ($entity->tthh_cel1)
                            @if (Auth::user()->tthh_cel1 === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->tthh_cel1 }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Director TTHH - Cel 2:</strong><br>
                        @if ($entity->tthh_cel2)
                            @if (Auth::user()->tthh_cel2 === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->tthh_cel2 }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Director TTHH - Cel 3:</strong><br>
                        @if ($entity->tthh_cel3)
                            @if (Auth::user()->tthh_cel3 === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->tthh_cel3 }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Director TTHH - Email:</strong><br>
                        @if ($entity->tthh_email)
                            @if (Auth::user()->tthh_email === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->tthh_email }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Director TTHH - Cargo:</strong><br>
                        @if ($entity->tthh_cargo)
                            @if (Auth::user()->tthh_cargo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->tthh_cargo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Director TTHH - Observaciones:</strong><br>
                        @if ($entity->tthh_observaciones)
                            @if (Auth::user()->tthh_observaciones === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->tthh_observaciones }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de nómina - Nombre y Apellido:</strong><br>
                        @if ($entity->area_nomina_nombres)
                            @if (Auth::user()->area_nomina_nombres === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->area_nomina_nombres }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de nómina - Celular:</strong><br>
                        @if ($entity->area_nomina_celular)
                            @if (Auth::user()->area_nomina_celular === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->area_nomina_celular }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de nómina - Email:</strong><br>
                        @if ($entity->area_nomina_email)
                            @if (Auth::user()->area_nomina_email === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->area_nomina_email }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Encargado área de nómina - Cargo:</strong><br>
                        @if ($entity->area_nomina_cargo)
                            @if (Auth::user()->area_nomina_cargo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->area_nomina_cargo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de nómina - Observaciones:</strong><br>
                        @if ($entity->area_nomina_observaciones)
                            @if (Auth::user()->area_nomina_observaciones === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->area_nomina_observaciones }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Observaciones / Proceso visado:</strong><br>
                        @if ($entity->observaciones_visado)
                            @if (Auth::user()->observaciones_visado === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->observaciones_visado }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Archivos para radicación:</strong><br>
                        @if ($entity->archivos_radicacion)
                            @if (Auth::user()->archivos_radicacion === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->archivos_radicacion }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de bienestar - Nombre y apellido:</strong><br>
                        @if ($entity->ea_nombres)
                            @if (Auth::user()->ea_nombres === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->ea_nombres }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de bienestar - Celular:</strong><br>
                        @if ($entity->ea_cel)
                            @if (Auth::user()->ea_cel === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->ea_cel }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Encargado área de bienestar - Email:</strong><br>
                        @if ($entity->ea_email)
                            @if (Auth::user()->ea_email === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->ea_email }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de bienestar - Cargo:</strong><br>
                        @if ($entity->ea_cargo)
                            @if (Auth::user()->ea_cargo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->ea_cargo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de bienestar - Observaciones:</strong><br>
                        @if ($entity->ea_observaciones)
                            @if (Auth::user()->ea_observaciones === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->ea_observaciones }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Encargado área de tesorería - Nombre y Apellido:</strong><br>
                        @if ($entity->at_nombres)
                            @if (Auth::user()->at_nombres === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->at_nombres }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de tesorería - Celular:</strong><br>
                        @if ($entity->at_cel)
                            @if (Auth::user()->at_cel === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->at_cel }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de tesorería - Email:</strong><br>
                        @if ($entity->at_email)
                            @if (Auth::user()->at_email === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->at_email }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Encargado área de tesorería - Cargo:</strong><br>
                        @if ($entity->at_cargo)
                            @if (Auth::user()->at_cargo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->at_cargo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Encargado área de tesorería - Observaciones:</strong><br>
                        @if ($entity->at_observaciones)
                            @if (Auth::user()->at_observaciones === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->at_observaciones }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Observaciones - Otros contactos:</strong><br>
                        @if ($entity->observaciones_c)
                            @if (Auth::user()->observaciones_c === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->observaciones_c }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>¿Autorizan código?:</strong><br>
                        @if ($entity->codigo)
                            @if (Auth::user()->codigo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->codigo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>¿Permiten 1 a 1?:</strong><br>
                        @if ($entity->{'1_1'})
                            @if (Auth::user()->{'1_1'} === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->{'1_1'} }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Ruta:</strong><br>
                        @if ($entity->ruta)
                            @if (Auth::user()->ruta === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->ruta }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Zona:</strong><br>
                        @if ($entity->zona)
                            @if (Auth::user()->zona === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->zona }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Código postal:</strong><br>
                        @if ($entity->codigo_postal)
                            @if (Auth::user()->codigo_postal === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->codigo_postal }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Afiliados planta:</strong><br>
                        @if ($entity->afiliados_planta)
                            @if (Auth::user()->afiliados_planta === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->afiliados_planta }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Afiliados contratistas:</strong><br>
                        @if ($entity->afiliados_contratistas)
                            @if (Auth::user()->afiliados_contratistas === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->afiliados_contratistas }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Historial de afiliados:</strong><br>
                        @if ($entity->historial_afiliados)
                            @if (Auth::user()->historial_afiliados === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->historial_afiliados }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Con código:</strong><br>
                        @if ($entity->c_codigo)
                            @if (Auth::user()->c_codigo === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->c_codigo }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>¿Apertura?:</strong><br>
                        @if ($entity->apertura)
                            @if (Auth::user()->apertura === '1' || Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Jefe de ventas'))
                                {{ $entity->apertura }}
                            @else
                                <i class="text-muted">No disponible</i>
                            @endif
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    @if (Auth::user()->hasRole('Administrador'))
                        <div class="col-md-12">
                            @if ($entity->updated_at)
                                <div class="alert alert-info" role="alert">
                                    Última modificación del registro fue el
                                    <strong>{{ $entity->updated_at->format('d/m/Y h:i A') }}</strong>
                                </div>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    Oops!, No se encontró la última modificación del registro.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @stop

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    @stop

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Tu archivo de script personalizado -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>
    @stop
