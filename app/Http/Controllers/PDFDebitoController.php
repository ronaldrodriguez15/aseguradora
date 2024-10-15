<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use App\Models\Docuuments;

class PDFDebitoController extends Controller
{
    public function generarPDF($id)
    {
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        $document = Docuuments::first();

        // Asegúrate de que exista el documento y que la columna libranza_document no esté vacía
        if (!$document || empty($document->debito_document)) {
            abort(404, 'Documento no encontrado.');
        }

        // Obtener la ruta del archivo PDF almacenado
        $pdfFilePath = $document->debito_document;

        // Verificar si el archivo existe en Storage
        if (!Storage::disk('public')->exists($pdfFilePath)) {
            abort(404, 'Archivo PDF no encontrado.');
        }

        // Obtener la ruta completa del archivo
        $fullPath = Storage::disk('public')->path($pdfFilePath);

        // Obtener el consecutivo máximo en la tabla
        $maxInability = Inability::orderBy('consecutivo', 'desc')->first();

        // Verificar el consecutivo máximo
        $maxConsecutivo = $maxInability ? $maxInability->consecutivo : 0;

        // Verificar si el consecutivo actual es menor que el máximo
        if ($inability->consecutivo < $maxConsecutivo) {
            // Actualizar el consecutivo al máximo + 1
            $inability->consecutivo = $maxConsecutivo + 1;
        } else {
            // Si el consecutivo actual es mayor o igual, incrementar en 1
            $inability->consecutivo += 1;
        }

        // Guardar los cambios en el registro
        $inability->save();

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

                //Fecha
                $pdf->SetXY(45, 40);
                $pdf->Write(0, convertToISO88591($inability->fecha_diligenciamiento));

                //Cedula
                $pdf->SetXY(42, 50);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                //Primer apellido
                $pdf->SetXY(86, 50);
                $pdf->Write(0, convertToISO88591($inability->primer_apellido));

                //segundo apellido
                $pdf->SetXY(125, 50);
                $pdf->Write(0, convertToISO88591($inability->segundo_apellido));

                //nombres
                $pdf->SetXY(165, 50);
                $pdf->Write(0, convertToISO88591($inability->nombres_completos));

                //celular
                $pdf->SetXY(42, 59);
                $pdf->Write(0, convertToISO88591($inability->celular));

                //telefono
                $pdf->SetXY(86, 59);
                $pdf->Write(0, convertToISO88591($inability->telefono_fijo));

                //ciudad residencia
                $pdf->SetXY(125, 59);
                $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

                //direccion residencia
                $pdf->SetXY(158, 59);
                $pdf->Write(0, convertToISO88591($inability->direccion_residencia));

                //correo
                $pdf->SetXY(58, 67);
                $pdf->Write(0, convertToISO88591($inability->email_corporativo));

                //entidad
                $pdf->SetXY(136, 67);
                $pdf->Write(0, convertToISO88591($inability->entidad_pagadora_sucursal));

                //banco
                $pdf->SetXY(55, 78);
                $pdf->Write(0, convertToISO88591($inability->banco));

                //ciudad
                $pdf->SetXY(135, 78);
                $pdf->Write(0, convertToISO88591($inability->ciudad_banco));

                //tipo de cuenta
                $pdf->SetXY(38, 91);
                $pdf->Write(0, convertToISO88591($inability->tipo_cuenta));

                //tipo de cuenta 2
                $pdf->SetXY(80, 91);
                $pdf->Write(0, convertToISO88591($inability->tipo_cuenta));

                //n de cuenta
                $pdf->SetXY(120, 91);
                $pdf->Write(0, convertToISO88591($inability->no_cuenta));

                //n de cuenta 2
                $pdf->SetXY(160, 91);
                $pdf->Write(0, convertToISO88591($inability->no_cuenta));

                // No de Contrato
                // Extraer el año y el mes de la fecha
                $fecha = $inability->fecha_diligenciamiento;
                $ano = date('Y', strtotime($fecha));
                $mes = date('n', strtotime($fecha));
                $pdf->SetXY(150, 105);
                $pdf->Write(0, $ano . $mes . str_replace('.', '', $inability->no_identificacion));

                //previexequial exclusivo
                if ($inability->servicios_prevision_exequial === 'si') {
                    $pdf->SetXY(109.5, 109.6);
                    $pdf->Write(0, 'X');
                }

                //prevision exequial mascotas
                if ($inability->serv_prevision_exequial_mascotas === 'si') {
                    $pdf->SetXY(172.5, 109.6);
                    $pdf->Write(0, 'X');
                }

                //servicios de prevision salud mascotas
                if ($inability->serv_prevision_salud === 'si') {
                    $pdf->SetXY(109.5, 113.3);
                    $pdf->Write(0, 'X');
                }

                //beneficio diario por incapacidad temporal
                if ($inability->beneficiario_diario_inc_temp === 'si') {
                    $pdf->SetXY(109.5, 123.5);
                    $pdf->Write(0, 'X');
                }

                //No poliza
                $pdf->SetXY(158, 123.5);
                $pdf->Write(0, convertToISO88591($inability->no_poliza));

                //valor descuento
                $pdf->SetXY(62, 232);
                $pdf->Write(0, convertToISO88591($inability->val_total_desc_mensual));

                //cc
                $pdf->SetXY(42, 256);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                //direccion
                $pdf->SetXY(125, 256);
                $pdf->Write(0, convertToISO88591($inability->direccion_residencia));

                //celular
                $pdf->SetXY(46, 260);
                $pdf->Write(0, convertToISO88591($inability->celular));

                //telefono fijo
                $pdf->SetXY(110, 260);
                $pdf->Write(0, convertToISO88591($inability->telefono_fijo));
            }
        }

        $numeroDocumento = $inability->no_identificacion;
        $fechaActual = date('Y-m-d');
        $nombreArchivo = 'documento_' . $numeroDocumento . '_' . $fechaActual . '.pdf';


        // Ruta donde se va a almacenar el PDF
        $rutaCarpeta = storage_path('app/public/documentos_debito');
        if (!file_exists($rutaCarpeta)) {
            // Crea la carpeta si no existe
            if (!mkdir($rutaCarpeta, 0755, true)) {
                die('Error al crear la carpeta: ' . $rutaCarpeta);
            }
        }

        $rutaArchivo = $rutaCarpeta . '/' . $nombreArchivo;

        $pdf->Output('F', $rutaArchivo); // 'F' indica que se guarda en un archivo

        $inability->path_pago = 'documentos_debito/' . $nombreArchivo;
        $inability->save();

        $pdf->Output('I', $nombreArchivo); // 'I' indica que se visualiza en el navegador
    }
}
