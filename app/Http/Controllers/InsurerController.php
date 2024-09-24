<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurer;
use Illuminate\Support\Facades\Storage;

class InsurerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insurers = Insurer::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('general.insurers.index', compact('insurers'));
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
            'no_poliza' => 'required',
            'name' => 'required|string|max:255',
            'document_path' => 'nullable|file|mimes:pdf,PDF|max:2048',
            'identificador' => 'required',
        ];
        $messages = [
            'poliza.required' => 'El campo poliza es obligatorio.',
            'identificador.required' => 'El campo código es obligatorio.',

            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',

            'document.file' => 'El documento debe ser un archivo.',
            'document.mimes' => 'El documento debe ser un archivo de tipo: pdf.',
            'document.max' => 'El documento no debe exceder los 2048 KB.',
        ];

        $this->validate($request, $rules, $messages);

        $document_path = null;
        if ($request->hasFile('document_path')) {
            $document_path = $request->file('document_path')->store('insurers', 'public'); // Almacena el documento en storage/app/public/insurers
        }

        $insurer = new Insurer();
        $insurer->no_poliza = $request->no_poliza;
        $insurer->identificador = $request->identificador;
        $insurer->name = $request->name;
        $insurer->document_path = $document_path;
        $insurer->status = 1; //Activo 1, Inactivo 2
        $insurer->save();

        return redirect()->route('aseguradoras.index')->with('success', 'Excelente!!, La aseguradora ha sido creada.');
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
            'no_poliza' => 'required',
            'name' => 'required|string|max:255',
            'document_path' => 'nullable|file|mimes:pdf,PDF|max:2048',
            'identificador' => 'required',
        ];
        $messages = [
            'poliza.required' => 'El campo poliza es obligatorio.',
            'identificador.required' => 'El campo código es obligatorio.',

            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',

            'document.file' => 'El documento debe ser un archivo.',
            'document.mimes' => 'El documento debe ser un archivo de tipo: pdf.',
            'document.max' => 'El documento no debe exceder los 2048 KB.',
        ];

        $this->validate($request, $rules, $messages);

        $insurer = Insurer::find();

        $document_path = null;
        if ($request->hasFile('document_path')) {
            // Eliminar el archivo antiguo si existe
            if ($insurer->document_path && Storage::disk('public')->exists($insurer->document_path)) {
                Storage::disk('public')->delete($insurer->document_path);
            }
            // Guardar el nuevo archivo
            $insurer->document_path = $request->file('document_path')->store('insurers', 'public');
        }

        $insurer->no_poliza = $request->no_poliza;
        $insurer->identificador = $request->identificador;
        $insurer->name = $request->name;
        $insurer->document_path = $document_path;
        $insurer->status = 1; //Activo 1, Inactivo 2
        $insurer->save();

        return redirect()->route('aseguradoras.index')->with('success', 'Excelente!!, La aseguradora ha sido creada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $insurer = Insurer::find($id);

        // Eliminar el archivo asociado si existe
        if ($insurer->document_path && Storage::disk('public')->exists($insurer->document_path)) {
            Storage::disk('public')->delete($insurer->document_path);
        }

        $insurer->delete();

        return redirect()->route('aseguradoras.index')->with('success', 'La aseguradora ha sido eliminada.');
    }
}
