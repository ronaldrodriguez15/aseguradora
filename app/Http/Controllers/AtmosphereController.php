<?php

namespace App\Http\Controllers;

use App\Models\Atmosphere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtmosphereController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $atmosphere = Atmosphere::latest()->first();

        return view('general.atmosphere.index', compact('atmosphere'));
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
        $rules = [
            'ambiente' => 'required',
        ];
        $messages = [
            'ambiente.required' => 'El campo ambiente es obligatorio.',
        ];

        $this->validate($request, $rules, $messages);

        // Cambiar los valores del arreglo para que coincidan con lo que se envía desde el formulario
        $atmosphereKeys = [
            'development' => 'sandbox', // Asegúrate de que la clave sea minúscula
            'production' => 'documents',
        ];

        $atmosphere = new Atmosphere();
        $atmosphere->name = $request->ambiente;

        // Asignar el valor correcto a 'key' basado en el valor de 'ambiente' en minúsculas
        $atmosphere->key = $atmosphereKeys[strtolower($request->ambiente)] ?? 'sandbox'; // 'sandbox' como valor predeterminado
        $atmosphere->save();

        return redirect()->back()->with('success', 'Ambiente guardado con éxito.');
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
        $rules = [
            'ambiente' => 'required',
        ];
        $messages = [
            'ambiente.required' => 'El campo ambiente es obligatorio.',
        ];

        $this->validate($request, $rules, $messages);

        $atmosphere = Atmosphere::findOrFail($id);

        $atmosphereKeys = [
            'Development' => 'sandbox',
            'Production' => 'documents',
        ];

        $atmosphere->name = $request->ambiente;
        $atmosphere->key = $atmosphereKeys[$request->ambiente] ?? 'sandbox';
        $atmosphere->save();

        return redirect()->back()->with('success', 'Ambiente actualizado con éxito.');
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
}
