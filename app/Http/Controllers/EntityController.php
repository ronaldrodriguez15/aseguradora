<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\City;
use App\Models\Entity;
use App\Models\Departments;
use App\Exports\EntidadesSeleccionadasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Atmosphere;
use App\Models\User;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $filtroTipo = request()->get('filtro_tipo');
        $buscar = request()->get('buscar');

        if (Auth::user()->hasRole('Ventas')) {
            if (empty($user->empresas)) {
                $query = Entity::whereRaw('0=1'); // vacío
            } else {
                $empresasIds = is_array($user->empresas)
                    ? $user->empresas
                    : json_decode($user->empresas, true);

                $query = Entity::whereIn('id', $empresasIds)
                    ->where('apertura', '!=', 'Si')
                    ->orderBy('status', 'DESC')
                    ->orderBy('created_at', 'DESC');
            }
        } else {
            $query = Entity::where('status', 1);
        }

        if ($filtroTipo && $buscar) {
            $query->where($filtroTipo, 'LIKE', "%{$buscar}%");
        }

        $entities = $query->get();

        $camposEntidad = [
            'name' => 'Nombre de la entidad',
            'nemo' => 'Abreviatura',
            'cnitpagador' => 'NIT o Código',
            'sucursal' => 'Sucursal',
            'n_apertura' => 'Número apertura',
            'fecha_apertura' => 'Fecha apertura',
            't_empresa' => 'Tipo de empresa',
            'direccion' => 'Dirección',
            'ambiente' => 'ambiente',
            'department' => 'Departamento',
            'empresas' => 'empresas',
            'ciudad_expedicion' => 'Ciudad de expedición',
            'pbx' => 'PBX',
            'n_empleados' => 'Número de empleados de planta',
            'n_contratistas' => 'Número de empleados contratista',
            'p_salario' => 'Número de personas que ganan el salario mínimo',
            'n_sedes' => 'Número de sedes',
            'n_sedes' => 'Número de sedes',
            't_orden_empresa' => 'Tipo de orden empresa',
            'arl_empresa' => 'ARL de la empresa',
            'm_hibrido' => 'Modalidad híbrida',
            'd_asistencia' => 'Dínamica de asistencia',
            'd_numero_personas' => 'Día de la semana donde hay mayor número de personas en la entidad',
            'v_salario_minimo' => 'Valor mínimo de la entidad',
            'v_salario_maximo' => 'Valor del salario máximo en la entidad',
            'pago_nomina' => 'Pago de nómina',
            'm_socializacion' => 'Metodología de socialización beneficio',
            'tthh_nombres' => 'Director de TTHH - Nombres y apellidos',
            'tthh_cel1' => 'Director de TTHH - Celular 1',
            'tthh_cel2' => 'Director de TTHH - Celular 2',
            'tthh_cel3' => 'Director de TTHH - Celular 3',
            'tthh_email' => 'Director de TTHH - Email',
            'tthh_cargo' => 'Director de TTHH - Cargo',
            'tthh_observaciones' => 'Director de TTHH - Observaciones',
            'area_nomina_nombres' => 'Encargado del área de nómina - Nombres y apellidos',
            'area_nomina_celular' => 'Encargado del área de nómina - Celular 1',
            'area_nomina_email' => 'Encargado del área de nómina - Email',
            'area_nomina_cargo' => 'Encargado del área de nómina - Cargo',
            'area_nomina_observaciones' => 'Encargado del área de nómina - Observaciones',
            'observaciones_visado' => 'Observaciones / Proceso de visado / Radicado',
            'archivos_radicacion' => 'Archivos para radicación',
            'ea_nombres' => 'Encargado del área de bienestar - Nombres y apellidos',
            'ea_cel' => 'Encargado del área de bienestar - Celular',
            'ea_email' => 'Encargado del área de bienestar - Email',
            'ea_cargo' => 'Encargado del área de bienestar - Cargo',
            'ea_observaciones' => 'Encargado del área de bienestar - Observaciones',
            'at_nombres' => 'Encargado del área de tesorería - Nombre y apellido',
            'at_cel' => 'Encargado del área de tesorería - Celular',
            'at_email' => 'Encargado del área de tesorería - Email',
            'at_cargo' => 'Encargado del área de tesorería - Cargo',
            'at_observaciones' => 'Encargado del área de tesorería - Observaciones',
            'observaciones_c' => 'Observaciones - otros contactos',
            'codigo' => 'Autorizan código',
            '1_1' => 'Permiten 1 a 1',
            'ruta' => 'Ruta',
            'zona' => 'Zona',
            'codigo_postal' => 'Código postal',
            'afiliados_planta' => 'Afiliados planta',
            'afiliados_contratistas' => 'Afiliados contratistas',
            'historial_afiliados' => 'Historial de afiliados',
            'apertura' => 'Apertura',
            'c_codigo' => 'Con código'
        ];

        return view('general.entities.index', compact('entities', 'camposEntidad'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $asesors = Asesor::where('status', 1)->get();
        $departments = Departments::all();

        return view('general.entities.create', compact('asesors', 'departments'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'nemo' => 'required|string',
            'cnitpagador' => 'required',
            'sucursal' => 'required',
        ];
        $messages = [
            'name.required' => 'El campo Nombres es obligatorio.',
            'type_entity.required' => 'El campo Abreviatura es obligatorio.',
            'cnitpagador.required' => 'El campo NIT o código es obligatorio.',
            'sucursal.required' => 'La sucursal es obligatoria.',
        ];

        $request->validate($rules, $messages);

        $entity = new Entity();
        $entity->name = $request->name;
        $entity->nemo = $request->nemo;
        $entity->cnitpagador = $request->cnitpagador;
        $entity->sucursal = $request->sucursal;
        $entity->n_apertura = $request->n_apertura;
        $entity->fecha_apertura = $request->fecha_apertura;
        $entity->t_empresa = $request->t_empresa;
        $entity->direccion = $request->direccion;
        $entity->department = $request->department;
        $entity->ciudad_expedicion = $request->ciudad_expedicion;
        $entity->pbx = $request->pbx;
        $entity->n_empleados = $request->n_empleados;
        $entity->n_contratistas = $request->n_contratistas;
        $entity->p_salario = $request->p_salario;
        $entity->n_sedes = $request->n_sedes;
        $entity->t_orden_empresa = $request->t_orden_empresa;
        $entity->arl_empresa = $request->arl_empresa;
        $entity->m_hibrido = $request->m_hibrido;
        $entity->d_asistencia = $request->d_asistencia;
        $entity->d_numero_personas = $request->d_numero_personas;
        $entity->v_salario_minimo = $request->v_salario_minimo;
        $entity->v_salario_maximo = $request->v_salario_maximo;
        $entity->pago_nomina = $request->pago_nomina;
        $entity->m_socializacion = $request->m_socializacion;
        $entity->tthh_nombres = $request->tthh_nombres;
        $entity->tthh_cel1 = $request->tthh_cel1;
        $entity->tthh_cel2 = $request->tthh_cel2;
        $entity->tthh_cel3 = $request->tthh_cel3;
        $entity->tthh_email = $request->tthh_email;
        $entity->tthh_cargo = $request->tthh_cargo;
        $entity->tthh_observaciones = $request->tthh_observaciones;
        $entity->area_nomina_nombres = $request->area_nomina_nombres;
        $entity->area_nomina_celular = $request->area_nomina_celular;
        $entity->area_nomina_cargo = $request->area_nomina_cargo;
        $entity->area_nomina_email = $request->area_nomina_email;
        $entity->area_nomina_observaciones = $request->area_nomina_observaciones;
        $entity->observaciones_visado = $request->observaciones_visado;
        $entity->archivos_radicacion = $request->archivos_radicacion;
        $entity->ea_nombres = $request->ea_nombres;
        $entity->ea_cel = $request->ea_cel;
        $entity->ea_email = $request->ea_email;
        $entity->ea_cargo = $request->ea_cargo;
        $entity->ea_observaciones = $request->ea_observaciones;
        $entity->at_nombres = $request->at_nombres;
        $entity->at_cel = $request->at_cel;
        $entity->at_email = $request->at_email;
        $entity->at_cargo = $request->at_cargo;
        $entity->at_observaciones = $request->at_observaciones;
        $entity->observaciones_c = $request->observaciones_c;
        $entity->codigo = $request->codigo;
        $entity->{'1_1'} = $request->{'1_1'};
        $entity->ruta = $request->ruta;
        $entity->zona = $request->zona;
        $entity->codigo_postal = $request->codigo_postal;
        $entity->afiliados_planta = $request->afiliados_planta;
        $entity->afiliados_contratistas = $request->afiliados_contratistas;
        $entity->historial_afiliados = $request->historial_afiliados;
        $entity->apertura = $request->apertura;
        $entity->c_codigo = $request->c_codigo;
        $entity->status = 1; //Activo 1, Inactivo 2
        $entity->save();

        return redirect()->route('entidades.index')->with('success', 'Excelente!!, La entidad ha sido creada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!is_numeric($id)) {
            abort(404);
        }

        $entity = Entity::findOrFail($id);

        return view('general.entities.show', compact('entity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $entity = Entity::find($id);
        $asesors = Asesor::where('status', 1)->get();
        $departments = Departments::all();

        return view('general.entities.edit', compact('entity', 'asesors', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $entity = Entity::find($id);
        $entity->nemo = $request->nemo;
        $entity->sucursal = $request->sucursal;
        $entity->n_apertura = $request->n_apertura;
        $entity->fecha_apertura = $request->fecha_apertura;
        $entity->t_empresa = $request->t_empresa;
        $entity->direccion = $request->direccion;
        $entity->department = $request->department;
        $entity->ciudad_expedicion = $request->ciudad_expedicion;
        $entity->pbx = $request->pbx;
        $entity->n_empleados = $request->n_empleados;
        $entity->n_contratistas = $request->n_contratistas;
        $entity->p_salario = $request->p_salario;
        $entity->n_sedes = $request->n_sedes;
        $entity->t_orden_empresa = $request->t_orden_empresa;
        $entity->arl_empresa = $request->arl_empresa;
        $entity->m_hibrido = $request->m_hibrido;
        $entity->d_asistencia = $request->d_asistencia;
        $entity->d_numero_personas = $request->d_numero_personas;
        $entity->v_salario_minimo = $request->v_salario_minimo;
        $entity->v_salario_maximo = $request->v_salario_maximo;
        $entity->pago_nomina = $request->pago_nomina;
        $entity->m_socializacion = $request->m_socializacion;
        $entity->tthh_nombres = $request->tthh_nombres;
        $entity->tthh_cel1 = $request->tthh_cel1;
        $entity->tthh_cel2 = $request->tthh_cel2;
        $entity->tthh_cel3 = $request->tthh_cel3;
        $entity->tthh_email = $request->tthh_email;
        $entity->tthh_cargo = $request->tthh_cargo;
        $entity->tthh_observaciones = $request->tthh_observaciones;
        $entity->area_nomina_nombres = $request->area_nomina_nombres;
        $entity->area_nomina_celular = $request->area_nomina_celular;
        $entity->area_nomina_cargo = $request->area_nomina_cargo;
        $entity->area_nomina_email = $request->area_nomina_email;
        $entity->area_nomina_observaciones = $request->area_nomina_observaciones;
        $entity->observaciones_visado = $request->observaciones_visado;
        $entity->archivos_radicacion = $request->archivos_radicacion;
        $entity->ea_nombres = $request->ea_nombres;
        $entity->ea_cel = $request->ea_cel;
        $entity->ea_email = $request->ea_email;
        $entity->ea_cargo = $request->ea_cargo;
        $entity->ea_observaciones = $request->ea_observaciones;
        $entity->at_nombres = $request->at_nombres;
        $entity->at_cel = $request->at_cel;
        $entity->at_email = $request->at_email;
        $entity->at_cargo = $request->at_cargo;
        $entity->at_observaciones = $request->at_observaciones;
        $entity->observaciones_c = $request->observaciones_c;
        $entity->codigo = $request->codigo;
        $entity->{'1_1'} = $request->{'1_1'};
        $entity->ruta = $request->ruta;
        $entity->zona = $request->zona;
        $entity->codigo_postal = $request->codigo_postal;
        $entity->afiliados_planta = $request->afiliados_planta;
        $entity->afiliados_contratistas = $request->afiliados_contratistas;
        $entity->historial_afiliados = $request->historial_afiliados;
        $entity->apertura = $request->apertura;
        $entity->c_codigo = $request->c_codigo;
        $entity->status = 1; //Activo 1, Inactivo 2
        $entity->save();

        return redirect()->route('entidades.index')->with('success', 'El registro ha sido editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entity = Entity::find($id);
        $entity->delete();

        return redirect()->route('entidades.index')->with('success', 'La entidad ha sido eliminada.');
    }

    public function getCitiesByDepartment($departmentId)
    {
        $cities = City::where('id', $departmentId)->get();
        return response()->json($cities);
    }

    public function exportarSeleccionadas(Request $request)
    {
        $ids = $request->input('seleccionados', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No seleccionaste ninguna entidad para exportar.');
        }

        return Excel::download(new EntidadesSeleccionadasExport($ids), 'entidades_seleccionadas.xlsx');
    }

    public function showEntitiesAsign(Request $request)
    {
        $vendedores = User::role('ventas')->get();
        $entities = collect();
        $vendedorSeleccionado = null;

        if ($request->filled('filtro_tipo') && $request->filtro_tipo !== 'all') {
            $vendedorSeleccionado = User::find($request->filtro_tipo);

            if ($vendedorSeleccionado && !empty($vendedorSeleccionado->empresas)) {
                // Decodificar JSON a array
                $empresaIds = json_decode($vendedorSeleccionado->empresas, true);

                // Validar que sea un array y tenga datos
                if (is_array($empresaIds) && count($empresaIds) > 0) {
                    $entities = Entity::whereIn('id', $empresaIds)->get();
                }
            }
        } else {
            // Si no hay filtro o es "all", mostrar todas
            $entities = Entity::all();
        }

        return view('general.entities.entitiesasign', [
            'entities' => $entities,
            'vendedores' => $vendedores,
            'vendedorSeleccionado' => $vendedorSeleccionado
        ]);
    }
}
