@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Editar usuario</h1>
@stop

@section('content')
    <p>En este modulo puedes editar los registros seleccionados.</p>
    <br><br><br>

    <div class="container">
        <form autocomplete="off" action="{{ route('usuarios.update', $usuario['id']) }}" method="post">
            @method('PUT')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Nombres y Apellidos <span class="required">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" placeholder="Introduce tus nombres y Apellidos" value="{{ $usuario['name'] }}"
                        required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="document">Documento <span class="required">*</span></label>
                    <input type="number" class="form-control @error('document') is-invalid @enderror" id="document"
                        name="document" placeholder="Introduce tu número de documento" value="{{ $usuario['document'] }}"
                        required>
                    @error('document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Celular <span class="required">*</span></label>
                    <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone"
                        name="phone" placeholder="Introduce tu número de celular" value="{{ $usuario['phone'] }}"
                        required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Correo electrónico <span class="required">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="Introduce tu correo electrónico" autocomplete="new-email"
                        value="{{ $usuario['email'] }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="emailConfirm">Confirmación correo electrónico <span class="required">*</span></label>
                    <input type="email" class="form-control @error('emailConfirm') is-invalid @enderror" id="emailConfirm"
                        name="emailConfirm" placeholder="Confirma tu correo electrónico"
                        value="{{ $usuario['emailConfirm'] }}" required>
                    @error('emailConfirm')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="birthdate">Fecha de nacimiento <span class="required">*</span></label>
                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate"
                        name="birthdate" placeholder="Introduce tu fecha de nacimiento" value="{{ $usuario['birthdate'] }}"
                        required>
                    @error('birthdate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Introduce tu contraseña" autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="role_id">Rol <span class="required">*</span></label>
                @php
                    $userRoleId = optional($usuario->roles->first())->id;
                @endphp
            
                <select id="role_id" name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $userRoleId == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


                <div id="permisos_empresa_container" class="col-md-6" style="display: none; margin-top: 32px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="habilitar_permisos_empresa"
                            id="habilitar_permisos_empresa" {{ $usuario->permisos_entidades == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="habilitar_permisos_empresa">
                            Habilitar permisos empresa
                        </label>
                    </div>
                </div>

                <div class="form-group input-entities col-md-6">
                    <label for="empresas">Empresas asignadas</label>
                    <select id="empresas" name="empresas[]"
                        class="form-control select2 @error('empresas') is-invalid @enderror" multiple required>
                        @foreach ($entities as $entity)
                            <option value="{{ $entity->id }}"
                                {{ in_array((string) $entity->id, json_decode($usuario->empresas, true) ?? []) ? 'selected' : '' }}>
                                {{ $entity->cnitpagador . ' - ' . $entity->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('empresas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group"
                style="background-color: #fff8e1; border: 1px solid #f0ad4e; padding: 12px; border-radius: 8px;">
                <label for="ambiente" style="color: #a05d00; font-weight: bold;">Ambiente</label><br>
                <input type="checkbox" id="ambiente" name="ambiente" class="js-switch" data-color="#f0ad4e"
                    {{ $usuario->ambiente == '1' ? 'checked' : '' }}>
                <span id="ambiente-label" style="margin-left: 8px; color: #a05d00;">Sandbox (Por defecto)</span>
            </div>

            <br><br>
            <div class="inputs-sales mb-3">
                <div style="display: flex; flex-wrap: wrap; gap: 20px;">

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="name_entity"
                            {{ $usuario->name_entity == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Nombre</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="nemo"
                            {{ $usuario->nemo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Abreviatura</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="cnitpagador"
                            {{ $usuario->cnitpagador == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">NIT</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="sucursal"
                            {{ $usuario->sucursal == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Sucursal</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_apertura"
                            {{ $usuario->n_apertura == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Nombre quien apertura</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="fecha_apertura"
                            {{ $usuario->fecha_apertura == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Fecha de apertura</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="t_empresa"
                            {{ $usuario->t_empresa == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Tipo de empresa</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="direccion"
                            {{ $usuario->direccion == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Dirección</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="department"
                            {{ $usuario->department == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Departamento</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ciudad_expedicion"
                            {{ $usuario->ciudad_expedicion == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Ciudad de expedición</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="pbx"
                            {{ $usuario->pbx == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">PBX</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_empleados"
                            {{ $usuario->n_empleados == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Número de empleados de planta</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_contratistas"
                            {{ $usuario->n_contratistas == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Número de empleados contratistas</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="p_salario"
                            {{ $usuario->p_salario == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Número de empleados que ganan el salario mínimo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_sedes"
                            {{ $usuario->n_sedes == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Número de sedes</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="t_orden_empresa"
                            {{ $usuario->t_orden_empresa == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Tipo de orden empresa</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="arl_empresa"
                            {{ $usuario->arl_empresa == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">ARL empresa</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="m_hibrido"
                            {{ $usuario->m_hibrido == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Modalidad híbrida</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="d_asistencia"
                            {{ $usuario->d_asistencia == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Dínamica de asistencia</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="d_numero_personas"
                            {{ $usuario->d_numero_personas == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Día de la semana donde hay mayor número de personas</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="v_salario_minimo"
                            {{ $usuario->v_salario_minimo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Valor del salario mínimo en la entidad</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="v_salario_maximo"
                            {{ $usuario->v_salario_maximo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Valor del salario máximo en la entidad</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="pago_nomina"
                            {{ $usuario->pago_nomina == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Pago nómina</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="m_socializacion"
                            {{ $usuario->m_socializacion == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Metodología de socialización beneficio</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_nombres"
                            {{ $usuario->tthh_nombres == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Director de TTHH - Nombres y apellidos</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_cel1"
                            {{ $usuario->tthh_cel1 == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Director de TTHH - Celular 1</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_cel2"
                            {{ $usuario->tthh_cel2 == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Director de TTHH - Celular 2</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_cel3"
                            {{ $usuario->tthh_cel3 == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Director de TTHH - Celular 3</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_email"
                            {{ $usuario->tthh_email == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Director de TTHH - Email</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="area_nomina_cargo"
                            {{ $usuario->area_nomina_cargo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Director de TTHH - Cargo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="area_nomina_observaciones"
                            {{ $usuario->area_nomina_observaciones == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Director de TTHH - Observaciones</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="observaciones_visado"
                            {{ $usuario->observaciones_visado == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Observaciones / Proceso visado</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="archivos_radicacion"
                            {{ $usuario->archivos_radicacion == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Archivos para radicación</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_nombres"
                            {{ $usuario->ea_nombres == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de bienestar - Nombres y apellidos</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_cel"
                            {{ $usuario->ea_cel == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de bienestar - Celular</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_email"
                            {{ $usuario->ea_email == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de bienestar - Email</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_cargo"
                            {{ $usuario->ea_cargo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de bienestar - Cargo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_observaciones"
                            {{ $usuario->ea_observaciones == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de bienestar - Observaciones</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_nombres"
                            {{ $usuario->at_nombres == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de tesoreria - Nombre y apellido</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_cel"
                            {{ $usuario->at_cel == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de tesoreria - Celular</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_email"
                            {{ $usuario->at_email == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de tesoreria - Email</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_cargo"
                            {{ $usuario->at_cargo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de tesoreria - Cargo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_observaciones"
                            {{ $usuario->at_observaciones == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Encargado del área de tesorería - Observaciones</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="codigo"
                            {{ $usuario->codigo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">¿Autorizan código?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="1_1"
                            {{ $usuario->{'1_1'} == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">¿Permiten 1 a 1?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ruta"
                            {{ $usuario->ruta == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Ruta</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="zona"
                            {{ $usuario->zona == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Zona</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="codigo_postal"
                            {{ $usuario->codigo_postal == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Código postal</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="afiliados_planta"
                            {{ $usuario->afiliados_planta == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Afiliados planta</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="afiliados_contratistas"
                            {{ $usuario->afiliados_contratistas == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Afiliados contratistas</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="historial_afiliados"
                            {{ $usuario->historial_afiliados == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Historial de afiliados</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="apertura"
                            {{ $usuario->apertura == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">¿Con código?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="c_codigo"
                            {{ $usuario->c_codigo == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">¿Apertura?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="observaciones_c"
                            {{ $usuario->observaciones_c == '1' ? 'checked' : '' }}>
                        <label class="form-check-label">Observaciones - otros contactos</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save mr-2"></i>Actualizar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left mr-2"></i> Regresar
            </a>
        </form>
    </div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .select2-selection__choice {
            max-width: 250px;
            /* Ajusta según tu diseño */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
            vertical-align: middle;
            font-size: 12px;
        }

        .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .select2-selection__choice {
            color: #000 !important;
            background-color: #e0e0e0;
            border-color: #aaa;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('input').attr('autocomplete', 'off');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_id');
            const salesInputs = document.querySelector('.inputs-sales');
            const inputEntities = document.querySelector('.input-entities');
            const empresasSelect = document.getElementById('empresas');
            const $empresas = $('#empresas');
            const checkbox = document.getElementById('ambiente');
            const label = document.getElementById('ambiente-label');
            const permisosContainer = document.getElementById("permisos_empresa_container");

            roleSelect.addEventListener("change", function() {
                const selectedText = roleSelect.options[roleSelect.selectedIndex].text;

                if (selectedText.toLowerCase() === "jefe de ventas") {
                    permisosContainer.style.display = "block";
                } else {
                    permisosContainer.style.display = "none";
                    document.getElementById("habilitar_permisos_empresa").checked = false; // desmarcar
                }
            });

            // Función para saber si es ventas
            function isVentas() {
                return roleSelect.options[roleSelect.selectedIndex].text.trim().toLowerCase() === 'ventas';
            }

            // Función para aplicar visibilidad y atributos
            function toggleEmpresas() {
                if (isVentas()) {
                    empresasSelect.removeAttribute('disabled');
                    empresasSelect.required = true;
                    inputEntities.style.display = 'block';
                    salesInputs.style.display = 'flex';
                } else {
                    empresasSelect.required = false;
                    empresasSelect.setAttribute('disabled', 'disabled');
                    $empresas.val(null).trigger('change.select2');
                    inputEntities.style.display = 'none';
                    salesInputs.style.display = 'none';
                }
            }

            // Estado inicial (vista edit)
            toggleEmpresas();

            // Inicializamos Select2
            $('.select2').select2({
                placeholder: "Buscar",
                width: '100%'
            });

            // Cambio de rol
            roleSelect.addEventListener('change', function() {
                toggleEmpresas();
            });

            // Cambio de ambiente
            checkbox.addEventListener('change', function() {
                label.textContent = checkbox.checked ? 'Producción' : 'Sandbox';
            });

            // Switches
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function(html) {
                new Switchery(html, {
                    size: 'small',
                    color: '#4CAF50'
                });
            });

            // Antes de enviar el form, quitar required a campos ocultos o deshabilitados
            document.querySelector('form').addEventListener('submit', function() {
                $('select[required]').each(function() {
                    if ($(this).is(':hidden') || this.disabled) {
                        $(this).prop('required', false).removeAttr('required');
                    }
                });
            });
        });
    </script>
@stop
