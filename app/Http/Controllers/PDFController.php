<?php

namespace App\Http\Controllers;

use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use App\Models\Inability;
use App\Models\Insurer;

class PDFController extends Controller
{
    public function generarPDF($id)
    {
        // Obtener los datos de la tabla inabilities
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        // Ruta del archivo PDF a usar como plantilla
        $pdfFilePath = public_path('documents/esstas.pdf');

        // Verificar si el archivo existe
        if (!file_exists($pdfFilePath)) {
            abort(404, 'Archivo PDF no encontrado.');
        }

        // dd($inability);

        // Generar el PDF con la plantilla
        $this->generarPDFConPlantilla($inability, $pdfFilePath);
    }

    // private function generarPDFConPlantilla($inability, $pdfFilePath)
    // {
    //     $pdf = new Fpdi();
    //     $pageCount = $pdf->setSourceFile($pdfFilePath);

    //     // Importar la primera página de la plantilla
    //     $tplIdx = $pdf->importPage(1);
    //     $pdf->AddPage();
    //     $pdf->useTemplate($tplIdx);

    //     // Agregar datos al PDF
    //     $pdf->SetFont('Arial', '', 8);

    //     // Función para convertir texto a ISO-8859-1
    //     function convertToISO88591($text)
    //     {
    //         return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    //     }

    //     //Ciudad
    //     $pdf->SetXY(133, 37.5);
    //     $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

    //     // Primer apellido
    //     $pdf->SetXY(48, 48.7);
    //     $pdf->Write(0, convertToISO88591($inability->primer_apellido));

    //     // Segundo apellido
    //     $pdf->SetXY(102, 48.7);
    //     $pdf->Write(0, convertToISO88591($inability->segundo_apellido));

    //     // Nombres completos
    //     $pdf->SetXY(153, 48.7);
    //     $pdf->Write(0, convertToISO88591($inability->nombres_completos));

    //     // N identificacion
    //     $pdf->SetXY(34, 57.5);
    //     $pdf->Write(0, convertToISO88591($inability->no_identificacion));

    //     // tipo identificación
    //     if ($inability->tipo_identificacion === 'cedula_ciudadania') {
    //         $pdf->SetXY(69.5, 57.5);
    //         $pdf->Write(0, 'X');
    //     } else if ($inability->tipo_identificacion === 'cedula_extranjeria') {
    //         $pdf->SetXY(79, 57.5);
    //         $pdf->Write(0, 'X');
    //     } else {
    //         $pdf->SetXY(89.5, 57.5);
    //         $pdf->Write(0, 'X');
    //     }

    //     // fecha nacimiento
    //     $pdf->SetXY(103, 57.5);
    //     $pdf->Write(0, convertToISO88591($inability->fecha_nacimiento_asegurado));

    //     // ciudad
    //     $pdf->SetXY(140, 57.5);
    //     $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

    //     // N identificacion
    //     $pdf->SetXY(170, 57.5);
    //     $pdf->Write(0, "Cundinamarca");

    //     // Genero
    //     if ($inability->genero === 'masculino') {
    //         $pdf->SetXY(55.5, 62.4);
    //         $pdf->Write(0, 'X');
    //     } else if ($inability->genero === 'femenino') {
    //         $pdf->SetXY(46, 62.4);
    //         $pdf->Write(0, 'X');
    //     }

    //     // Direccion de residencia
    //     $pdf->SetXY(84, 62.4);
    //     $pdf->Write(0, convertToISO88591($inability->direccion_residencia));

    //     // Teléfono
    //     $pdf->SetXY(162, 62.4);
    //     $pdf->Write(0, convertToISO88591($inability->telefono_fijo));

    //     // celular
    //     $pdf->SetXY(73, 66);
    //     $pdf->Write(0, convertToISO88591($inability->celular));

    //     // ciudad
    //     $pdf->SetXY(122, 66);
    //     $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

    //     // departamento
    //     $pdf->SetXY(164, 66);
    //     $pdf->Write(0, "Cundinamarca");

    //     // ocupacion
    //     $pdf->SetXY(50, 69.5);
    //     $pdf->Write(0, convertToISO88591($inability->ocupacion_asegurado));

    //     // correo electronico
    //     $pdf->SetXY(119, 69.5);
    //     $pdf->Write(0, convertToISO88591($inability->email_corporativo));

    //     // proteger a mascotas
    //     // $pdf->SetXY(164, 85);
    //     // $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

    //     // Salida del PDF
    //     $pdf->Output('I', 'DocumentoGenerado.pdf');
    // }

