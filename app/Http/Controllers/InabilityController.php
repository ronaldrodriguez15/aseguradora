<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Bank;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Inability;
use App\Models\Insurer;
use App\Models\Eps;
use App\Models\Entity;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;

class InabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inabilities = Inability::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('affiliations.inabilities.index', compact('inabilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $insurers = Insurer::where('status', 1)->get();
        $asesors = Asesor::where('status', 1)->get();
        $epss = Eps::where('status', 1)->get();

        return view('affiliations.inabilities.step1', compact('insurers', 'asesors', 'epss'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function formStepTwo(Request $request)
    {
        $rules = [
            'no_solicitud' => 'required',
            'aseguradora' => 'required',
            'no_poliza' => 'required',
            'codigo_asesor' => 'required',
            'nombre_asesor' => 'required',
            'nombre_eps' => 'required',
            'fecha_nacimiento_asesor' => 'required',
            'email_corporativo' => 'required',
            'descuento_eps' => 'required',
            'numero_dias' => 'required',
            'valor_ibc_basico' => 'required',
            'desea_valor' => 'required',
            'valor_adicional' => 'nullable',
            'total' => 'required',
            'amparo_basico' => 'required',
            'val_prevexequial_eclusivo' => 'required',
            'prima_pago_prima_seguro' => 'required',
            'gastos_administrativos' => 'nullable',
            'val_total_desc_mensual' => 'required',
            'tu_pierdes' => 'required',
            'te_pagamos' => 'required',
        ];

        $messages = [
            'no_solicitud.required' => 'El campo número de solicitud es obligatorio.',
            'aseguradora.required' => 'El campo aseguradora es obligatorio.',
            'no_poliza.required' => 'El campo número de póliza es obligatorio.',
            'codigo_asesor.required' => 'El campo código de asesor es obligatorio.',
            'nombre_asesor.required' => 'El campo nombre del asesor es obligatorio.',
            'nombre_eps.required' => 'El campo nombre de la EPS es obligatorio.',
            'fecha_nacimiento_asesor.required' => 'El campo fecha de nacimiento del asesor es obligatorio.',
            'email_corporativo.required' => 'El campo correo corporativo es obligatorio.',
            'descuento_eps.required' => 'El campo descuento EPS es obligatorio.',
            'numero_dias.required' => 'El campo número de días es obligatorio.',
            'valor_ibc_basico.required' => 'El campo valor IBC básico es obligatorio.',
            'desea_valor.required' => 'El campo desea valor es obligatorio.',
            'total.required' => 'El campo total es obligatorio.',
            'amparo_basico.numeric' => 'El campo amparo básico debe ser numérico.',
            'val_prevexequial_eclusivo.required' => 'El campo valor previ-exequial exclusivo es obligatorio.',
            'prima_pago_prima_seguro.required' => 'El campo prima de pago prima de seguro es obligatorio.',
            'val_total_desc_mensual.required' => 'El campo valor total descuento mensual es obligatorio.',
            'tu_pierdes.required' => 'El campo tú pierdes es obligatorio.',
            'te_pagamos.required' => 'El campo te pagamos es obligatorio.',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $inability = new Inability();
            $inability->no_solicitud = $request->no_solicitud;
            $inability->insurer_id = $request->aseguradora;

            $insurer = Insurer::find($request->aseguradora);
            $inability->aseguradora = $insurer->name;

            $inability->no_poliza = $request->no_poliza;
            $inability->fecha_diligenciamiento = Carbon::now();
            $inability->codigo_asesor = $request->codigo_asesor;
            $inability->nombre_asesor = $request->nombre_asesor;
            $inability->nombre_eps = $request->nombre_eps;
            $inability->fecha_nacimiento_asesor = $request->fecha_nacimiento_asesor;
            $inability->email_corporativo = $request->email_corporativo;
            $inability->descuento_eps = $request->descuento_eps;
            $inability->numero_dias = $request->numero_dias;
            $inability->edad = $request->edad;
            $inability->tu_pierdes = $request->tu_pierdes;
            $inability->te_pagamos = $request->te_pagamos;
            $inability->valor_ibc_basico = $request->valor_ibc_basico;
            $inability->desea_valor = $request->desea_valor;
            $inability->valor_adicional = $request->valor_adicional;
            $inability->total = $request->total;
            $inability->amparo_basico = $request->amparo_basico;
            $inability->val_prevexequial_eclusivo = $request->val_prevexequial_eclusivo;
            $inability->prima_pago_prima_seguro = $request->prima_pago_prima_seguro;
            $inability->gastos_administrativos = $request->gastos_administrativos;
            $inability->val_total_desc_mensual = $request->val_total_desc_mensual;
            $inability->save();

            // Guardar el ID en la sesión o en una variable
            $request->session()->put('inability_id', $inability->id);

            $val_total_desc_mensual = $request->val_total_desc_mensual;
            $tu_pierdes = number_format($request->tu_pierdes, 0, ',', '.');
            $te_pagamos = number_format($request->te_pagamos, 0, ',', '.');
            $edad = $request->edad;
            $banks = Bank::where('status', 1)->get();
            $cities = City::where('status', 1)->get();
            $message = "La información se guardó correctamente.";

            return view(
                'affiliations.inabilities.step2',
                compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'banks', 'cities', "message")
            );
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->with('error', 'El correo corporativo ya existe.'); // Aquí controlas el error de duplicado
            }
            return redirect()->back()->with('error', 'Ocurrió un error al guardar la información.');
        }
    }

    public function formStepTree(Request $request)
    {
        // Validación del formulario por backend
        $rules = [
            'forma_pago' => 'required|string|in:debito_automatico,mensual_libranza',
        ];

        $messages = [
            'forma_pago.required' => 'El campo forma de pago es obligatorio.',
            'forma_pago.string' => 'La forma de pago debe ser una cadena de texto.',
            'forma_pago.in' => 'La forma de pago seleccionada no es válida.',
        ];

        $this->validate($request, $rules, $messages);

        // Recuperar el ID del registro desde la sesión
        $inabilityId = $request->session()->get('inability_id');

        $inability = Inability::find($inabilityId);
        $inability->forma_pago = $request->forma_pago;
        $inability->tipo_cuenta = $request->tipo_cuenta;

        if ($request->forma_pago !== 'mensual_libranza') {
            $inability->no_cuenta = $request->no_cuenta;
            $inability->no_cuenta = $request->r_no_cuenta;
            $bank = Bank::find($request->banco);
            $inability->bank_id = $bank->id;
            $inability->banco = $bank->name;
            $inability->ciudad_banco = $request->ciudad_banco;
        }
        $inability->save();

        $val_total_desc_mensual = $inability->val_total_desc_mensual;
        $tu_pierdes = number_format($inability->tu_pierdes, 0, ',', '.');
        $te_pagamos = number_format($inability->te_pagamos, 0, ',', '.');
        $edad = $inability->edad;
        $cities = City::where('status', 1)->get();
        $epss = Eps::where('status', 1)->get();
        $companies = Entity::where('status', 1)->get();
        $message = "La información se guardó correctamente.";

        return view(
            'affiliations.inabilities.step3',
            compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'cities', 'epss', 'companies', 'message')
        );
    }

    public function formStepFour(Request $request)
    {
        // Validación del formulario por backend
        $rules = [
            'primer_apellido' => 'required',
            'segundo_apellido' => 'required',
            'nombres_completos' => 'required',
            'tipo_identificacion' => 'required',
            'no_identificacion' => 'required',
            'ciudad_expedicion' => 'required',
            'genero' => 'required',
            'fecha_nacimiento_asegurado' => 'required|date',
            'direccion_residencia' => 'required',
            'telefono_fijo' => 'required',
            'celular' => 'required',
            'ciudad_residencia' => 'required',
            'fuente_recursos' => 'required',
            'ocupacion_asegurado' => 'required',
            'eps_asegurado' => 'required',
            'entidad_pagadora_sucursal' => 'required',
        ];

        $messages = [
            'primer_apellido.required' => 'El campo primer apellido es obligatorio.',
            'segundo_apellido.required' => 'El campo segundo apellido es obligatorio.',
            'nombres_completos.required' => 'El campo nombres completos es obligatorio.',
            'tipo_identificacion.required' => 'El campo tipo de identificación es obligatorio.',
            'no_identificacion.required' => 'El campo número de identificación es obligatorio.',
            'ciudad_expedicion.required' => 'El campo ciudad de expedición es obligatorio.',
            'genero.required' => 'El campo género es obligatorio.',
            'fecha_nacimiento_asegurado.required' => 'El campo fecha de nacimiento es obligatorio.',
            'direccion_residencia.required' => 'El campo dirección de residencia es obligatorio.',
            'telefono_fijo.required' => 'El campo teléfono fijo es obligatorio.',
            'celular.required' => 'El campo celular es obligatorio.',
            'ciudad_residencia.required' => 'El campo ciudad de residencia es obligatorio.',
            'fuente_recursos.required' => 'El campo fuente de recursos es obligatorio.',
            'ocupacion_asegurado.required' => 'El campo ocupación es obligatorio.',
            'eps_asegurado.required' => 'El campo EPS es obligatorio.',
            'entidad_pagadora_sucursal.required' => 'El campo entidad pagadora sucursal es obligatorio.',
        ];


        $this->validate($request, $rules, $messages);

        // Recuperar el ID del registro desde la sesión
        $inabilityId = $request->session()->get('inability_id');

        $inability = Inability::find($inabilityId);
        $inability->primer_apellido = $request->primer_apellido;
        $inability->segundo_apellido = $request->segundo_apellido;
        $inability->nombres_completos = $request->nombres_completos;
        $inability->tipo_identificacion = $request->tipo_identificacion;
        $inability->no_identificacion = $request->no_identificacion;
        $inability->ciudad_expedicion = $request->ciudad_expedicion;
        $inability->genero = $request->genero;
        $inability->fecha_nacimiento_asegurado = $request->fecha_nacimiento_asegurado;
        $inability->direccion_residencia = $request->direccion_residencia;
        $inability->telefono_fijo = $request->telefono_fijo;
        $inability->celular = $request->celular;
        $inability->ciudad_residencia = $request->ciudad_residencia;
        $inability->fuente_recursos = $request->fuente_recursos;
        $inability->ocupacion_asegurado = $request->ocupacion_asegurado;
        $inability->eps_asegurado = $request->eps_asegurado;
        $inability->entidad_pagadora_sucursal = $request->entidad_pagadora_sucursal;

        $inability->nombres_s1 = $request->no_cuenta;
        $inability->apellidos_s1 = $request->no_cuenta;
        $inability->genero_s1 = $request->no_cuenta;
        $inability->parentesco_s1 = $request->no_cuenta;
        $inability->edad_s1 = $request->no_cuenta;
        $inability->porcentaje_s1 = $request->no_cuenta;
        $inability->tipo_identidad_s1 = $request->no_cuenta;

        $inability->nombres_s2 = $request->no_cuenta;
        $inability->apellidos_s2 = $request->no_cuenta;
        $inability->genero_s2 = $request->no_cuenta;
        $inability->parentesco_s2 = $request->no_cuenta;
        $inability->edad_s2 = $request->no_cuenta;
        $inability->porcentaje_s2 = $request->no_cuenta;
        $inability->tipo_identidad_s2 = $request->no_cuenta;

        $inability->nombres_s3 = $request->no_cuenta;
        $inability->apellidos_s3 = $request->no_cuenta;
        $inability->genero_s3 = $request->no_cuenta;
        $inability->parentesco_s3 = $request->no_cuenta;
        $inability->edad_s3 = $request->no_cuenta;
        $inability->porcentaje_s3 = $request->no_cuenta;
        $inability->tipo_identidad_s3 = $request->no_cuenta;
        $inability->save();

        $val_total_desc_mensual = $request->val_total_desc_mensual;
        $tu_pierdes = number_format($request->tu_pierdes, 0, ',', '.');
        $te_pagamos = number_format($request->te_pagamos, 0, ',', '.');
        $edad = $request->edad;
        $message = "La información se guardó correctamente.";

        return view(
            'affiliations.inabilities.step4',
            compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'message')
        );
    }

    public function formStepFive(Request $request)
    {
        // Validación del formulario por backend
        $rules = [
            'servicios_prevision_exequial' => 'required',
            'beneficiario_diario_inc_temp' => 'required',
            'serv_prevision_exequial_mascotas' => 'required',
            'serv_prevision_salud' => 'required',
            'otro' => 'required',
            'cancer' => 'required',
            'corazon' => 'required',
            'diabetes' => 'required',
            'enf_hepaticas' => 'required',
            'enf_neurologicas' => 'required',
            'pulmones' => 'required',
            'presion_arterial' => 'required',
            'rinones' => 'required',
            'infeccion_vih' => 'required',
            'perdida_funcional_anatomica' => 'required',
            'accidentes_labores_ocupacion' => 'required',
            'hospitalizacion_intervencion_quirurgica' => 'required',
            'enfermedad_diferente' => 'required',
            'enf_cerebrovasculares' => 'required',
            'cirugias' => 'required',
            'alcoholismo' => 'required',
            'tabaquismo' => 'required',
            'enf_congenitas' => 'required',
            'enf_colageno' => 'required',
            'enf_hematologicas' => 'required',
        ];

        $messages = [
            'servicios_prevision_exequial.required' => 'El campo servicios de previsión exequial es obligatorio.',
            'beneficiario_diario_inc_temp.required' => 'El campo beneficiario diario por incapacidad temporal es obligatorio.',
            'serv_prevision_exequial_mascotas.required' => 'El campo servicios de previsión exequial para mascotas es obligatorio.',
            'serv_prevision_salud.required' => 'El campo servicios de previsión de salud es obligatorio.',
            'otro.required' => 'El campo otro es obligatorio.',
            'cancer.required' => 'El campo cáncer es obligatorio.',
            'corazon.required' => 'El campo corazón es obligatorio.',
            'diabetes.required' => 'El campo diabetes es obligatorio.',
            'enf_hepaticas.required' => 'El campo enfermedades hepáticas es obligatorio.',
            'enf_neurologicas.required' => 'El campo enfermedades neurológicas es obligatorio.',
            'pulmones.required' => 'El campo pulmones es obligatorio.',
            'presion_arterial.required' => 'El campo presión arterial es obligatorio.',
            'rinones.required' => 'El campo riñones es obligatorio.',
            'infeccion_vih.required' => 'El campo infección por VIH es obligatorio.',
            'perdida_funcional_anatomica.required' => 'El campo pérdida funcional o anatómica es obligatorio.',
            'accidentes_labores_ocupacion.required' => 'El campo accidentes laborales por ocupación es obligatorio.',
            'hospitalizacion_intervencion_quirurgica.required' => 'El campo hospitalización o intervención quirúrgica es obligatorio.',
            'enfermedad_diferente.required' => 'El campo enfermedad diferente es obligatorio.',
            'enf_cerebrovasculares.required' => 'El campo enfermedades cerebrovasculares es obligatorio.',
            'cirugias.required' => 'El campo cirugías es obligatorio.',
            'alcoholismo.required' => 'El campo alcoholismo es obligatorio.',
            'tabaquismo.required' => 'El campo tabaquismo es obligatorio.',
            'enf_congenitas.required' => 'El campo enfermedades congénitas es obligatorio.',
            'enf_colageno.required' => 'El campo enfermedades de colágeno es obligatorio.',
            'enf_hematologicas.required' => 'El campo enfermedades hematológicas es obligatorio.',
        ];


        $request->validate($rules, $messages);

        // Recuperar el ID del registro desde la sesión
        $inabilityId = $request->session()->get('inability_id');

        $inability = Inability::find($inabilityId);
        $inability->servicios_prevision_exequial = $request->servicios_prevision_exequial;
        $inability->beneficiario_diario_inc_temp = $request->beneficiario_diario_inc_temp;
        $inability->serv_prevision_exequial_mascotas = $request->serv_prevision_exequial_mascotas;
        $inability->serv_prevision_salud = $request->serv_prevision_salud;
        $inability->otro = $request->otro;
        $inability->cual = $request->cual;
        $inability->cancer = $request->cancer;
        $inability->corazon = $request->corazon;
        $inability->diabetes = $request->diabetes;
        $inability->enf_hepaticas = $request->enf_hepaticas;
        $inability->enf_neurologicas = $request->enf_neurologicas;
        $inability->pulmones = $request->pulmones;
        $inability->presion_arterial = $request->presion_arterial;
        $inability->rinones = $request->rinones;
        $inability->infeccion_vih = $request->infeccion_vih;
        $inability->perdida_funcional_anatomica = $request->perdida_funcional_anatomica;
        $inability->accidentes_labores_ocupacion = $request->accidentes_labores_ocupacion;
        $inability->hospitalizacion_intervencion_quirurgica = $request->hospitalizacion_intervencion_quirurgica;
        $inability->enfermedad_diferente = $request->enfermedad_diferente;
        $inability->enf_cerebrovasculares = $request->enf_cerebrovasculares;
        $inability->cirugias = $request->cirugias;
        $inability->alcoholismo = $request->alcoholismo;
        $inability->tabaquismo = $request->tabaquismo;
        $inability->enf_congenitas = $request->enf_congenitas;
        $inability->enf_colageno = $request->enf_colageno;
        $inability->enf_hematologicas = $request->enf_hematologicas;
        $inability->descripcion_de_enfermedades = $request->descripcion_de_enfermedades;
        $inability->save();


        $val_total_desc_mensual = $inability->val_total_desc_mensual;
        $tu_pierdes = number_format($inability->tu_pierdes, 0, ',', '.');
        $te_pagamos = number_format($inability->te_pagamos, 0, ',', '.');
        $edad = $inability->edad;
        $cities = City::where('status', 1)->get();
        $epss = Eps::where('status', 1)->get();
        $companies = Entity::where('status', 1)->get();
        $message = "La información se guardó correctamente.";

        return view(
            'affiliations.inabilities.step5',
            compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'cities', 'epss', 'companies', 'message')
        );
    }

    public function formStepSix(Request $request)
    {
        $rules = [
            'nombres_apellidos_r1' => 'required',
            'telefono_r1' => 'required',
            'entidad_r1' => 'required',
            'seleccion_opcion_r1' => 'required',

            'nombres_apellidos_r2' => 'required',
            'telefono_r2' => 'required',
            'entidad_r2' => 'nullable',
            'seleccion_opcion_r2' => 'required',

            'nombres_apellidos_r3' => 'required',
            'telefono_r3' => 'required',
            'entidad_r3' => 'required',
            'seleccion_opcion_r3' => 'required',

            'tienes_mascotas' => 'required',
            'proteger_mascotas' => 'required',
        ];

        $messages = [
            'nombres_apellidos_r1.required' => 'El nombre y apellido del primer registro es obligatorio.',
            'telefono_r1.required' => 'El teléfono del primer registro es obligatorio.',
            'entidad_r1.required' => 'La entidad del primer registro es obligatoria.',
            'seleccion_opcion_r1.required' => 'Debes seleccionar una opción en el primer registro.',

            'nombres_apellidos_r2.required' => 'El nombre y apellido del segundo registro es obligatorio.',
            'telefono_r2.required' => 'El teléfono del segundo registro es obligatorio.',
            'entidad_r2.required' => 'La entidad del segundo registro es obligatoria.',
            'seleccion_opcion_r2.required' => 'Debes seleccionar una opción en el segundo registro.',

            'nombres_apellidos_r3.required' => 'El nombre y apellido del tercer registro es obligatorio.',
            'telefono_r3.required' => 'El teléfono del tercer registro es obligatorio.',
            'entidad_r3.required' => 'La entidad del tercer registro es obligatoria.',
            'seleccion_opcion_r3.required' => 'Debes seleccionar una opción en el tercer registro.',

            'tienes_mascotas.required' => 'El campo de mascotas es obligatorio.',
            'proteger_mascotas.required_if' => 'Debes especificar cómo proteger a tus mascotas si seleccionaste que tienes mascotas.',
        ];

        $this->validate($request, $rules, $messages);

        // Recuperar el ID del registro desde la sesión
        $inabilityId = $request->session()->get('inability_id');

        $inability = Inability::find($inabilityId);

        // Almacenar datos de r1
        $inability->nombres_apellidos_r1 = $request->nombres_apellidos_r1;
        $inability->telefono_r1 = $request->telefono_r1;
        $inability->entidad_r1 = ($request->seleccion_opcion_r1 === 'si') ? $request->cual_r1 : $request->entidad_r1;

        // Almacenar datos de r2
        $inability->nombres_apellidos_r2 = $request->nombres_apellidos_r2;
        $inability->telefono_r2 = $request->telefono_r2;
        $inability->entidad_r2 = ($request->seleccion_opcion_r2 === 'si') ? $request->cual_r2 : $request->entidad_r2;

        // Almacenar datos de r3
        $inability->nombres_apellidos_r3 = $request->nombres_apellidos_r3;
        $inability->telefono_r3 = $request->telefono_r3;
        $inability->entidad_r3 = ($request->seleccion_opcion_r3 === 'si') ? $request->cual_r3 : $request->entidad_r3;

        // Almacenar información de mascotas
        $inability->tienes_mascotas = $request->tienes_mascotas;
        $inability->proteger_mascotas = $request->proteger_mascotas;

        // Almacenar datos de la mascota 1
        $inability->nombre_m1 = $request->nombre_m1;
        $inability->tipo_m1 = $request->tipo_m1;
        $inability->raza_m1 = $request->raza_m1;
        $inability->color_m1 = $request->color_m1;
        $inability->genero_m1 = $request->genero_m1;
        $inability->edad_m1 = $request->edad_m1;
        $inability->valor_prima_m1 = $request->valor_prima_m1;

        // Almacenar datos de la mascota 2
        $inability->nombre_m2 = $request->nombre_m2;
        $inability->tipo_m2 = $request->tipo_m2;
        $inability->raza_m2 = $request->raza_m2;
        $inability->color_m2 = $request->color_m2;
        $inability->genero_m2 = $request->genero_m2;
        $inability->edad_m2 = $request->edad_m2;
        $inability->valor_prima_m2 = $request->valor_prima_m2;

        // Almacenar datos de la mascota 3 si existen
        $inability->nombre_m3 = $request->nombre_m3;
        $inability->tipo_m3 = $request->tipo_m3;
        $inability->raza_m3 = $request->raza_m3;
        $inability->color_m3 = $request->color_m3;
        $inability->genero_m3 = $request->genero_m3;
        $inability->edad_m3 = $request->edad_m3;
        $inability->valor_prima_m3 = $request->valor_prima_m3;
        $inability->save();


        $val_total_desc_mensual = $inability->val_total_desc_mensual;
        $tu_pierdes = number_format($inability->tu_pierdes, 0, ',', '.');
        $te_pagamos = number_format($inability->te_pagamos, 0, ',', '.');
        $edad = $inability->edad;
        $message = "La información se guardó correctamente.";
        $aseguradora = $inability->aseguradora;

        return view(
            'affiliations.inabilities.step6',
            compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'message', 'inabilityId', 'aseguradora')
        );
    }
}
