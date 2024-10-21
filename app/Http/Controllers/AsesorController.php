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
            'consecutivo' => 'required|integer', // Asegúrate que el consecutivo sea un número entero
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'asesor_code.required' => 'El campo codigo es obligatorio',
            'consecutivo.required' => 'El campo consecutivo es obligatorio',
            'consecutivo.integer' => 'El campo consecutivo debe ser un número entero.', // Mensaje de error para el tipo de dato
        ];
        $this->validate($request, $rules, $messages);

        // Obtener el consecutivo del nuevo asesor
        $nuevoConsecutivo = $request->consecutivo;

        // Verificar si ya existe un asesor con el mismo consecutivo
        if (Asesor::where('consecutivo', $nuevoConsecutivo)->exists()) {
            return redirect()->back()->with('error', 'Ya existe un asesor con este consecutivo.');
        }

        // Verificar si hay asesores en el rango de 1000 consecutivos
        $asesoresExistentes = Asesor::where('consecutivo', '<=', $nuevoConsecutivo)
            ->where('consecutivo', '>', $nuevoConsecutivo - 1000)
            ->get();

        if ($asesoresExistentes->isNotEmpty()) {
            return redirect()->back()->with('error', 'El consecutivo debe estar fuera del rango de 1000 consecutivos de otro asesor existente.');
        }

        // Crear y guardar el nuevo asesor
        $asesor = new Asesor();
        $asesor->name = $request->name;
        $asesor->consecutivo = $nuevoConsecutivo;
        $asesor->asesor_code = $request->asesor_code;
        $asesor->status = 1; // Activo 1, Inactivo 2
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
        $asesor->consecutivo = $request->consecutivo;
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

        return redirect()->route('asesores.index')->with('success', 'El asesor ha sido eliminado.');
    }
}
