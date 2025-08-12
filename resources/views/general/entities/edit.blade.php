@extends('adminlte::page')

@section('title', 'Entidades')

@section('content_header')
    <h1>Editar Entidad</h1>
@stop

@section('content')
    <p>En este modulo puedes editar o gestionar el registro seleccionado.</p>
    <br><br><br>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Hay problemas con los datos ingresados:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid">
        <form autocomplete="off" action="{{ route('entidades.update', $entity['id']) }}" method="post">
            @method('PUT')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <h6><b>Nombre de la entidad:</b> {{ $entity['name'] }} </h6>
                </div>
                <div class="form-group col-md-6">
                    <h6><b>NIT o Código:</b> <span class="text-info">{{ $entity['cnitpagador'] }}</span> </h6>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nemo">Abreviatura <span class="required">*</span></label>
                    <input type="text" class="form-control @error('nemo') is-invalid @enderror" id="nemo"
                        name="nemo" value="{{ $entity['nemo'] }}" placeholder="0000000" required>
                    @error('nemo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="sucursal">Sucursal <span class="required">*</span></label>
                    <input type="number" class="form-control @error('sucursal') is-invalid @enderror" id="sucursal"
                        name="sucursal" value="{{ $entity['sucursal'] }}" placeholder="0000000">
                    @error('sucursal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="n_apertura">Nombre de quien apertura <span class="required">*</span></label>
                    <select class="form-control @error('n_apertura') is-invalid @enderror" id="n_apertura" name="n_apertura"
                        required>
                        <option value="" disabled
                            {{ old('n_apertura', $entity->n_apertura) == null ? 'selected' : '' }}>
                            Seleccione un departamento
                        </option>
                        @foreach ($asesors as $asesor)
                            <option value="{{ $asesor->id }}"
                                {{ old('n_apertura', $entity->n_apertura) == $asesor->id ? 'selected' : '' }}>
                                {{ $asesor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('n_apertura')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="fecha_apertura">Fecha apertura <span class="required">*</span></label>
                    <input type="date" class="form-control @error('fecha_apertura') is-invalid @enderror"
                        id="fecha_apertura" name="fecha_apertura" value="{{ $entity->fecha_apertura }}">
                    @error('fecha_apertura')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="t_empresa">Tipo de empresa</label>
                    <select class="form-control @error('t_empresa') is-invalid @enderror" id="t_empresa" name="t_empresa"
                        >
                        <option value="" disabled
                            {{ old('t_empresa', $entity->t_empresa) == null ? 'selected' : '' }}>
                            Seleccione una opción
                        </option>
                        <option value="Publica" {{ old('t_empresa', $entity->t_empresa) == 'Publica' ? 'selected' : '' }}>
                            Pública
                        </option>
                        <option value="Privada" {{ old('t_empresa', $entity->t_empresa) == 'Privada' ? 'selected' : '' }}>
                            Privada
                        </option>
                    </select>
                    @error('t_empresa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion"
                        name="direccion" placeholder="Introduce la dirección" value="{{ $entity->direccion }}">
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="department">Departamento </label>
                    <select class="form-control @error('department') is-invalid @enderror" id="department" name="department"
                        >
                        <option value="" disabled
                            {{ old('department', $entity->department) == null ? 'selected' : '' }}>
                            Seleccione un departamento
                        </option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id_departamento }}"
                                {{ old('department', $entity->department) == $department->id_departamento ? 'selected' : '' }}>
                                {{ $department->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="ciudad_expedicion">Ciudad</label>
                    <select class="form-control @error('ciudad_expedicion') is-invalid @enderror" id="ciudad_expedicion"
                        name="ciudad_expedicion">
                        <option value="" disabled selected>Seleccione una ciudad</option>
                    </select>
                    @error('ciudad_expedicion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="pbx">PBX</label>
                    <input type="text" class="form-control @error('pbx') is-invalid @enderror" id="pbx"
                        name="pbx" placeholder="---" value="{{ $entity->pbx }}">
                    @error('pbx')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="n_empleados">Número de empleados de planta</label>
                    <input type="number" class="form-control @error('n_empleados') is-invalid @enderror" id="n_empleados"
                        name="n_empleados" placeholder="00" value="{{ $entity->n_empleados }}">
                    @error('n_empleados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="n_contratistas">Número de empleados contratistas</label>
                    <input type="number" class="form-control @error('n_contratistas') is-invalid @enderror"
                        id="n_contratistas" name="n_contratistas" placeholder="00"
                        value="{{ $entity->n_contratistas }}">
                    @error('n_contratistas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="p_salario">Número de personas que ganan salario mínimo</label>
                    <input type="number" class="form-control @error('p_salario') is-invalid @enderror" id="p_salario"
                        name="p_salario" placeholder="00" value="{{ $entity->p_salario }}">
                    @error('p_salario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="n_sedes">Número de sedes</label>
                    <input type="number" class="form-control @error('n-sedes') is-invalid @enderror" id="n_sedes"
                        name="n_sedes" placeholder="00" value="{{ $entity->n_sedes }}">
                    @error('n_sedes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="t_orden_empresa">Tipo de orden de empresa</label>
                    <select class="form-control @error('t_orden_empresa') is-invalid @enderror" id="t_orden_empresa"
                        name="t_orden_empresa">
                        <option value="" disabled
                            {{ old('t_orden_empresa', $entity->t_orden_empresa) == null ? 'selected' : '' }}>
                            Seleccione una opción
                        </option>
                        <option value="publica"
                            {{ old('t_orden_empresa', $entity->t_orden_empresa) == 'publica' ? 'selected' : '' }}>
                            Pública
                        </option>
                        <option value="privada"
                            {{ old('t_orden_empresa', $entity->t_orden_empresa) == 'privada' ? 'selected' : '' }}>
                            Privada
                        </option>
                    </select>
                    @error('t_orden_empresa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="arl_empresa">ARL de la empresa</label>
                    <input type="text" class="form-control @error('arl_empresa') is-invalid @enderror"
                        id="arl_empresa" name="arl_empresa" placeholder="Escribe la ARL"
                        value="{{ $entity->arl_empresa }}">
                    @error('arl_empresa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="m_hibrido">Modalidad híbrida</label>
                    <select class="form-control @error('m_hibrido') is-invalid @enderror" id="m_hibrido"
                        name="m_hibrido">
                        <option value="" disabled
                            {{ old('m_hibrido', $entity->m_hibrido) == null ? 'selected' : '' }}>
                            Seleccione una opción
                        </option>
                        <option value="Si" {{ old('m_hibrido', $entity->m_hibrido) == 'Si' ? 'selected' : '' }}>
                            Sí
                        </option>
                        <option value="No" {{ old('m_hibrido', $entity->m_hibrido) == 'No' ? 'selected' : '' }}>
                            No
                        </option>
                    </select>
                    @error('m_hibrido')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="d_asistencia">Dínamica de asistencia</label>
                    <input type="text" class="form-control @error('d_asistencia') is-invalid @enderror"
                        id="d_asistencia" name="d_asistencia" placeholder="Introduce la dínamica de asistencia"
                        value="{{ $entity->d_asistencia }}">
                    @error('d_asistencia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-8">
                    <label for="d_numero_personas">Día de la semana donde hay mayor número de personas en la
                        entidad</label>
                    <input type="text" class="form-control @error('d_numero_personas') is-invalid @enderror"
                        id="d_numero_personas" name="d_numero_personas" placeholder="Introduce el dia"
                        value="{{ $entity->d_numero_personas }}">
                    @error('d_numero_personas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="v_salario_minimo">Valor del salario mínimo en la entidad</label>
                    <input type="number" class="form-control @error('v_salario_minimo') is-invalid @enderror"
                        id="v_salario_minimo" name="v_salario_minimo" placeholder="COP"
                        value="{{ $entity->v_salario_minimo }}">
                    @error('v_salario_minimo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="v_salario_maximo">Valor del salario maximo en la entidad</label>
                    <input type="number" class="form-control @error('v_salario_maximo') is-invalid @enderror"
                        id="v_salario_maximo" name="v_salario_maximo" placeholder="COP"
                        value="{{ $entity->v_salario_maximo }}">
                    @error('v_salario_maximo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="pago_nomina">Pago de nómina </label>
                    <select class="form-control @error('pago_nomina') is-invalid @enderror" id="pago_nomina"
                        name="pago_nomina">
                        <option value="" disabled
                            {{ old('pago_nomina', $entity->pago_nomina) == null ? 'selected' : '' }}>
                            Seleccione una opción
                        </option>
                        <option value="quincenal"
                            {{ old('pago_nomina', $entity->pago_nomina) == 'quincenal' ? 'selected' : '' }}>
                            Quincenal
                        </option>
                        <option value="mensual"
                            {{ old('pago_nomina', $entity->pago_nomina) == 'mensual' ? 'selected' : '' }}>
                            Mensual
                        </option>
                    </select>
                    @error('pago_nomina')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="m_socializacion">Metodología de socialización beneficio</label>
                    <input type="text" class="form-control @error('m_socializacion') is-invalid @enderror"
                        id="m_socializacion" name="m_socializacion" placeholder="Introduce la metodología"
                        value="{{ $entity->m_socializacion }}">
                    @error('m_socializacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tthh_nombres">Director de TTHH - Nombres y apellidos</label>
                    <input type="text" class="form-control @error('tthh_nombres') is-invalid @enderror"
                        id="tthh_nombres" name="tthh_nombres" placeholder="Introduce los nombres y apellidos"
                        value="{{ $entity->tthh_nombres }}">
                    @error('tthh_nombres')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="tthh_cel1">Director de TTHH - celular 1</label>
                    <input type="number" class="form-control @error('tthh_cel1') is-invalid @enderror" id="tthh_cel1"
                        name="tthh_cel1" placeholder="Número de celular 1" value="{{ $entity->tthh_cel1 }}">
                    @error('tthh_cel1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tthh_cel2">Director de TTHH - Celular 2</label>
                    <input type="number" class="form-control @error('tthh_cel2') is-invalid @enderror" id="tthh_cel2"
                        name="tthh_cel2" placeholder="Número de celular 2" value="{{ $entity->tthh_cel2 }}">
                    @error('tthh_cel2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="tthh_cel3">Director de TTHH - celular 3</label>
                    <input type="number" class="form-control @error('tthh_cel3') is-invalid @enderror" id="tthh_cel3"
                        name="tthh_cel3" placeholder="Número de celular 3" value="{{ $entity->tthh_cel3 }}">
                    @error('tthh_cel3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tthh_email">Director de TTHH - Email</label>
                    <input type="email" class="form-control @error('tthh_email') is-invalid @enderror" id="tthh_email"
                        name="tthh_email" placeholder="alguien@example.com" value="{{ $entity->tthh_email }}">
                    @error('tthh_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="tthh_cargo">Director de TTHH - cargo</label>
                    <input type="text" class="form-control @error('tthh_cargo') is-invalid @enderror" id="tthh_cargo"
                        name="tthh_cargo" value="{{ $entity->tthh_cargo }}">
                    @error('tthh_cargo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tthh_observaciones">Director de TTHH - Observaciones</label>
                    <input type="text" class="form-control @error('tthh_observaciones') is-invalid @enderror"
                        id="tthh_observaciones" name="tthh_observaciones" value="{{ $entity->tthh_observaciones }}">
                    @error('tthh_observaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="area_nomina_nombres">Encargado del área de nómina - Nombres y apellidos</label>
                    <input type="text" class="form-control @error('area_nomina_nombres') is-invalid @enderror"
                        id="area_nomina_nombres" name="area_nomina_nombres"
                        placeholder="Introduce los nombres y apellidos" value="{{ $entity->area_nomina_nombres }}">
                    @error('area_nomina_nombres')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="area_nomina_celular">Encargado del área de nómina - Celular 1</label>
                    <input type="number" class="form-control @error('area_nomina_celular') is-invalid @enderror"
                        id="area_nomina_celular" name="area_nomina_celular" placeholder="Número de celular"
                        value="{{ $entity->area_nomina_celular }}">
                    @error('area_nomina_celular')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="area_nomina_email">Encargado del área de nómina - Email</label>
                    <input type="email" class="form-control @error('area_nomina_email') is-invalid @enderror"
                        id="area_nomina_email" name="area_nomina_email" placeholder="alguien@example.com"
                        value="{{ $entity->area_nomina_email }}">
                    @error('area_nomina_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="area_nomina_cargo">Encargado del área de nómina - Cargo</label>
                    <input type="text" class="form-control @error('area_nomina_cargo') is-invalid @enderror"
                        id="area_nomina_cargo" name="area_nomina_cargo" placeholder="Introduce el cargo"
                        value="{{ $entity->area_nomina_cargo }}">
                    @error('area_nomina_cargo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="area_nomina_observaciones">Encargado del área de nómina - Observaciones</label>
                    <input type="text" class="form-control @error('area_nomina_observaciones') is-invalid @enderror"
                        id="area_nomina_observaciones" name="area_nomina_observaciones"
                        placeholder="Introduce la Observación" value="{{ $entity->area_nomina_observaciones }}">
                    @error('area_nomina_observaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="observaciones_visado">Observaciones/Proceso de visado/Radicado</label>
                    <input type="text" class="form-control @error('observaciones_visado') is-invalid @enderror"
                        id="observaciones_visado" name="observaciones_visado"
                        placeholder="Introduce el Proceso de visado" value="{{ $entity->observaciones_visado }}">
                    @error('observaciones_visado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="archivos_radicacion">Archivos para radicación</label>
                    <input type="text" class="form-control @error('archivos_radicacion') is-invalid @enderror"
                        id="archivos_radicacion" name="archivos_radicacion"
                        placeholder="Introduce los archivos para radicación" value="{{ $entity->archivos_radicacion }}">
                    @error('archivos_radicacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ea_nombres">Encargado del área de bienestar - Nombres y apellidos</label>
                    <input type="text" class="form-control @error('ea_nombres') is-invalid @enderror" id="ea_nombres"
                        name="ea_nombres" placeholder="Introduce los nombres y apellidos"
                        value="{{ $entity->ea_nombres }}">
                    @error('ea_nombres')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="ea_cel">Encargado del área de bienestar - Celular</label>
                    <input type="number" class="form-control @error('ea_cel') is-invalid @enderror" id="ea_cel"
                        name="ea_cel" placeholder="Introduce el número" value="{{ $entity->ea_cel }}">
                    @error('ea_cel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ea_email">Encargado del área de bienestar - Email</label>
                    <input type="email" class="form-control @error('ea_email') is-invalid @enderror" id="ea_email"
                        name="ea_email" placeholder="alguien@example.com" value="{{ $entity->ea_email }}">
                    @error('ea_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="ea_cargo">Encargado del área de bienestar - Cargo</label>
                    <input type="text" class="form-control @error('ea_cargo') is-invalid @enderror" id="ea_cargo"
                        name="ea_cargo" placeholder="Introduce el cargo" value="{{ $entity->ea_cargo }}">
                    @error('ea_cargo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ea_observaciones">Encargado del área de bienestar - Observaciones</label>
                    <input type="text" class="form-control @error('ea_observaciones') is-invalid @enderror"
                        id="ea_observaciones" name="ea_observaciones" placeholder="Introduce una observación"
                        value="{{ $entity->ea_observaciones }}">
                    @error('ea_observaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="at_nombres">Encargado del área de tesoreria - Nombre y apellido</label>
                    <input type="text" class="form-control @error('at_nombres') is-invalid @enderror" id="at_nombres"
                        name="at_nombres" placeholder="Introduce los nombres" value="{{ $entity->at_nombres }}">
                    @error('at_nombres')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="at_cel">Encargado del área de tesoreria - Celular</label>
                    <input type="number" class="form-control @error('at_cel') is-invalid @enderror" id="at_cel"
                        name="at_cel" placeholder="Introduce el número" value="{{ $entity->at_cel }}">
                    @error('at_cel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="at_email">Encargado del área de tesoreria - Email</label>
                    <input type="email" class="form-control @error('at_email') is-invalid @enderror" id="at_email"
                        name="at_email" placeholder="alguien@example.com" value="{{ $entity->at_email }}">
                    @error('at_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="at_cargo">Encargado del área de tesorería - Cargo</label>
                    <input type="text" class="form-control @error('at_cargo') is-invalid @enderror" id="at_cargo"
                        name="at_cargo" placeholder="Introduce el cargo" value="{{ $entity->at_cargo }}">
                    @error('at_cargo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-12">
                    <label for="at_observaciones">Encargado del área de tesorería - Observaciones</label>
                    <textarea class="form-control @error('at_observaciones') is-invalid @enderror" id="at_observaciones"
                        name="at_observaciones" placeholder="Introduce alguna observación" rows="3">{{ $entity->at_observaciones }}</textarea>
                    @error('at_observaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="codigo">¿Autorizan código?</label>
                    <select class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo">
                        <option value="" disabled {{ old('codigo', $entity->codigo) == null ? 'selected' : '' }}>
                            Seleccione una opción
                        </option>
                        <option value="Si" {{ old('codigo', $entity->codigo) == 'Si' ? 'selected' : '' }}>
                            Si
                        </option>
                        <option value="No" {{ old('codigo', $entity->codigo) == 'No' ? 'selected' : '' }}>
                            No
                        </option>
                    </select>
                    @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="1_1">¿Permiten 1 a 1?</label>
                    <select class="form-control @error('1_1') is-invalid @enderror" id="1_1" name="1_1">
                        <option value="" disabled {{ old('1_1', $entity->{'1_1'}) == null ? 'selected' : '' }}>
                            Seleccione una opción
                        </option>
                        <option value="Si" {{ old('1_1', $entity->{'1_1'}) == 'Si' ? 'selected' : '' }}>Si
                        </option>
                        <option value="No" {{ old('1_1', $entity->{'1_1'}) == 'No' ? 'selected' : '' }}>No
                        </option>
                    </select>
                    @error('1_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ruta">Ruta</label>
                    <input type="text" class="form-control @error('ruta') is-invalid @enderror" id="ruta"
                        name="ruta" placeholder="Introduce la ruta" value="{{ $entity->ruta }}">
                    @error('ruta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="zona">Zona</label>
                    <input type="text" class="form-control @error('zona') is-invalid @enderror" id="zona"
                        name="zona" placeholder="Introduce la zona" value="{{ $entity->zona }}">
                    @error('zona')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="codigo_postal">Código postal</label>
                    <input type="number" class="form-control @error('codigo_postal') is-invalid @enderror"
                        id="codigo_postal" name="codigo_postal" placeholder="Introduce el código postal"
                        value="{{ $entity->codigo_postal }}">
                    @error('codigo_postal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="afiliados_planta">Afiliados planta</label>
                    <input type="text" class="form-control @error('afiliados_planta') is-invalid @enderror"
                        id="afiliados_planta" name="afiliados_planta" placeholder="Introduce los afiliados"
                        value="{{ $entity->afiliados_planta }}">
                    @error('afiliados_planta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="afiliados_contratistas">Afiliados contratistas</label>
                    <input type="text" class="form-control @error('afiliados_contratistas') is-invalid @enderror"
                        id="afiliados_contratistas" name="afiliados_contratistas"
                        placeholder="Introduce el número de afiliados" value="{{ $entity->afiliados_contratistas }}">
                    @error('afiliados_contratistas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="historial_afiliados">Historial de afiliados</label>
                    <input type="text" class="form-control @error('historial_afiliados') is-invalid @enderror"
                        id="historial_afiliados" name="historial_afiliados"
                        placeholder="Introduce el historial de afiliados" value="{{ $entity->historial_afiliados }}">
                    @error('historial_afiliados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="c_codigo">¿Con código?</label>
                    <select class="form-control @error('c_codigo') is-invalid @enderror" id="c_codigo" name="c_codigo">
                        <option value="" disabled
                            {{ old('c_codigo', $entity->c_codigo) === null ? 'selected' : '' }}>Seleccione una opción
                        </option>
                        <option value="Si" {{ old('c_codigo', $entity->c_codigo) == 'Si' ? 'selected' : '' }}>Si
                        </option>
                        <option value="No" {{ old('c_codigo', $entity->c_codigo) == 'No' ? 'selected' : '' }}>No
                        </option>
                    </select>
                    @error('c_codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="apertura">¿Apertura?</label>
                    <select class="form-control @error('apertura') is-invalid @enderror" id="apertura" name="apertura">
                        <option value="" disabled
                            {{ old('apertura', $entity->apertura) === null ? 'selected' : '' }}>Seleccione una opción
                        </option>
                        <option value="Si" {{ old('apertura', $entity->apertura) == 'Si' ? 'selected' : '' }}>Si
                        </option>
                        <option value="No" {{ old('apertura', $entity->apertura) == 'No' ? 'selected' : '' }}>No
                        </option>
                    </select>
                    @error('apertura')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="observaciones_c">Observaciones - Otros contactos</label>
                    <textarea class="form-control @error('observaciones_c') is-invalid @enderror" id="observaciones_c"
                        name="observaciones_c" rows="3">{{ $entity->observaciones_c }}</textarea>
                    @error('observaciones_c')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <a href="{{ route('entidades.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left mr-2"></i> Regresar
            </a>
            <button type="submit" type="button" class="btn btn-success">
                <i class="fas fa-save mr-2"></i> Guardar entidad
            </button>
        </form>
        <br><br><br>
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

        $('#department').change(function() {
            let departmentId = $(this).val();
            let citySelect = $('#ciudad_expedicion');
            citySelect.empty();
            citySelect.append('<option value="">Selecciona la ciudad</option>');

            if (departmentId) {
                $.ajax({
                    url: `/get-cities/${departmentId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, city) {
                            citySelect.append(
                                `<option value="${city.id}">${city.name}</option>`);
                        });
                    },
                    error: function() {
                        alert('Hubo un error al cargar las ciudades.');
                    }
                });
            }
        });
    </script>
@stop
