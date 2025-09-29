<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entity;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(50);

        return view('users.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $entities = Entity::where('status', 1)->get();
        $vendedores = User::role('Ventas')
            ->where('status', 1)
            ->get();

        return view('users.create', compact('roles', 'entities', 'vendedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Obtener reglas de validación y mensajes
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();

        // Validar los datos
        $request->validate($rules, $messages);

        // Crear un nuevo usuario
        $user = new User();
        $user->name = $request->name;
        $user->document = $request->document;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;
        $user->password = Hash::make($request->password);
        $user->ambiente = $request->has('ambiente') ? 1 : 0;
        $user->permisos_entidades = $request->has('habilitar_permisos_empresa') ? 1 : 0;

        $user->name_entity = $request->has('name_entity') ? 1 : 0;
        $user->nemo = $request->has('nemo') ? 1 : 0;
        $user->cnitpagador = $request->has('cnitpagador') ? 1 : 0;
        $user->sucursal = $request->has('sucursal') ? 1 : 0;
        $user->n_apertura = $request->has('n_apertura') ? 1 : 0;
        $user->fecha_apertura = $request->has('fecha_apertura') ? 1 : 0;
        $user->t_empresa = $request->has('t_empresa') ? 1 : 0;
        $user->direccion = $request->has('direccion') ? 1 : 0;
        $user->department = $request->has('department') ? 1 : 0;
        $user->ciudad_expedicion = $request->has('ciudad_expedicion') ? 1 : 0;
        $user->pbx = $request->has('pbx') ? 1 : 0;
        $user->n_empleados = $request->has('n_empleados') ? 1 : 0;
        $user->n_contratistas = $request->has('n_contratistas') ? 1 : 0;
        $user->p_salario = $request->has('p_salario') ? 1 : 0;
        $user->n_sedes = $request->has('n_sedes') ? 1 : 0;
        $user->t_orden_empresa = $request->has('t_orden_empresa') ? 1 : 0;
        $user->arl_empresa = $request->has('arl_empresa') ? 1 : 0;
        $user->d_numero_personas = $request->has('d_numero_personas') ? 1 : 0;
        $user->v_salario_minimo = $request->has('v_salario_minimo') ? 1 : 0;
        $user->v_salario_maximo = $request->has('v_salario_maximo') ? 1 : 0;

        // Multiselect en JSON
        $user->empresas = json_encode($request->empresas ?? []);

        // Más switches
        $user->tthh_nombres = $request->has('tthh_nombres') ? 1 : 0;
        $user->tthh_cel1 = $request->has('tthh_cel1') ? 1 : 0;
        $user->tthh_cel2 = $request->has('tthh_cel2') ? 1 : 0;
        $user->tthh_cel3 = $request->has('tthh_cel3') ? 1 : 0;
        $user->tthh_email = $request->has('tthh_email') ? 1 : 0;
        $user->tthh_cargo = $request->has('tthh_cargo') ? 1 : 0;
        $user->tthh_observaciones = $request->has('tthh_observaciones') ? 1 : 0;

        $user->area_nomina_nombres = $request->has('area_nomina_nombres') ? 1 : 0;
        $user->area_nomina_celular = $request->has('area_nomina_celular') ? 1 : 0;
        $user->area_nomina_email = $request->has('area_nomina_email') ? 1 : 0;
        $user->area_nomina_cargo = $request->has('area_nomina_cargo') ? 1 : 0;
        $user->area_nomina_observaciones = $request->has('area_nomina_observaciones') ? 1 : 0;

        $user->observaciones_visado = $request->has('observaciones_visado') ? 1 : 0;
        $user->archivos_radicacion = $request->has('archivos_radicacion') ? 1 : 0;

        $user->ea_nombres = $request->has('ea_nombres') ? 1 : 0;
        $user->ea_cel = $request->has('ea_cel') ? 1 : 0;
        $user->ea_email = $request->has('ea_email') ? 1 : 0;
        $user->ea_cargo = $request->has('ea_cargo') ? 1 : 0;
        $user->ea_observaciones = $request->has('ea_observaciones') ? 1 : 0;

        $user->at_nombres = $request->has('at_nombres') ? 1 : 0;
        $user->at_cel = $request->has('at_cel') ? 1 : 0;
        $user->at_email = $request->has('at_email') ? 1 : 0;
        $user->at_cargo = $request->has('at_cargo') ? 1 : 0;
        $user->at_observaciones = $request->has('at_observaciones') ? 1 : 0;

        $user->observaciones_c = $request->has('observaciones_c') ? 1 : 0;
        $user->codigo = $request->has('codigo') ? 1 : 0;
        $user->{'1_1'} = $request->has('1_1') ? 1 : 0;
        $user->ruta = $request->has('ruta') ? 1 : 0;
        $user->zona = $request->has('zona') ? 1 : 0;
        $user->codigo_postal = $request->has('codigo_postal') ? 1 : 0;
        $user->afiliados_planta = $request->has('afiliados_planta') ? 1 : 0;
        $user->afiliados_contratistas = $request->has('afiliados_contratistas') ? 1 : 0;
        $user->historial_afiliados = $request->has('historial_afiliados') ? 1 : 0;
        $user->apertura = $request->has('apertura') ? 1 : 0;
        $user->c_codigo = $request->has('c_codigo') ? 1 : 0;
        $$user->geolocalizacion = $request->has('geolocalizacion') ? 1 : 0;

        $user->status = 1; // 1: Activo, 0: Inactivo
        $user->save();

        // Asignar el rol al usuario
        if ($request->role_id) {
            $user->assignRole($request->role_id);
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Excelente!!, El usuario ha sido creado.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();
        $entities = Entity::where('status', 1)->get()->groupBy('name');
        $vendedores = User::role('Ventas')->where('status', 1)->get();

        // Decodificar empresas y vendedores en arrays de STRINGS
        $selectedEmpresas   = array_map('strval', json_decode($usuario->empresas, true) ?? []);
        $selectedVendedores = array_map('strval', json_decode($usuario->vendedores_id, true) ?? []);

        return view('users.edit', compact(
            'usuario',
            'roles',
            'entities',
            'vendedores',
            'selectedEmpresas',
            'selectedVendedores'
        ));
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
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();

        $request->validate($rules, $messages);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->document = $request->get('document');
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
        $user->birthdate = $request->get('birthdate');
        $user->password = Hash::make($request->get('password'));
        $user->ambiente = $request->has('ambiente') ? 1 : 0;
        $user->permisos_entidades = $request->has('habilitar_permisos_empresa') ? 1 : 0;

        $user->name_entity = $request->has('name_entity') ? 1 : 0;
        $user->nemo = $request->has('nemo') ? 1 : 0;
        $user->cnitpagador = $request->has('cnitpagador') ? 1 : 0;
        $user->sucursal = $request->has('sucursal') ? 1 : 0;
        $user->n_apertura = $request->has('n_apertura') ? 1 : 0;
        $user->fecha_apertura = $request->has('fecha_apertura') ? 1 : 0;
        $user->t_empresa = $request->has('t_empresa') ? 1 : 0;
        $user->direccion = $request->has('direccion') ? 1 : 0;
        $user->department = $request->has('department') ? 1 : 0;
        $user->ciudad_expedicion = $request->has('ciudad_expedicion') ? 1 : 0;
        $user->pbx = $request->has('pbx') ? 1 : 0;
        $user->n_empleados = $request->has('n_empleados') ? 1 : 0;
        $user->n_contratistas = $request->has('n_contratistas') ? 1 : 0;
        $user->p_salario = $request->has('p_salario') ? 1 : 0;
        $user->n_sedes = $request->has('n_sedes') ? 1 : 0;
        $user->t_orden_empresa = $request->has('t_orden_empresa') ? 1 : 0;
        $user->arl_empresa = $request->has('arl_empresa') ? 1 : 0;
        $user->d_numero_personas = $request->has('d_numero_personas') ? 1 : 0;
        $user->v_salario_minimo = $request->has('v_salario_minimo') ? 1 : 0;
        $user->v_salario_maximo = $request->has('v_salario_maximo') ? 1 : 0;

        // Multiselect en JSON
        $user->empresas = json_encode($request->empresas ?? []);

        // Más switches
        $user->tthh_nombres = $request->has('tthh_nombres') ? 1 : 0;
        $user->tthh_cel1 = $request->has('tthh_cel1') ? 1 : 0;
        $user->tthh_cel2 = $request->has('tthh_cel2') ? 1 : 0;
        $user->tthh_cel3 = $request->has('tthh_cel3') ? 1 : 0;
        $user->tthh_email = $request->has('tthh_email') ? 1 : 0;
        $user->tthh_cargo = $request->has('tthh_cargo') ? 1 : 0;
        $user->tthh_observaciones = $request->has('tthh_observaciones') ? 1 : 0;

        $user->area_nomina_nombres = $request->has('area_nomina_nombres') ? 1 : 0;
        $user->area_nomina_celular = $request->has('area_nomina_celular') ? 1 : 0;
        $user->area_nomina_email = $request->has('area_nomina_email') ? 1 : 0;
        $user->area_nomina_cargo = $request->has('area_nomina_cargo') ? 1 : 0;
        $user->area_nomina_observaciones = $request->has('area_nomina_observaciones') ? 1 : 0;

        $user->observaciones_visado = $request->has('observaciones_visado') ? 1 : 0;
        $user->archivos_radicacion = $request->has('archivos_radicacion') ? 1 : 0;

        $user->ea_nombres = $request->has('ea_nombres') ? 1 : 0;
        $user->ea_cel = $request->has('ea_cel') ? 1 : 0;
        $user->ea_email = $request->has('ea_email') ? 1 : 0;
        $user->ea_cargo = $request->has('ea_cargo') ? 1 : 0;
        $user->ea_observaciones = $request->has('ea_observaciones') ? 1 : 0;

        $user->at_nombres = $request->has('at_nombres') ? 1 : 0;
        $user->at_cel = $request->has('at_cel') ? 1 : 0;
        $user->at_email = $request->has('at_email') ? 1 : 0;
        $user->at_cargo = $request->has('at_cargo') ? 1 : 0;
        $user->at_observaciones = $request->has('at_observaciones') ? 1 : 0;

        $user->observaciones_c = $request->has('observaciones_c') ? 1 : 0;
        $user->codigo = $request->has('codigo') ? 1 : 0;
        $user->{'1_1'} = $request->has('1_1') ? 1 : 0;
        $user->ruta = $request->has('ruta') ? 1 : 0;
        $user->zona = $request->has('zona') ? 1 : 0;
        $user->codigo_postal = $request->has('codigo_postal') ? 1 : 0;
        $user->afiliados_planta = $request->has('afiliados_planta') ? 1 : 0;
        $user->afiliados_contratistas = $request->has('afiliados_contratistas') ? 1 : 0;
        $user->historial_afiliados = $request->has('historial_afiliados') ? 1 : 0;
        $user->vendedores_id = json_encode($request->vendedores);
        $user->apertura = $request->has('apertura') ? 1 : 0;
        $user->c_codigo = $request->has('c_codigo') ? 1 : 0;
        $user->geolocalizacion = $request->has('geolocalizacion') ? 1 : 0;

        $user->status = 1; //1: Activo, 0: Inactivo
        $user->save();

        if ($request->filled('role_id')) {
            $user->syncRoles([$request->get('role_id')]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Excelente!!, El usuario ha sido actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Excelente!!, El usuario ha sido inactivado correctamente.');
    }

    private function getValidationRules()
    {
        return [
            'name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'emailConfirm' => 'required|email|same:email',
            'birthdate' => 'required|date',
            'password' => 'required',
        ];
    }

    private function getValidationMessages()
    {
        return [
            'name.required' => 'El campo Nombres es obligatorio.',
            'document.required' => 'El campo Documento es obligatorio.',
            'phone.required' => 'El campo Celular es obligatorio.',
            'email.required' => 'El campo Correo electrónico es obligatorio.',
            'email.email' => 'El campo Correo electrónico debe ser una dirección de correo válida.',
            'emailConfirm.required' => 'El campo Confirmación correo electrónico es obligatorio.',
            'emailConfirm.email' => 'El campo Confirmación correo electrónico debe ser una dirección de correo válida.',
            'emailConfirm.same' => 'Los correos electrónicos no coinciden.',
            'birthdate.required' => 'El campo Nacimiento es obligatorio.',
            'birthdate.date' => 'El campo Nacimiento debe ser una fecha válida.',
            'password.required' => 'El campo Contraseña es obligatorio.',
        ];
    }
}
