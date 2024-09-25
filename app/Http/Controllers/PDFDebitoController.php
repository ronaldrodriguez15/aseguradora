<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inability;
use setasign\Fpdi\Fpdi;

class PDFDebitoController extends Controller
{
    public function generarPDF($id)
    {
        // Obtener los datos de la tabla inabilities
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        // Ruta del archivo PDF a usar como plantilla
        $pdfFilePath = public_path('documents/debito.pdf');

        // Verificar si el archivo existe
        if (!file_exists($pdfFilePath)) {
            abort(404, 'Archivo PDF no encontrado.');
        }

        // Generar el PDF con la plantilla
        $this->generarPDFConPlantilla($inability, $pdfFilePath);
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

            $pdf->AddPage('P', 'Legal');
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
                if ($inability->serv_prevision_exequial_mascotas === 'no') {
                    $pdf->SetXY(172.5, 109.6);
                    $pdf->Write(0, 'X');
                }

                //servicios de prevision salud mascotas
                if ($inability->serv_prevision_salud === 'no') {
                    $pdf->SetXY(109.5, 113.3);
                    $pdf->Write(0, 'X');
                }

                //beneficio diario por incapacidad temporal
                if ($inability->beneficiario_diario_inc_temp === 'si') {
                    $pdf->SetXY(109.5, 123.5);
                    $pdf->Write(0, 'X');
                }
            }
        }

        // Salida del PDF
        $pdf->Output('I', 'DebitoGenerado.pdf');
    }
}
