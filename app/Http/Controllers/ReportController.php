<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Asesor;
use App\Models\Insurer;
use App\Models\Entity;
use App\Models\Inability;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentSigned;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FocusExport;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Iniciamos la consulta base
        $query = Inability::select('inabilities.*', 'insurers.name as insurer_name', DB::raw('
            CASE
                WHEN (SELECT COUNT(*) FROM documents_signed WHERE inability_id = inabilities.id) >= 3 THEN "Firmado"
                WHEN path_estasseguro IS NULL AND path_aseguradora IS NULL AND path_pago IS NULL THEN "Sin firmar"
                WHEN path_estasseguro IS NOT NULL AND path_aseguradora IS NOT NULL AND path_pago IS NOT NULL THEN "Pendiente"
                ELSE "En espera"
            END as estado_firmado
        '))
            ->leftJoin('insurers', 'inabilities.insurer_id', '=', 'insurers.id');

        // Aplicamos los filtros si están presentes
        if ($request->filled('fecha_desde')) {
            $query->where('inabilities.created_at', '>=', $request->input('fecha_desde'));
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('inabilities.created_at', '<=', $request->input('fecha_hasta'));
        }

        if ($request->filled('vendedor')) {
            $query->where('inabilities.nombre_asesor', $request->input('vendedor'));
        }

        if ($request->filled('aseguradora')) {
            $query->where('inabilities.insurer_id', $request->input('aseguradora'));
        }

        if ($request->filled('numero_afiliacion')) {
            $query->where('inabilities.no_solicitud', 'like', '%' . $request->input('numero_afiliacion') . '%');
        }

        if ($request->filled('fecha_afiliacion')) {
            $query->where('inabilities.fecha_diligenciamiento', $request->input('fecha_afiliacion'));
        }

        if ($request->filled('cedula')) {
            $query->where('inabilities.no_identificacion', 'like', '%' . $request->input('cedula') . '%');
        }

        if ($request->filled('entidad')) {
            $query->where('inabilities.entidad_pagadora_sucursal', $request->input('entidad'));
        }

        if ($request->filled('ciudad')) {
            $query->where('inabilities.ciudad_residencia', $request->input('ciudad'));
        }

        if ($request->filled('edad')) {
            $query->where('inabilities.edad', $request->input('edad'));
        }

        // Finalizamos la consulta con los filtros aplicados
        $inabilities = $query->orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        // Variables adicionales
        $cities = City::where('status', 1)->get();
        $asesors = Asesor::where('status', 1)->get();
        $insurers = Insurer::where('status', 1)->get();
        $entities = Entity::where('status', 1)->get();

        // Retornamos la vista con los datos y los filtros aplicados
        return view('reports.index', compact('inabilities', 'cities', 'asesors', 'insurers', 'entities', 'request'));
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

    public function descargarPDFs(Request $request)
    {
        // Obtener los IDs seleccionados
        $selectedRecords = json_decode($request->input('selected_records'));

        if (empty($selectedRecords)) {
            return redirect()->back()->with('error', 'No se seleccionaron registros.');
        }

        // Crear un archivo ZIP en la ruta de almacenamiento
        $zipFileName = 'afiliaciones_documentos.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        // Verificar si ya existe el archivo ZIP y eliminarlo antes de crear uno nuevo
        if (File::exists($zipFilePath)) {
            File::delete($zipFilePath);
        }

        // Inicializar ZipArchive
        $zip = new ZipArchive();

        // Intentar abrir el archivo ZIP
        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            Log::error('Error al abrir el archivo ZIP: ' . $zip->getStatusString());
            return redirect()->back()->with('error', 'No se pudo crear el archivo ZIP.');
        }

        foreach ($selectedRecords as $inabilityId) {
            // Obtener la afiliación por su ID
            $inability = Inability::find($inabilityId);

            if ($inability) {
                // Crear una carpeta dentro del ZIP con el número de solicitud como nombre
                $folderName = $inability->no_solicitud;
                $zip->addEmptyDir($folderName);

                // Obtener los documentos relacionados con la afiliación
                $documents = DocumentSigned::where('inability_id', $inabilityId)->get();

                // Agregar cada documento al archivo ZIP
                foreach ($documents as $document) {
                    $filePath = storage_path('app/' . $document->file_path);

                    if (File::exists($filePath)) {
                        $zip->addFile($filePath, "$folderName/" . basename($filePath));
                    } else {
                        // Registrar en el log si el archivo no existe
                        Log::warning("Archivo no encontrado: $filePath");
                    }
                }
            }
        }

        // Cerrar el archivo ZIP
        if ($zip->close() === false) {
            Log::error('Error al cerrar el archivo ZIP: ' . $zip->getStatusString());
            return redirect()->back()->with('error', 'No se pudo cerrar el archivo ZIP.');
        }

        // Descargar el archivo ZIP
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function descargarPlanoFocus(Request $request)
    {
        // Obtener los IDs seleccionados
        $selectedRecords = json_decode($request->input('selected_records'));

        // Obtener los datos de los registros seleccionados como una colección
        $inabilities = Inability::whereIn('id', $selectedRecords)->get();

        // Crear una instancia de FocusExport con la colección
        $export = new FocusExport($inabilities);

        // Guardar el archivo en una ubicación temporal
        $filePath = public_path('plano_focus/resultado_' . time() . '.xlsx');

        // Guardar el archivo Excel
        $export->saveTemplate($filePath);

        // Retornar la descarga
        return response()->json([
            'success' => true,
            'file' => url('plano_focus/resultado_' . time() . '.xlsx')
        ]);
    }
}
