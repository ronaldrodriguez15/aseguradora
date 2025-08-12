@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Crear usuario</h1>
@stop

@section('content')
    <p>En este módulo puedes crear un nuevo usuario.</p>
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
                        name="document" placeholder="Introduce tu número de documento" required>
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

            <!-- Desplegable para seleccionar rol -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="role_id">Rol <span class="required">*</span></label>
                    <select id="role_id" name="role_id" class="form-control @error('role_id') is-invalid @enderror"
                        required>
                        <option value="">Seleccione un rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group input-entities col-md-6" id="empresas-wrapper" style="display:none;">
                    <label for="empresas">Empresas asignadas</label>
                    <select id="empresas" name="empresas[]"
                        class="form-control select2 @error('empresas') is-invalid @enderror" multiple>
                        @foreach ($entities as $entity)
                            <option value="{{ $entity->id }}">{{ $entity->cnitpagador . ' - ' . $entity->name }}
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
                    {{ old('ambiente', 'sandbox') == 'sandbox' ? '' : 'checked' }}>
                <span id="ambiente-label" style="margin-left: 8px; color: #a05d00;">Sandbox (Por defecto)</span>
            </div>

            <br><br>
            <div class="inputs-sales mb-3">
                <div style="display: flex; flex-wrap: wrap; gap: 20px;">

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="name_entity">
                        <label class="form-check-label">Nombre</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="nemo">
                        <label class="form-check-label">Abreviatura</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="cnitpagador">
                        <label class="form-check-label">NIT</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="sucursal">
                        <label class="form-check-label">Sucursal</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_apertura">
                        <label class="form-check-label">Nombre quien apertura</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="fecha_apertura">
                        <label class="form-check-label">Fecha de apertura</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="t_empresa">
                        <label class="form-check-label">Tipo de empresa</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="direccion">
                        <label class="form-check-label">Dirección</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="department">
                        <label class="form-check-label">Departamento</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ciudad_expedicion">
                        <label class="form-check-label">Ciudad de expedición</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="pbx">
                        <label class="form-check-label">PBX</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_empleados">
                        <label class="form-check-label">Número de empleados de planta</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_contratistas">
                        <label class="form-check-label">Número de empleados contratistas</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="p_salario">
                        <label class="form-check-label">Número de empleados que ganan el salario mínimo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="n_sedes">
                        <label class="form-check-label">Número de sedes</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="t_orden_empresa">
                        <label class="form-check-label">Tipo de orden empresa</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="arl_empresa">
                        <label class="form-check-label">ARL empresa</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="m_hibrido">
                        <label class="form-check-label">Modalidad híbrida</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="d_asistencia">
                        <label class="form-check-label">Dínamica de asistencia</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="d_numero_personas">
                        <label class="form-check-label">Día se la semana donde hay mayor número de personas</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="v_salario_minimo">
                        <label class="form-check-label">Valor del salario mínimo en la entidad</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="v_salario_maximo">
                        <label class="form-check-label">Valor del salario máximo en la entidad</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="pago_nomina">
                        <label class="form-check-label">Pago nómina</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="m_socializacion">
                        <label class="form-check-label">Metodologia de socialización beneficio</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_nombres">
                        <label class="form-check-label">Director de TTHH - Nombres y apellidos</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_cel1">
                        <label class="form-check-label">Director de TTHH - Celular 1</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_cel2">
                        <label class="form-check-label">Director de TTHH - Celular 2</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_cel3">
                        <label class="form-check-label">Director de TTHH - Celular 3</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="tthh_email">
                        <label class="form-check-label">Director de TTHH - Email</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="area_nomina_cargo">
                        <label class="form-check-label">Director de TTHH - Cargo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="area_nomina_observaciones">
                        <label class="form-check-label">Director de TTHH - Observaciones</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="observaciones_visado">
                        <label class="form-check-label">Observaciones / Proceso visado</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="archivos_radicacion">
                        <label class="form-check-label">Archivos para radicación</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_nombres">
                        <label class="form-check-label">Encargado del área de bienestar - Nombres y apellidos</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_cel">
                        <label class="form-check-label">Encargado del área de bienestar - Celular</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_email">
                        <label class="form-check-label">Encargado del área de bienestar - Email</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_cargo">
                        <label class="form-check-label">Encargado del área de bienestar - Cargo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ea_observaciones">
                        <label class="form-check-label">Encargado del área de bienestar - Observaciones</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_nombres">
                        <label class="form-check-label">Encargado del área de tesoreria - Nombre y apellido</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_cel">
                        <label class="form-check-label">Encargado del área de tesoreria - Celular</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_email">
                        <label class="form-check-label">Encargado del área de tesoreria - Email</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_cargo">
                        <label class="form-check-label">Encargado del área de tesoreria - Cargo</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="at_observaciones">
                        <label class="form-check-label">Encargado del área de tesoreria - Observaciones</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="codigo">
                        <label class="form-check-label">¿Autorizan código?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="1_1">
                        <label class="form-check-label">¿Permiten 1 a 1?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="ruta">
                        <label class="form-check-label">Ruta</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="zona">
                        <label class="form-check-label">Zona</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="codigo_postal">
                        <label class="form-check-label">Código postal</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="afiliados_planta">
                        <label class="form-check-label">Afiliados planta</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="afiliados_contratistas">
                        <label class="form-check-label">Afiliados contratistas</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="historial_afiliados">
                        <label class="form-check-label">Historial de afiliados</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="apertura">
                        <label class="form-check-label">¿Con código?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="c_codigo">
                        <label class="form-check-label">¿Apertura?</label>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="js-switch" name="observaciones_c">
                        <label class="form-check-label">Observaciones - otros contactos</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save mr-2"></i>Registrar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left mr-2"></i> Regresar
            </a>
        </form>
        <br>
        <br>
        <br>
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
            const checkbox = document.getElementById('ambiente');
            const label = document.getElementById('ambiente-label');
            const empresasSelect = document.getElementById('empresas');

            $('.select2').select2({
                placeholder: "Buscar",
                width: '100%'
            });

            checkbox.addEventListener('change', function() {
                label.textContent = checkbox.checked ? 'Producción' : 'Sandbox';
            });

            roleSelect.addEventListener('change', function() {
                const selectedOptionText = roleSelect.options[roleSelect.selectedIndex].text.trim()
                    .toLowerCase();
                const isVentas = selectedOptionText === 'ventas';

                inputEntities.style.display = isVentas ? 'block' : 'none';
                salesInputs.style.display = isVentas ? 'flex' : 'none';

                empresasSelect.required = isVentas;
                empresasSelect.disabled = !isVentas;

                if (!isVentas) {
                    $(empresasSelect).val(null).trigger('change');
                }
            });

            roleSelect.dispatchEvent(new Event('change'));

            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function(html) {
                new Switchery(html, {
                    size: 'small',
                    color: '#4CAF50'
                });
            });
        });
    </script>
@stop
