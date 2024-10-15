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
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        // dd($request->all());
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

    public function descargarPlanoFocus(Request $request)
    {
        // Obtener los IDs seleccionados
        $selectedRecords = json_decode($request->input('selected_records'));

        // Obtener los datos de los registros seleccionados como una colección
        $inabilities = Inability::whereIn('id', $selectedRecords)->get();

        if ($inabilities->isEmpty()) {
            // Si no se encontraron registros, retorna un mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros para descargar.'
            ]);
        }

        // Crear una instancia de FocusExport con la colección
        $export = new FocusExport($inabilities);

        // Cargar la plantilla existente
        $spreadsheet = IOFactory::load(public_path('plano_focus/plantilla.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $row = 3; // Fila donde comenzaremos a escribir los datos

        // Obtener la colección desde el export
        $data = $export->collection();

        // Agregar datos a la plantilla
        foreach ($data as $inability) {
            // Escribir datos en las celdas
            $sheet->setCellValue('A' . $row, $inability->no_solicitud);
            $sheet->setCellValue('B' . $row, $inability->no_identificacion);
            $sheet->setCellValue('C' . $row, $inability->nombres_completos . ' ' . $inability->primer_apellido . ' ' . $inability->segundo_apellido);
            $sheet->setCellValue('D' . $row, $inability->fecha_nacimiento_asesor);
            $sheet->setCellValue('E' . $row, $inability->edad);
            $sheet->setCellValue('F' . $row, $inability->prima_pago_prima_seguro);
            $sheet->setCellValue('G' . $row, $inability->desea_valor);
            $sheet->setCellValue('H' . $row, $inability->valor_ibc_basico);
            $sheet->setCellValue('I' . $row, $inability->valor_adicional);
            $sheet->setCellValue('J' . $row, $inability->total);
            $sheet->setCellValue('K' . $row, $inability->amparo_basico);

            // Convertir los valores de string a números quitando los puntos y cambiando las comas por puntos decimales
            $total = str_replace(['.', ','], ['', '.'], $inability->total);
            $amparo_basico = str_replace(['.', ','], ['', '.'], $inability->amparo_basico);
            // Convertir a float para hacer la suma
            $suma = (float)$total + (float)$amparo_basico;

            // Escribir el resultado en la celda L
            $sheet->setCellValue('L' . $row, number_format($suma, 2, ',', '.'));
            $sheet->setCellValue('M' . $row, $inability->entidad_pagadora_sucursal);
            $tipo_pago = '';
            if ($inability->forma_pago === 'mensual_libranza') {
                $tipo_pago = 'Mensual Libranza';
            } else {
                $tipo_pago = 'Debito Automatico';
            }
            $sheet->setCellValue('N' . $row, $tipo_pago ?? '0');
            $sheet->setCellValue('O' . $row, $inability->banco ?? '0');
            $sheet->setCellValue('P' . $row, $inability->ciudad_banco ?? '0');

            if ($inability->forma_pago !== 'mensual_libranza') {
                $sheet->setCellValue('Q' . $row, $inability->tipo_cuenta ?? '0');
            }

            $sheet->setCellValue('R' . $row, $inability->no_cuenta ?? '0');
            $sheet->setCellValue('S' . $row, $inability->val_prevexequial_eclusivo);
            $sheet->setCellValue('T' . $row, $inability->gastos_administrativos);
            $sheet->setCellValue('U' . $row, $inability->val_total_desc_mensual);
            $sheet->setCellValue('v' . $row, $inability->created_at->format('Y-m-d'));
            $sheet->setCellValue('W' . $row, $inability->ciudad_residencia);
            $sheet->setCellValue('X' . $row, 'Cundinamarca');
            $sheet->setCellValue('Y' . $row, $inability->genero);
            $sheet->setCellValue('Z' . $row, $inability->direccion_residencia);
            $sheet->setCellValue('AA' . $row, $inability->ciudad_residencia);
            $sheet->setCellValue('AB' . $row, 'Cundinamarca');
            $sheet->setCellValue('AC' . $row, $inability->celular ?? '0');
            $sheet->setCellValue('AD' . $row, $inability->telefono_fijo ?? '0');
            $sheet->setCellValue('AE' . $row, $inability->email_corporativo ?? '0');
            $sheet->setCellValue('AF' . $row, $inability->ocupacion_asegurado ?? '0');
            $sheet->setCellValue('AG' . $row, $inability->nombre_eps ?? '0');
            $sheet->setCellValue('AH' . $row, $inability->descuento_eps ?? '0');
            $sheet->setCellValue('AI' . $row, $inability->fuente_recursos ?? '0');
            $sheet->setCellValue('AJ' . $row, $inability->proteger_mascotas ?? '0');
            $sheet->setCellValue('AK' . $row, $inability->nombre_m1 ?? '0');
            $sheet->setCellValue('AL' . $row, $inability->tipo_m1 ?? '0');
            $sheet->setCellValue('AM' . $row, $inability->raza_m1 ?? '0');
            $sheet->setCellValue('AN' . $row, $inability->color_m1 ?? '0');
            $sheet->setCellValue('AO' . $row, $inability->genero_m1 ?? '0');
            $sheet->setCellValue('AP' . $row, $inability->edad_m1 ?? '0');
            $sheet->setCellValue('AQ' . $row, $inability->nombre_m2 ?? '0');
            $sheet->setCellValue('AR' . $row, $inability->tipo_m2 ?? '0');
            $sheet->setCellValue('AS' . $row, $inability->raza_m2 ?? '0');
            $sheet->setCellValue('AT' . $row, $inability->color_m2 ?? '0');
            $sheet->setCellValue('AU' . $row, $inability->genero_m2 ?? '0');
            $sheet->setCellValue('AV' . $row, $inability->edad_m2 ?? '0');
            $sheet->setCellValue('AW' . $row, $inability->nombre_m3 ?? '0');
            $sheet->setCellValue('AX' . $row, $inability->tipo_m3 ?? '0');
            $sheet->setCellValue('AY' . $row, $inability->raza_m3 ?? '0');
            $sheet->setCellValue('AZ' . $row, $inability->color_m3 ?? '0');

            $sheet->setCellValue('BA' . $row, $inability->genero_m3 ?? '0');
            $sheet->setCellValue('BB' . $row, $inability->edad_m3 ?? '0');
            $sheet->setCellValue('BC' . $row, $inability->nombres_s1 ?? '0');
            $sheet->setCellValue('BD' . $row, $inability->parentesco_s1 ?? '0');
            $sheet->setCellValue('BE' . $row, $inability->porcentaje_s1 ?? '0');
            $sheet->setCellValue('BF' . $row, 'GRATUITO');
            $sheet->setCellValue('BH' . $row, $inability->n_identificacion_s1 ?? '0');
            $sheet->setCellValue('BG' . $row, $inability->tipo_identidad_s1 ?? '0');
            $sheet->setCellValue('BI' . $row, $inability->nombres_s2 ?? '0');
            $sheet->setCellValue('BJ' . $row, $inability->parentesco_s2 ?? '0');
            $sheet->setCellValue('BK' . $row, $inability->porcentaje_s2 ?? '0');
            $sheet->setCellValue('BL' . $row, 'GRATUITO');
            $sheet->setCellValue('BM' . $row, $inability->tipo_identidad_s2 ?? '0');
            $sheet->setCellValue('BN' . $row, $inability->n_identificacion_s2 ?? '0');
            $sheet->setCellValue('BO' . $row, $inability->nombres_s3 ?? '0');
            $sheet->setCellValue('BP' . $row, $inability->parentesco_s3 ?? '0');
            $sheet->setCellValue('BQ' . $row, $inability->porcentaje_s3 ?? '0');
            $sheet->setCellValue('BR' . $row, 'GRATUITO');
            $sheet->setCellValue('BS' . $row, $inability->tipo_identidad_s3 ?? '0');
            $sheet->setCellValue('BT' . $row, $inability->n_identificacion_s3 ?? '0');
            $sheet->setCellValue('BU' . $row, $inability->cancer ?? '0');
            $sheet->setCellValue('BV' . $row, $inability->corazon ?? '0');
            $sheet->setCellValue('BW' . $row, $inability->diabetes ?? '0');
            $sheet->setCellValue('BX' . $row, $inability->enf_hepaticas ?? '0');
            $sheet->setCellValue('BY' . $row, $inability->enf_neurologicas ?? '0');
            $sheet->setCellValue('BZ' . $row, $inability->pulmones ?? '0');

            $sheet->setCellValue('CA' . $row, $inability->presion_arterial ?? '0');
            $sheet->setCellValue('CB' . $row, $inability->rinones ?? '0');
            $sheet->setCellValue('CC' . $row, $inability->infeccion_vih ?? '0');
            $sheet->setCellValue('CD' . $row, $inability->perdida_funcional_anatomica ?? '0');
            $sheet->setCellValue('CE' . $row, $inability->accidentes_labores_ocupacion ?? '0');
            $sheet->setCellValue('CF' . $row, $inability->hospitalizacion_intervencion_quirurgica ?? '0');
            $sheet->setCellValue('CG' . $row, $inability->enfermedad_diferente ?? '0');
            $sheet->setCellValue('CH' . $row, $inability->descripcion_de_enfermedades ?? '0');
            $sheet->setCellValue('CI' . $row, $inability->nombres_apellidos_r1 ?? '0');
            $sheet->setCellValue('CJ' . $row, $inability->telefono_r1 ?? '0');
            $sheet->setCellValue('CK' . $row, $inability->entidad_r1 ?? '0');
            $sheet->setCellValue('CL' . $row, $inability->nombres_apellidos_r2 ?? '0');
            $sheet->setCellValue('CM' . $row, $inability->telefono_r2 ?? '0');
            $sheet->setCellValue('CN' . $row, $inability->entidad_r2 ?? '0');
            $sheet->setCellValue('CO' . $row, $inability->nombres_apellidos_r3 ?? '0');
            $sheet->setCellValue('CP' . $row, $inability->telefono_r3 ?? '0');
            $sheet->setCellValue('CQ' . $row, $inability->entidad_r3 ?? '0');
            $sheet->setCellValue('CR' . $row, $inability->nombre_asesor ?? '0');
            $sheet->setCellValue('CS' . $row, $inability->codigo_asesor ?? '0');

            $fontSize = 9; // Tamaño de fuente deseado

            $sheet->getStyle('A' . $row . ':CS' . $row)->getFont()->setSize($fontSize);

            $row++;
        }

        // Crear el escritor de Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Hacer que la respuesta sea una descarga
        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="plano_focus_' . time() . '.xlsx"',
        ]);
    }

    public function descargarSeguimientoVentas(Request $request)
    {
        // Obtener los IDs seleccionados
        $selectedRecords = json_decode($request->input('selected_records'));

        // Obtener los datos de los registros seleccionados como una colección
        $inabilities = Inability::whereIn('id', $selectedRecords)->get();

        if ($inabilities->isEmpty()) {
            // Si no se encontraron registros, retorna un mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros para descargar.'
            ]);
        }

        // Crear una instancia de FocusExport con la colección
        $export = new FocusExport($inabilities);

        // Cargar la plantilla existente
        $spreadsheet = IOFactory::load(public_path('plano_focus/seguimiento.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $row = 2; // Fila donde comenzaremos a escribir los datos

        // Obtener la colección desde el export
        $data = $export->collection();

        // Agregar datos a la plantilla
        foreach ($data as $inability) {
            // Escribir datos en las celdas
            $sheet->setCellValue('A' . $row, $inability->no_solicitud);
            $sheet->setCellValue('B' . $row, $inability->nombre_asesor);
            $sheet->setCellValue('C' . $row, $inability->nombres_completos . ' ' . $inability->primer_apellido . ' ' . $inability->segundo_apellido);
            $sheet->setCellValue('D' . $row, $inability->no_identificacion);
            $sheet->setCellValue('E' . $row, $inability->updated_at->format('Y-m-d'));
            $sheet->setCellValue('F' . $row, 'Fecha firmado');
            $sheet->setCellValue('G' . $row, 'Firmado');
            $sheet->setCellValue('H' . $row, $inability->entidad_pagadora_sucursal);
            $sheet->setCellValue('I' . $row, $inability->celular);
            $sheet->setCellValue('J' . $row, $inability->email_corporativo);
            $sheet->setCellValue('K' . $row, $inability->fecha_nacimiento_asesor);
            $sheet->setCellValue('L' . $row, $inability->edad);
            $sheet->setCellValue('M' . $row, $inability->valor_ibc_basico);
            $sheet->setCellValue('N' . $row, $inability->valor_adicional);
            $sheet->setCellValue('O' . $row, $inability->amparo_basico);
            $sheet->setCellValue('P' . $row, $inability->prima_pago_prima_seguro);
            $sheet->setCellValue('Q' . $row, $inability->gastos_administrativos);
            $sheet->setCellValue('R' . $row, '0');
            $tipo_pago = '';
            if ($inability->forma_pago === 'mensual_libranza') {
                $tipo_pago = 'Mensual Libranza';
            } else {
                $tipo_pago = 'Debito Automatico';
            }
            $sheet->setCellValue('S' . $row, $tipo_pago);
            $sheet->setCellValue('T' . $row, ($inability->forma_pago !== 'mensual_libranza') ? $inability->no_cuenta : '0');
            $sheet->setCellValue('U' . $row, ($inability->forma_pago !== 'mensual_libranza') ? $inability->tipo_cuenta : '0');    
            $sheet->setCellValue('V' . $row, ($inability->forma_pago !== 'mensual_libranza') ? $inability->banco : '0');

            $fontSize = 8; // Tamaño de fuente deseado

            $sheet->getStyle('A' . $row . ':V' . $row)->getFont()->setSize($fontSize);

            $row++;
        }

        // Crear el escritor de Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Hacer que la respuesta sea una descarga
        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="plano_seguimiento_' . time() . '.xlsx"',
        ]);
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
        $zipFilePath = public_path($zipFileName);

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

        $hasDocuments = false; // Variable para verificar si hay documentos

        foreach ($selectedRecords as $inabilityId) {
            $inability = Inability::find($inabilityId);

            if ($inability) {
                // Obtener los documentos relacionados con la afiliación
                $documents = DocumentSigned::where('inability_id', $inabilityId)->get();

                // Solo agregar carpeta si hay documentos
                if ($documents->isNotEmpty()) {
                    $hasDocuments = true; // Hay al menos un documento
                    // Crear una carpeta dentro del ZIP con el número de solicitud como nombre
                    $folderName = $inability->no_solicitud;
                    $zip->addEmptyDir($folderName);

                    // Agregar cada documento al archivo ZIP
                    foreach ($documents as $document) {
                        $filePath = storage_path('app/' . $document->document_path);

                        if (File::exists($filePath)) {
                            $zip->addFile($filePath, "$folderName/" . basename($filePath));
                        } else {
                            // Registrar en el log si el archivo no existe
                            Log::warning("Archivo no encontrado: $filePath");
                        }
                    }
                }
            }
        }

        // Cerrar el archivo ZIP
        if ($zip->close() === false) {
            Log::error('Error al cerrar el archivo ZIP: ' . $zip->getStatusString());
            return redirect()->back()->with('error', 'No se pudo cerrar el archivo ZIP.');
        }

        // Verificar si no se han agregado documentos al ZIP
        if (!$hasDocuments) {
            return redirect()->back()->with('error', 'No se encontraron documentos para los registros seleccionados.');
        }

        // Descargar el archivo ZIP
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
