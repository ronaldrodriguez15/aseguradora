<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesor;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asesors = Asesor::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('general.asesors.index', compact('asesors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validación del formulario por backend
        $rules = [
            'name' => 'required',
            'asesor_code' => 'required',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'asesor_code.required' => 'El campo codigo es obligatorio',
        ];
        $this->validate($request, $rules, $messages);

        $asesor = new Asesor();
        $asesor->name = $request->name;
        $asesor->asesor_code = $request->asesor_code;
        $asesor->status = 1; //Activo 1, Inactivo 2
        $asesor->save();

        return redirect()->route('asesores.index')->with('success', 'Excelente!!, El asesor ha sido creado.');
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
        // Validación del formulario por backend
        $rules = [
            'name' => 'required',
            'asesor_code' => 'required',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'asesor_code.required' => 'El campo codigo es obligatorio',
        ];
        $this->validate($request, $rules, $messages);

        $asesor = Asesor::find($id);
        $asesor->name = $request->name;
        $asesor->asesor_code = $request->asesor_code;
        $asesor->status = 1; //Activo 1, Inactivo 2
        $asesor->save();

        return redirect()->route('asesores.index')->with('success', 'Excelente!!, El asesor ha sido actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asesor = Asesor::find($id);
        $asesor->delete();

        return redirect()->route('asesores.index')->with('success', 'Excelente!!, El asesir ha sido eliminado.');
    }
}
