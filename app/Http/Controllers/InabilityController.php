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
        // $rules = [
        //     'no_solicitud' => 'required|string',
        //     'aseguradora' => 'required|numeric',
        //     'no_poliza' => 'required|string',
        //     'codigo_asesor' => 'required|string',
        //     'nombre_asesor' => 'required|string|max:255',
        //     'nombre_eps' => 'required|string',
        //     'fecha_nacimiento_asesor' => 'required|date',
        //     'email_corporativo' => 'required|email|max:255',
        //     'descuento_eps' => 'required|numeric',
        //     'numero_dias' => 'required|numeric|min:1',
        //     'valor_ibc_basico' => 'required|numeric',
        //     'desea_valor' => 'required|in:si,no',
        //     'valor_adicional' => 'nullable|numeric',
        //     'total' => 'required|string',
        //     'amparo_basico' => 'nullable|numeric',
        //     'val_prevexequial_eclusivo' => 'required|numeric',
        //     'prima_pago_prima_seguro' => 'required|string',
        //     'gastos_administrativos' => 'nullable|numeric',
        //     'val_total_desc_mensual' => 'required|numeric',
        //     'tu_pierdes' => 'required|numeric',
        //     'te_pagamos' => 'required|numeric',
        // ];

        // $messages = [
        //     'no_solicitud.required' => 'El campo número de solicitud es obligatorio.',
        //     'aseguradora.required' => 'El campo aseguradora es obligatorio.',
        //     'aseguradora.numeric' => 'El campo aseguradora debe ser numérico.',
        //     'no_poliza.required' => 'El campo número de póliza es obligatorio.',
        //     'codigo_asesor.required' => 'El campo código de asesor es obligatorio.',
        //     'nombre_asesor.required' => 'El campo nombre del asesor es obligatorio.',
        //     'nombre_asesor.max' => 'El nombre del asesor no debe exceder los 255 caracteres.',
        //     'nombre_eps.required' => 'El campo nombre de la EPS es obligatorio.',
        //     'fecha_nacimiento_asesor.required' => 'El campo fecha de nacimiento del asesor es obligatorio.',
        //     'fecha_nacimiento_asesor.date' => 'El campo fecha de nacimiento debe ser una fecha válida.',
        //     'email_corporativo.required' => 'El campo correo corporativo es obligatorio.',
        //     'email_corporativo.email' => 'El campo correo corporativo debe ser una dirección de correo válida.',
        //     'email_corporativo.max' => 'El campo correo corporativo no debe exceder los 255 caracteres.',
        //     'descuento_eps.required' => 'El campo descuento EPS es obligatorio.',
        //     'descuento_eps.numeric' => 'El campo descuento EPS debe ser un número.',
        //     'numero_dias.required' => 'El campo número de días es obligatorio.',
        //     'numero_dias.numeric' => 'El campo número de días debe ser un número.',
        //     'numero_dias.min' => 'El campo número de días debe ser al menos 1.',
        //     'valor_ibc_basico.required' => 'El campo valor IBC básico es obligatorio.',
        //     'valor_ibc_basico.numeric' => 'El campo valor IBC básico debe ser numérico.',
        //     'desea_valor.required' => 'El campo desea valor es obligatorio.',
        //     'desea_valor.in' => 'El campo desea valor debe ser "si" o "no".',
        //     'valor_adicional.numeric' => 'El campo valor adicional debe ser numérico.',
        //     'total.required' => 'El campo total es obligatorio.',
        //     'amparo_basico.numeric' => 'El campo amparo básico debe ser numérico.',
        //     'val_prevexequial_eclusivo.required' => 'El campo valor previ-exequial exclusivo es obligatorio.',
        //     'val_prevexequial_eclusivo.numeric' => 'El campo valor previ-exequial exclusivo debe ser numérico.',
        //     'prima_pago_prima_seguro.required' => 'El campo prima de pago prima de seguro es obligatorio.',
        //     'gastos_administrativos.numeric' => 'El campo gastos administrativos debe ser numérico.',
        //     'val_total_desc_mensual.required' => 'El campo valor total descuento mensual es obligatorio.',
        //     'val_total_desc_mensual.numeric' => 'El campo valor total descuento mensual debe ser numérico.',
        //     'tu_pierdes.required' => 'El campo tú pierdes es obligatorio.',
        //     'tu_pierdes.numeric' => 'El campo tú pierdes debe ser numérico.',
        //     'te_pagamos.required' => 'El campo te pagamos es obligatorio.',
        //     'te_pagamos.numeric' => 'El campo te pagamos debe ser numérico.',
        // ];

        // $this->validate($request, $rules, $messages);

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

        return view('affiliations.inabilities.step2', compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'banks', 'cities'))
            ->with('success', 'La información se guardó correctamente.');
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
        $inability->no_cuenta = $request->no_cuenta;

        $bank = Bank::find($request->banco);
        $inability->bank_id = $bank->id;
        $inability->banco = $bank->name;
        $inability->ciudad_banco = $request->ciudad_banco;
        $inability->save();

        $val_total_desc_mensual = $inability->val_total_desc_mensual;
        $tu_pierdes = number_format($inability->tu_pierdes, 0, ',', '.');
        $te_pagamos = number_format($inability->te_pagamos, 0, ',', '.');
        $edad = $inability->edad;
        $cities = City::where('status', 1)->get();
        $epss = Eps::where('status', 1)->get();
        $companies = Entity::where('status', 1)->get();

        return view('affiliations.inabilities.step3', compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'cities', 'epss', 'companies'))
            ->with('success', 'La información se guardó correctamente.');
    }

    public function formStepFour(Request $request)
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
        $inability->no_cuenta = $request->no_cuenta;

        $bank = Bank::find($request->banco);
        $inability->bank_id = $bank->id;
        $inability->banco = $bank->name;
        $inability->ciudad_banco = $request->ciudad_banco;
        $inability->save();

        $val_total_desc_mensual = $request->val_total_desc_mensual;
        $tu_pierdes = number_format($request->tu_pierdes, 0, ',', '.');
        $te_pagamos = number_format($request->te_pagamos, 0, ',', '.');
        $edad = $request->edad;
        $banks = Bank::where('status', 1)->get();
        $cities = City::where('status', 1)->get();

        return view('affiliations.inabilities.step3', compact('val_total_desc_mensual', 'tu_pierdes', 'te_pagamos', 'edad', 'banks', 'cities'))
            ->with('success', 'La información se guardó correctamente.');
    }
}
