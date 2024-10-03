<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use App\Models\Docuuments;

class PDFLibranzaController extends Controller
{
    public function generarPDF($id)
    {
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        $document = Docuuments::first();

        // Asegúrate de que exista el documento y que la columna libranza_document no esté vacía
        if (!$document || empty($document->libranza_document)) {
            abort(404, 'Documento no encontrado.');
        }

        // Obtener la ruta del archivo PDF almacenado
        $pdfFilePath = $document->libranza_document;

        // Verificar si el archivo existe en Storage
        if (!Storage::disk('public')->exists($pdfFilePath)) {
            abort(404, 'Archivo PDF no encontrado.');
        }

        // Obtener la ruta completa del archivo
        $fullPath = Storage::disk('public')->path($pdfFilePath);

        // Generar el PDF con la plantilla
        $this->generarPDFConPlantilla($inability, $fullPath);
    }

    private function generarPDFConPlantilla($inability, $pdfFilePath)
    {

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfFilePath);

        // Función para convertir texto a ISO-8859-1
        function convertToISO88591($text)
        {
            return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
        }

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplIdx = $pdf->importPage($pageNo);

            $pdf->AddPage('P', 'Letter');
            $pdf->useTemplate($tplIdx);
            $pdf->SetAutoPageBreak(false);
            if ($pageNo == 1) {
                // Agregar datos al PDF
                $pdf->SetFont('Arial', '', 8);

                //Fecha diligenciamiento
                $pdf->SetXY(44, 42);
                $pdf->Write(0, convertToISO88591($inability->fecha_diligenciamiento));

                //Entidad pagadora
                $pdf->SetXY(83, 41.5);
                $pdf->Write(0, convertToISO88591($inability->entidad_pagadora_sucursal));

                //Nemomico
                $texto = $inability->entidad_pagadora_sucursal;
                $regex = '/\(([^)]+)\)/';

                // Extraer el texto dentro de los paréntesis
                if (preg_match($regex, $texto, $coincidencia)) {
                    $textoExtraido = $coincidencia[1];
                    $pdf->SetXY(150, 42);
                    $pdf->Write(0, convertToISO88591($textoExtraido));
                }

                //Sucursal
                $pdf->SetXY(185, 42);
                $pdf->Write(0, '5');

                // nombre titular contratante
                $nombres_completos = $inability->primer_apellido . ' ' . $inability->segundo_apellido . ' ' . $inability->nombres_completos;
                $pdf->SetXY(40, 48.8);
                $pdf->Write(0, convertToISO88591($nombres_completos));

                //Cedula
                $pdf->SetXY(132, 48.8);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                //expedicion
                $pdf->SetXY(173, 48.8);
                $pdf->Write(0, convertToISO88591($inability->ciudad_expedicion));

                //calidad de
                $pdf->SetXY(55, 53);
                $pdf->Write(0, convertToISO88591($inability->ocupacion_asegurado));

                // No de Contrato
                // Extraer el año y el mes de la fecha
                $fecha = $inability->fecha_diligenciamiento;
                $ano = date('Y', strtotime($fecha));
                $mes = date('n', strtotime($fecha));
                $pdf->SetXY(149, 60.5);
                $pdf->Write(0, $ano . $mes . str_replace('.', '', $inability->no_identificacion));

                //previexequial exclusivo
                if ($inability->servicios_prevision_exequial === 'si') {
                    $pdf->SetXY(102, 65);
                    $pdf->Write(0, 'X');
                }

                //prevision exequial mascotas
                if ($inability->serv_prevision_exequial_mascotas === 'si') {
                    $pdf->SetXY(169.5, 65.2);
                    $pdf->Write(0, 'X');
                }

                //servicios de prevision salud mascotas
                if ($inability->serv_prevision_salud === 'si') {
                    $pdf->SetXY(102, 69);
                    $pdf->Write(0, 'X');
                }

                //beneficio diario por incapacidad temporal
                if ($inability->beneficiario_diario_inc_temp === 'si') {
                    $pdf->SetXY(102, 81);
                    $pdf->Write(0, 'X');
                }

                //No poliza
                $pdf->SetXY(154, 81);
                $pdf->Write(0, convertToISO88591($inability->no_poliza));

                //valor descuento
                $pdf->SetXY(70, 204.5);
                $pdf->Write(0, $inability->val_total_desc_mensual);

                //CC
                $pdf->SetXY(50, 231.3);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                //direccion
                $pdf->SetXY(113, 231.3);
                $pdf->Write(0, convertToISO88591($inability->direccion_residencia));

                //celular
                $pdf->SetXY(55, 235.3);
                $pdf->Write(0, convertToISO88591($inability->celular));

                //tel fijo
                $pdf->SetXY(109, 235.3);
                $pdf->Write(0, convertToISO88591($inability->telefono_fijo));
            }
        }

        $numeroDocumento = $inability->no_identificacion;
        $fechaActual = date('Y-m-d');
        $nombreArchivo = 'documento_' . $numeroDocumento . '_' . $fechaActual . '.pdf';


        // Ruta donde se va a almacenar el PDF
        $rutaCarpeta = storage_path('app/public/documentos_libranza');
        if (!file_exists($rutaCarpeta)) {
            // Crea la carpeta si no existe
            if (!mkdir($rutaCarpeta, 0755, true)) {
                die('Error al crear la carpeta: ' . $rutaCarpeta);
            }
        }

        $rutaArchivo = $rutaCarpeta . '/' . $nombreArchivo;

        $pdf->Output('F', $rutaArchivo); // 'F' indica que se guarda en un archivo

        $inability->path_pago = 'documentos_libranza/' . $nombreArchivo;
        $inability->save();

        $pdf->Output('I', $nombreArchivo); // 'I' indica que se visualiza en el navegador
    }
}
