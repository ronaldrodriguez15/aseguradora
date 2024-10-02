<?php

namespace App\Http\Controllers;

use setasign\Fpdi\Fpdi;
use App\Models\Inability;
use Illuminate\Support\Facades\Storage;
use App\Models\Docuuments;

class PDFController extends Controller
{
    public function generarPDF($id)
    {
        // Obtener los datos de la tabla inabilities
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        // Obtener el documento desde el modelo Documents
        $document = Docuuments::first();

        // Asegúrate de que exista el documento
        if (!$document || empty($document->estasseguro_document)) {
            abort(404, 'Documento no encontrado.');
        }

        // Obtener la ruta del archivo PDF almacenado
        $pdfFilePath = $document->estasseguro_document;

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

        // Recorrer todas las páginas del PDF original
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Importar cada página de la plantilla
            $tplIdx = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);

            // Pagina 1
            if ($pageNo == 1) {

                // Agregar datos al PDF
                $pdf->SetFont('Arial', '', 8);

                // Extraer el año y el mes de la fecha
                $fecha = $inability->fecha_diligenciamiento;
                $ano = date('Y', strtotime($fecha));
                $mes = date('n', strtotime($fecha));

                // No de Contrato
                $pdf->SetXY(168, 29);
                $pdf->Write(0, $ano . $mes . str_replace('.', '', $inability->no_identificacion));


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
                $pdf->Write(0, convertToISO88591($inability->fecha_nacimiento_asesor));

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

                // tipo pago
                if ($inability->forma_pago === 'debito_automatico') {
                    $pdf->SetXY(83, 229);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Write(0, convertToISO88591('Mensual'));
                    $pdf->SetXY(83, 232);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Write(0, convertToISO88591('DebAuto'));
                } else {
                    $pdf->SetXY(83, 229);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Write(0, convertToISO88591('Mensual'));
                    $pdf->SetXY(83, 232);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Write(0, convertToISO88591('DebAuto'));
                }

                $pdf->SetFont('Arial', '', 8);

                // valor servicios
                $pdf->SetXY(158, 236.5);
                $pdf->Write(0, convertToISO88591($inability->val_prevexequial_eclusivo));

                // nombre titular contratante
                $nombres_completos = $inability->primer_apellido . ' ' . $inability->segundo_apellido . ' ' . $inability->nombres_completos;
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

            // Pagina 2
            if ($pageNo == 2) {

                // Agregar datos al PDF
                $pdf->SetFont('Arial', '', 7);

                // Nombres completos
                $nombres_completos = $inability->nombres_completos . ' ' . $inability->primer_apellido . ' ' . $inability->segundo_apellido;
                $pdf->SetXY(37, 108);
                $pdf->Write(0, $nombres_completos);

                // no documento
                $pdf->SetXY(125, 108);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // expedida en
                $pdf->SetXY(162, 108);
                $pdf->Write(0, convertToISO88591($inability->ciudad_expedicion));

                // ocupacion
                $pdf->SetXY(50, 111.6);
                $pdf->Write(0, convertToISO88591($inability->ocupacion_asegurado));

                // fecha nacimiento
                $pdf->SetXY(60, 118);
                $pdf->Write(0, convertToISO88591($inability->fecha_nacimiento_asesor));

                // valor IBC basico
                $pdf->SetXY(91, 121);
                $pdf->Write(0, $inability->valor_ibc_basico);

                // entidad donde labora
                $pdf->SetXY(70.5, 125);
                $pdf->Write(0, convertToISO88591($inability->entidad_pagadora_sucursal));

                // Correo electronico
                $pdf->SetXY(65, 128.4);
                $pdf->Write(0, convertToISO88591($inability->email_corporativo));

                // Nombres y No documento
                $pdf->SetXY(46, 196);
                $pdf->Write(0, $nombres_completos);
                $pdf->SetXY(54, 199);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));
            }

            // Pagina 3
            if ($pageNo == 3) {

                // Agregar datos al PDF
                $pdf->SetFont('Arial', '', 8);

                // ------------------ Referido 1
                // nombres y apellidos
                $pdf->SetXY(70, 121.5);
                $pdf->Write(0, $inability->nombres_apellidos_r1);

                // telefono de contacto
                $pdf->SetXY(70, 124.8);
                $pdf->Write(0, convertToISO88591($inability->telefono_r1));

                // entidad
                $pdf->SetXY(70, 128);
                $pdf->Write(0, convertToISO88591($inability->entidad_r1));

                // ------------------ Referido 2
                // nombres y apellidos
                $pdf->SetXY(70, 139);
                $pdf->Write(0, $inability->nombres_apellidos_r2);

                // telefono de contacto
                $pdf->SetXY(70, 142.8);
                $pdf->Write(0, convertToISO88591($inability->telefono_r2));

                // entidad
                $pdf->SetXY(70, 146);
                $pdf->Write(0, convertToISO88591($inability->entidad_r2));

                // ------------------ Referido 3
                // nombres y apellidos
                $pdf->SetXY(70, 157);
                $pdf->Write(0, $inability->nombres_apellidos_r3);

                // telefono de contacto
                $pdf->SetXY(70, 160.5);
                $pdf->Write(0, convertToISO88591($inability->telefono_r3));

                // entidad
                $pdf->SetXY(70, 163.8);
                $pdf->Write(0, convertToISO88591($inability->entidad_r3));

                // tiene mascotas
                $pdf->SetXY(70, 171);
                $pdf->Write(0, convertToISO88591($inability->tienes_mascotas));

                // ------------------ Mascota 1
                // nombres
                $pdf->SetXY(50, 181.3);
                $pdf->Write(0, $inability->nombre_m1);

                // tipo de mascotas
                $pdf->SetXY(80, 181.3);
                $pdf->Write(0, $inability->tipo_m1);

                // raza
                $pdf->SetXY(104, 181.5);
                $pdf->Write(0, $inability->raza_m1);

                // color
                $pdf->SetXY(118, 181.5);
                $pdf->Write(0, $inability->color_m1);

                // genero
                $pdf->SetXY(136, 181.5);
                $pdf->Write(0, $inability->genero_m1);

                // edad
                $pdf->SetXY(158, 181.5);
                $pdf->Write(0, $inability->edad_m1 . " " . convertToISO88591("Años"));

                // ------------------ Mascota 2
                // nombres
                $pdf->SetXY(50, 185);
                $pdf->Write(0, $inability->nombre_m2);

                // tipo de mascotas
                $pdf->SetXY(80, 185);
                $pdf->Write(0, $inability->tipo_m2);

                // raza
                $pdf->SetXY(104, 185);
                $pdf->Write(0, $inability->raza_m2);

                // color
                $pdf->SetXY(118, 185);
                $pdf->Write(0, $inability->color_m2);

                // genero
                $pdf->SetXY(136, 185);
                $pdf->Write(0, $inability->genero_m2);

                // edad
                $pdf->SetXY(158, 185);
                $pdf->Write(0, $inability->edad_m2 . " " . convertToISO88591("Años"));

                // ------------------ Mascota 2
                // nombres
                $pdf->SetXY(50, 188.5);
                $pdf->Write(0, $inability->nombre_m3);

                // tipo de mascotas
                $pdf->SetXY(80, 188.5);
                $pdf->Write(0, $inability->tipo_m3);

                // raza
                $pdf->SetXY(104, 188.5);
                $pdf->Write(0, $inability->raza_m3);

                // color
                $pdf->SetXY(118, 188.5);
                $pdf->Write(0, $inability->color_m3);

                // genero
                $pdf->SetXY(136, 188.5);
                $pdf->Write(0, $inability->genero_m3);

                // edad
                $pdf->SetXY(158, 188.5);
                $pdf->Write(0, $inability->edad_m3 . " " . convertToISO88591("Años"));
            }
        }

        // Salida del PDF
        $pdf->Output('I', 'DocumentoGenerado.pdf');
    }
}
