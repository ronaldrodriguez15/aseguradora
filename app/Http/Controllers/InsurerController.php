<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        ];
        $messages = [
            'poliza.required' => 'El campo poliza es obligatorio.',

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
        $insurer = Insurer::findOrFail($id);
        return response()->json($insurer);
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
        ];
        $messages = [
            'no_poliza.required' => 'El campo póliza es obligatorio.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'document_path.file' => 'El documento debe ser un archivo.',
            'document_path.mimes' => 'El documento debe ser un archivo de tipo: pdf.',
            'document_path.max' => 'El documento no debe exceder los 2048 KB.',
        ];

        // Validar el request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Redirigir de nuevo con errores de validación
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // Para mantener la entrada anterior
        }

        $insurer = Insurer::find($id); // Asegúrate de buscar por ID

        if ($request->hasFile('document_path')) {
            // Eliminar el archivo antiguo si existe
            if ($insurer->document_path && Storage::disk('public')->exists($insurer->document_path)) {
                Storage::disk('public')->delete($insurer->document_path);
            }
            // Guardar el nuevo archivo
            $insurer->document_path = $request->file('document_path')->store('insurers', 'public');
        }

        // Asignar los otros campos
        $insurer->no_poliza = $request->no_poliza;
        $insurer->name = $request->name;
        // No vuelvas a asignar $document_path aquí, ya que ya se ha manejado arriba
        $insurer->status = 1; // Activo 1, Inactivo 2
        $insurer->save();

        return redirect()->route('aseguradoras.index')->with('success', 'Excelente!!, La aseguradora ha sido actualizada.');
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