    private function generarPDFConPlantilla($inability, $pdfFilePath)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfFilePath);

        // Recorrer todas las páginas del PDF original
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Importar cada página de la plantilla
            $tplIdx = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);

            // Solo agregamos texto en la primera página
            if ($pageNo == 1) {
                // Agregar datos al PDF
                $pdf->SetFont('Arial', '', 8);

                // Función para convertir texto a ISO-8859-1
                function convertToISO88591($text)
                {
                    return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
                }

                // Extraer el año y el mes de la fecha
                $fecha = $inability->fecha_diligenciamiento;
                $ano = date('Y', strtotime($fecha));
                $mes = date('n', strtotime($fecha));

                // No de Contrato
                $pdf->SetXY(168, 29);
                $pdf->Write(0, $ano . $mes . $inability->no_identificacion);

                //Fecha diligenciamiento
                $pdf->SetXY(89.5, 37.5);
                $pdf->Write(0, convertToISO88591($inability->fecha_diligenciamiento));

                //Ciudad
                $pdf->SetXY(133, 37.5);
                $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

                // Primer apellido
                $pdf->SetXY(48, 48.7);
                $pdf->Write(0, convertToISO88591($inability->primer_apellido));

                // Segundo apellido
                $pdf->SetXY(102, 48.7);
                $pdf->Write(0, convertToISO88591($inability->segundo_apellido));

                // Nombres completos
                $pdf->SetXY(153, 48.7);
                $pdf->Write(0, convertToISO88591($inability->nombres_completos));

                // N identificacion
                $pdf->SetXY(34, 57.5);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // tipo identificación
                if ($inability->tipo_identificacion === 'cedula_ciudadania') {
                    $pdf->SetXY(69.5, 57.5);
                    $pdf->Write(0, 'X');
                } else if ($inability->tipo_identificacion === 'cedula_extranjeria') {
                    $pdf->SetXY(79, 57.5);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(89.5, 57.5);
                    $pdf->Write(0, 'X');
                }

                // fecha nacimiento
                $pdf->SetXY(103, 57.5);
                $pdf->Write(0, convertToISO88591($inability->fecha_nacimiento_asegurado));

                // ciudad
                $pdf->SetXY(140, 57.5);
                $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

                // N identificacion
                $pdf->SetXY(170, 57.5);
                $pdf->Write(0, "Cundinamarca");

                // Genero
                if ($inability->genero === 'masculino') {
                    $pdf->SetXY(55.5, 62.4);
                    $pdf->Write(0, 'X');
                } else if ($inability->genero === 'femenino') {
                    $pdf->SetXY(46, 62.4);
                    $pdf->Write(0, 'X');
                }

                // Direccion de residencia
                $pdf->SetXY(85, 62.4);
                $pdf->Write(0, convertToISO88591($inability->direccion_residencia));

                // Teléfono
                $pdf->SetXY(162, 62.4);
                $pdf->Write(0, convertToISO88591($inability->telefono_fijo));

                // celular
                $pdf->SetXY(73, 66);
                $pdf->Write(0, convertToISO88591($inability->celular));

                // ciudad
                $pdf->SetXY(122, 66);
                $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

                // departamento
                $pdf->SetXY(164, 66);
                $pdf->Write(0, "Cundinamarca");

                // ocupacion
                $pdf->SetXY(50, 69.5);
                $pdf->Write(0, convertToISO88591($inability->ocupacion_asegurado));

                // correo electronico
                $pdf->SetXY(119, 69.5);
                $pdf->Write(0, convertToISO88591($inability->email_corporativo));

                // valor servicios
                $pdf->SetXY(158, 236.5);
                $pdf->Write(0, convertToISO88591($inability->val_prevexequial_eclusivo));

                // nombre titular contratante
                $nombres_completos = $inability->primer_apellido.' '.$inability->segundo_apellido.' '.$inability->nombres_completos;
                $pdf->SetXY(46.5, 261.5);
                $pdf->Write(0, convertToISO88591($nombres_completos));

                // n documento titular contratante
                $pdf->SetXY(54.5, 264.5);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // nombre titular contratante
                $pdf->SetXY(134, 261.5);
                $pdf->Write(0, convertToISO88591($inability->nombre_asesor));

                // n documento titular contratante
                $pdf->SetXY(133, 264.5);
                $pdf->Write(0, convertToISO88591($inability->codigo_asesor));
            }
        }

        // Salida del PDF
        $pdf->Output('I', 'DocumentoGenerado.pdf');
    }
}
