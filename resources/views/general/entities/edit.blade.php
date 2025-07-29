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
                                id="sucursal" name="sucursal" value="{{ $entity['sucursal'] }}" placeholder="0000000"
                                required>
                            @error('sucursal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="n-apertura">Nombre de quien apertura <span class="required">*</span></label>
                            <select class="form-control @error('n-apertura') is-invalid @enderror" id="n-apertura"
                                name="n-apertura" required>
                                <option value="" disabled selected>Seleccione un departamento</option>
                                @foreach ($asesors as $asesor)
                                    <option value="{{ $asesor->id }}">{{ $asesor->name }}</option>
                                @endforeach
                                @error('n-apertura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha-apertura">Fecha apertura <span class="required">*</span></label>
                            <input type="date" class="form-control @error('fecha-apertura') is-invalid @enderror"
                                id="fecha-apertura" name="fecha-apertura">
                            @error('fecha-apertura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="t-empresa">Tipo de empresa <span class="required">*</span></label>
                            <select class="form-control @error('t-empresa') is-invalid @enderror" id="t-empresa"
                                name="t-empresa" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Publica">Publica</option>
                                <option value="Privada">Privada</option>
                            </select>
                            @error('t-empresa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="direccion">Dirección</label>
                            <input type="number" class="form-control @error('direccion') is-invalid @enderror"
                                id="direccion" name="direccion" placeholder="Introduce la dirección">
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="department">Departamento <span class="required">*</span></label>
                            <select class="form-control @error('department') is-invalid @enderror" id="department"
                                name="department" required>
                                <option value="" disabled selected>Seleccione un departamento</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id_departamento }}">{{ $department->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="ciudad_expedicion">Ciudad <span class="required">*</span></label>
                            <select class="form-control @error('ciudad_expedicion') is-invalid @enderror"
                                id="ciudad_expedicion" name="ciudad_expedicion" required>
                                <option value="" disabled selected>Seleccione una ciudad</option>
                            </select>
                            @error('ciudad_expedicion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="PBX">PBX</label>
                            <input type="text" class="form-control @error('PBX') is-invalid @enderror" id="PBX"
                                name="PBX" placeholder="---">
                            @error('PBX')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="n-empleados">Número de empleados de planta</label>
                            <input type="number" class="form-control @error('n-empleados') is-invalid @enderror"
                                id="n-empleados" name="n-empleados" placeholder="00">
                            @error('n-empleados')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="n-contratistas">Número de empleados contratistas</label>
                            <input type="number" class="form-control @error('n-contratistas') is-invalid @enderror"
                                id="n-contratistas" name="n-contratistas" placeholder="00">
                            @error('n-contratistas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="p-salario">Número de personas que ganan salario mínimo</label>
                            <input type="number" class="form-control @error('p-salario') is-invalid @enderror"
                                id="p-salario" name="p-salario" placeholder="00">
                            @error('p-salario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="n-sedes">Número de sedes</label>
                            <input type="number" class="form-control @error('n-sedes') is-invalid @enderror"
                                id="n-sedes" name="n-sedes" placeholder="00">
                            @error('n-sedes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="t-orden-empresa">Tipo de orden de empresa</label>
                            <select class="form-control @error('sucursal') is-invalid @enderror" id="sucursal"
                                name="sucursal">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="publica">Publica</option>
                                <option value="privada">Privada</option>
                            </select>
                            @error('sucursal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="arl-empresa">ARL de la empresa</label>
                            <input type="number" class="form-control @error('arl-empresa') is-invalid @enderror"
                                id="arl-empresa" name="arl-empresa" placeholder="Escribe la ARL">
                            @error('arl-empresa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="m-hibrido">Modalidad híbrida</label>
                            <select class="form-control @error('m-hibrido') is-invalid @enderror" id="m-hibrido"
                                name="m-hibrido">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Publica">Si</option>
                                <option value="Privada">No</option>
                            </select>
                            @error('m-hibrido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="d-asistencia">Dinamica de asistencia</label>
                            <input type="text" class="form-control @error('d-asistencia') is-invalid @enderror"
                                id="d-asistencia" name="d-asistencia">
                            @error('d-asistencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="d-numero-personas">Día de la semana donde hay mayor número de personas en la
                                entidad</label>
                            <input type="text" class="form-control @error('d-numero-personas') is-invalid @enderror"
                                id="d-numero-personas" name="d-numero-personas" placeholder="Introduce el dia">
                            @error('d-numero-personas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="v-salario-minimo">Valor del salario mínimo en la entidad</label>
                            <input type="number" class="form-control @error('v-salario-minimo') is-invalid @enderror"
                                id="v-salario-minimo" name="v-salario-minimo" placeholder="COP">
                            @error('v-salario-minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="v-salario-maximo">Valor del salario maximo en la entidad</label>
                            <input type="number" class="form-control @error('v-salario-maximo') is-invalid @enderror"
                                id="v-salario-maximo" name="v-salario-maximo" placeholder="COP">
                            @error('v-salario-maximo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="pago-nomina">Pago de nómina </label>
                            <select class="form-control @error('pago-nomina') is-invalid @enderror" id="pago-nomina"
                                name="pago-nomina">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="quincenal">Quincenal</option>
                                <option value="mensual">Mensual</option>
                            </select>
                            @error('pago-nomina')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="m-socializacion">Metodología de socialización beneficio</label>
                            <input type="text" class="form-control @error('m-socializacion') is-invalid @enderror"
                                id="m-socializacion" name="m-socializacion" placeholder="Introduce la metodología">
                            @error('m-socializacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tthh-nombres">Director de TTHH - Nombres y apellidos</label>
                            <input type="text" class="form-control @error('tthh-nombres') is-invalid @enderror"
                                id="tthh-nombres" name="tthh-nombres" placeholder="Introduce los nombres y apellidos">
                            @error('tthh-nombres')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tthh-cel1">Director de TTHH - celular 1</label>
                            <input type="number" class="form-control @error('tthh-cel1') is-invalid @enderror"
                                id="tthh-cel1" name="tthh-cel1" placeholder="Número de celular 1">
                            @error('tthh-cel1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tthh-cel2">Director de TTHH - Celular 2</label>
                            <input type="number" class="form-control @error('tthh-cel2') is-invalid @enderror"
                                id="tthh-cel2" name="tthh-cel2" placeholder="Número de celular 2">
                            @error('tthh-cel2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tthh-cel3">Director de TTHH - celular 3</label>
                            <input type="number" class="form-control @error('tthh-cel3') is-invalid @enderror"
                                id="tthh-cel3" name="tthh-cel3" placeholder="Número de celular 3">
                            @error('tthh-cel3')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tthh-email">Director de TTHH - Email</label>
                            <input type="email" class="form-control @error('tthh-email') is-invalid @enderror"
                                id="tthh-email" name="tthh-email" placeholder="alguien@example.com">
                            @error('tthh-email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tthh-cargo">Director de TTHH - cargo</label>
                            <input type="text" class="form-control @error('tthh-cargo') is-invalid @enderror"
                                id="tthh-cargo" name="tthh-cargo">
                            @error('tthh-cargo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tthh-observaciones">Director de TTHH - Observaciones</label>
                            <input type="text" class="form-control @error('tthh-observaciones') is-invalid @enderror"
                                id="tthh-observaciones" name="tthh-observaciones">
                            @error('tthh-observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="area-nomina-nombres">Encargado del área de nómina - Nombres y apellidos</label>
                            <input type="text" class="form-control @error('area-nomina-nombres') is-invalid @enderror"
                                id="area-nomina-nombres" name="area-nomina-nombres"
                                placeholder="Introduce los nombres y apellidos">
                            @error('area-nomina-nombres')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="area-nomina-celular">Encargado del área de nómina - Celular 1</label>
                            <input type="number" class="form-control @error('area-nomina-celular') is-invalid @enderror"
                                id="area-nomina-celular" name="area-nomina-celular" placeholder="Número de celular">
                            @error('area-nomina-celular')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="area-nomina-email">Encargado del área de nómina - Email</label>
                            <input type="email" class="form-control @error('area-nomina-celular') is-invalid @enderror"
                                id="area-nomina-celular" name="area-nomina-celular" placeholder="alguien@example.com">
                            @error('area-nomina-celular')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="area-nomina-cargo">Encargado del área de nómina - Cargo</label>
                            <input type="text" class="form-control @error('area-nomina-cargo') is-invalid @enderror"
                                id="area-nomina-cargo" name="area-nomina-cargo" placeholder="Introduce el cargo">
                            @error('area-nomina-cargo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="area-nomina-observaciones">Encargado del área de nómina - Observaciones</label>
                            <input type="text"
                                class="form-control @error('area-nomina-observaciones') is-invalid @enderror"
                                id="area-nomina-observaciones" name="area-nomina-observaciones"
                                placeholder="Introduce la Observación">
                            @error('area-nomina-observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="observaciones-visado">Observaciones/Proceso de visado/Radicado</label>
                            <input type="text"
                                class="form-control @error('observaciones-visado') is-invalid @enderror"
                                id="observaciones-visado" name="observaciones-visado"
                                placeholder="Introduce el Proceso de visado">
                            @error('observaciones-visado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="archivos-radicacion">Archivos para radicación</label>
                            <input type="text" class="form-control @error('archivos-radicacion') is-invalid @enderror"
                                id="archivos-radicacion" name="archivos-radicacion"
                                placeholder="Introduce los archivos para radicación">
                            @error('archivos-radicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ea-nombres">Encargado del área de bienestar - Nombres y apellidos</label>
                            <input type="text" class="form-control @error('ea-nombres') is-invalid @enderror"
                                id="ea-nombres" name="ea-nombres" placeholder="Introduce los nombres y apellidos">
                            @error('ea-nombres')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ea-cel">Encargado del área de bienestar - Celular</label>
                            <input type="number" class="form-control @error('ea-cel') is-invalid @enderror"
                                id="ea-cel" name="ea-cel" placeholder="Introduce el número">
                            @error('ea-cel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ea-email">Encargado del área de bienestar - Email</label>
                            <input type="email" class="form-control @error('ea-email') is-invalid @enderror"
                                id="ea-email" name="ea-email" placeholder="alguien@example.com">
                            @error('ea-email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ea-cargo">Encargado del área de bienestar - Cargo</label>
                            <input type="text" class="form-control @error('ea-cargo') is-invalid @enderror"
                                id="ea-cargo" name="ea-cargo" placeholder="Introduce el cargo">
                            @error('ea-cargo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ea-observaciones">Encargado del área de bienestar - Observaciones</label>
                            <input type="email" class="form-control @error('ea-observaciones') is-invalid @enderror"
                                id="ea-observaciones" name="ea-observaciones" placeholder="Introduce una observación">
                            @error('ea-observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="at-cargo">Encargado del área de tesoreria - Cargo</label>
                            <input type="text" class="form-control @error('at-cargo') is-invalid @enderror"
                                id="at-cargo" name="at-cargo" placeholder="Introduce el cargo">
                            @error('at-cargo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="at-observaciones">Encargado del área de tesorería - Observaciones</label>
                            <textarea class="form-control @error('at-observaciones') is-invalid @enderror" id="at-observaciones"
                                name="at-observaciones" placeholder="Introduce alguna observación" rows="3"></textarea>
                            @error('at-observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="observaciones-c">Observaciones - Otros contactos</label>
                            <textarea class="form-control @error('observaciones-c') is-invalid @enderror" id="observaciones-c"
                                name="observaciones-c" rows="3"></textarea>
                            @error('observaciones-c')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="codigo">¿Autorizan código?</label>
                            <select class="form-control @error('codigo') is-invalid @enderror" id="codigo"
                                name="codigo">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="1-1">¿Permiten 1 a 1?</label>
                            <select class="form-control @error('1-1') is-invalid @enderror" id="1-1"
                                name="1-1">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            @error('1-1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ruta">Ruta</label>
                            <input type="number" class="form-control @error('ruta') is-invalid @enderror"
                                id="ruta" name="ruta" placeholder="Introduce la ruta">
                            @error('ruta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="zona">Zona</label>
                            <input type="number" class="form-control @error('zona') is-invalid @enderror"
                                id="zona" name="zona" placeholder="Introduce la zona">
                            @error('zona')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="codigo-postal">Código postal</label>
                            <input type="number" class="form-control @error('codigo-postal') is-invalid @enderror"
                                id="codigo-postal" name="codigo-postal" placeholder="Introduce el código postal">
                            @error('codigo-postal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="afiliados-planta">Afiliados planta</label>
                            <input type="number" class="form-control @error('afiliados-planta') is-invalid @enderror"
                                id="afiliados-planta" name="afiliados-planta">
                            @error('afiliados-planta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="afiliados-contratistas">Afiliados contratistas</label>
                            <input type="number" class="form-control @error('sucursal') is-invalid @enderror"
                                id="afiliados-contratistas" name="afiliados-contratistas"
                                placeholder="Introduce el número de afiliados">
                            @error('afiliados-contratistas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="historial-afiliados">Historial de afiliados</label>
                            <input type="text" class="form-control @error('historial-afiliados') is-invalid @enderror"
                                id="historial-afiliados" name="historial-afiliados"
                                placeholder="Introduce el historial de afiliados">
                            @error('historial-afiliados')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="apertura">¿Apertura?</label>
                            <select class="form-control @error('apertura') is-invalid @enderror" id="apertura"
                                name="apertura">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            @error('apertura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="c-codigo">¿Con código?</label>
                            <select class="form-control @error('c-codigo') is-invalid @enderror" id="c-codigo"
                                name="c-codigo">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            @error('c-codigo')
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
