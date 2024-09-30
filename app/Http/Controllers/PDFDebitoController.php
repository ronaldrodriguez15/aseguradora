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
                $pdf->SetXY(57, 67);
                $pdf->Write(0, convertToISO88591($inability->email_corporativo));

                //entidad
                $pdf->SetXY(135, 67);
                $pdf->Write(0, convertToISO88591($inability->entidad_pagadora_sucursal));

                //banco
                $pdf->SetXY(55, 78);
                $pdf->Write(0, convertToISO88591($inability->banco));

                //ciudad
                $pdf->SetXY(135, 78);
                $pdf->Write(0, convertToISO88591($inability->ciudad_banco));

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

        // Salida del PDF
        $pdf->Output('I', 'DebitoGenerado.pdf');
    }
}
