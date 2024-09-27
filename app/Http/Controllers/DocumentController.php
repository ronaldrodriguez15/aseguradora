<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docuuments;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Docuuments::all();

        if ($documents->isEmpty()) {
            $documents = collect([new Docuuments([
                'estasseguro_document' => null,
                'libranza_document' => null,
                'debito_document' => null
            ])]);
        }

        return view('documents.index', compact('documents'));
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
            'document_path' => 'required|file|mimes:pdf|max:2048', // Solo archivos PDF y máximo 2MB
            'type_document' => 'required|string|in:estasseguro,libranza,debito', // Debe ser uno de los tipos permitidos
        ];

        $messages = [
            'document_path.required' => 'El documento es obligatorio.',
            'document_path.file' => 'El documento debe ser un archivo.',
            'document_path.mimes' => 'El documento debe ser un archivo de tipo: PDF.',
            'document_path.max' => 'El documento no debe exceder los 2048 KB.',
            'type_document.required' => 'El tipo de documento es obligatorio.',
            'type_document.in' => 'El tipo de documento no es válido.',
        ];

        $this->validate($request, $rules, $messages);

        $type = $request->input('type_document');

        $documentFile = Docuuments::first();

        if (!$documentFile) {
            $documentFile = new Docuuments();
        }

        $oldFilePath = null;
        if ($type === 'estasseguro' && $documentFile->estasseguro_document) {
            $oldFilePath = $documentFile->estasseguro_document;
        } elseif ($type === 'libranza' && $documentFile->libranza_document) {
            $oldFilePath = $documentFile->libranza_document;
        } elseif ($type === 'debito' && $documentFile->debito_document) {
            $oldFilePath = $documentFile->debito_document;
        }

        // Eliminar el archivo anterior si existe
        if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            Storage::disk('public')->delete($oldFilePath);
        }

        // Guardar el nuevo archivo en la carpeta 'documents/uploads' usando Storage
        if ($request->hasFile('document_path')) {
            // Guardar el nuevo archivo
            $newFilePath = $request->file('document_path')->store('documents/uploads', 'public');

            // Asignar el nuevo archivo al campo correspondiente según el tipo de documento
            if ($type === 'estasseguro') {
                $documentFile->estasseguro_document = $newFilePath;
            } elseif ($type === 'libranza') {
                $documentFile->libranza_document = $newFilePath;
            } elseif ($type === 'debito') {
                $documentFile->debito_document = $newFilePath;
            }
        }

        $documentFile->save();

        return redirect()->route('ciudades.index')->with('success', '¡Excelente! El documento ha sido almacenado correctamente.');
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
}
