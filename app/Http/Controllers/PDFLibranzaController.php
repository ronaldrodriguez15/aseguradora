<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use App\Models\Docuuments;
use App\Models\City;

class PDFLibranzaController extends Controller
{
    public function generarPDF($id)
    {
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        $document = Docuuments::first();

        if (!$document) {
            abort(404, 'Documento no encontrado.');
        }

        $useFondoTemplate = $this->shouldUseFondoTemplate($inability);
        $templatePath = $useFondoTemplate
            ? $document->fondo_empleados_document
            : $document->libranza_document;

        if (empty($templatePath)) {
            abort(404, 'Documento no encontrado.');
        }

        if (!Storage::disk('public')->exists($templatePath)) {
            abort(404, 'Archivo PDF no encontrado.');
        }

        $fullPath = Storage::disk('public')->path($templatePath);
        
        $storageFolder = $useFondoTemplate ? 'documentos_fondo' : 'documentos_libranza';

        // Usar el método correspondiente según el tipo de plantilla
        if ($useFondoTemplate) {
            $this->generarPDFConPlantillaFondo($inability, $fullPath, $storageFolder);
        } else {
            $this->generarPDFConPlantilla($inability, $fullPath, $storageFolder);
        }
        
        // Generar la URL pública del archivo PDF generado
        $pdfUrl = asset('storage/' . $inability->path_pago);

        // Retornar la URL del PDF generado
        return response()->json(['pdf_url' => $pdfUrl]);
    }

    private function shouldUseFondoTemplate(Inability $inability): bool
    {
        if ($inability->forma_pago !== 'mensual_libranza') {
            return false;
        }

        $fondoName = trim((string) $inability->fondo_entity_name);

        if ($fondoName === '') {
            return false;
        }

        return strcasecmp($fondoName, 'ESTASSEGURO ADMINISTRADORA DE BENEFICIOS SAS') !== 0;
    }


    private function generarPDFConPlantilla($inability, $pdfFilePath, $storageFolder)
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
                $pdf->SetFont('Arial', '', 6);
                $pdf->SetXY(93, 40.2);
                $pdf->MultiCell(40, 3, convertToISO88591($inability->entidad_pagadora_sucursal));

                //Nemomico
                $pdf->SetFont('Arial', '', 8);
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
                $ciudadExpedicion = City::find($inability->ciudad_expedicion);
                $nombreCiudadExpedicion = $ciudadExpedicion ? $ciudadExpedicion->name : '';
                $pdf->SetXY(173, 48.8);
                $pdf->Write(0, convertToISO88591($nombreCiudadExpedicion));

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
        $rutaCarpeta = storage_path('app/public/' . $storageFolder);
        if (!file_exists($rutaCarpeta)) {
            // Crea la carpeta si no existe
            if (!mkdir($rutaCarpeta, 0755, true)) {
                die('Error al crear la carpeta: ' . $rutaCarpeta);
            }
        }

        $rutaArchivo = $rutaCarpeta . '/' . $nombreArchivo;

        $pdf->Output('F', $rutaArchivo); // 'F' indica que se guarda en un archivo

        $inability->path_pago = $storageFolder . '/' . $nombreArchivo;
        $inability->save();

        // Retorna la ruta del archivo
        return $nombreArchivo;
    }

    private function generarPDFConPlantillaFondo($inability, $pdfFilePath, string $storageFolder)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfFilePath);

        // Función para convertir texto a ISO-8859-1
        function convertToISO88591Fondo($text)
        {
            return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
        }

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplIdx = $pdf->importPage($pageNo);

            $pdf->AddPage('P', 'Letter');
            $pdf->useTemplate($tplIdx);
            $pdf->SetAutoPageBreak(false);
            if ($pageNo == 1) {
                // Agregar datos al PDF - AJUSTA LAS COORDENADAS SEGÚN TU PLANTILLA
                $pdf->SetFont('Arial', '', 8);
                
                //Nombre entidad pagadora 
                $pdf->SetXY(110, 35);
                $pdf->Write(0, convertToISO88591Fondo($inability->fondo_entity_name));

                //Fecha diligenciamiento
                $pdf->SetXY(30, 42);
                $pdf->Write(0, convertToISO88591Fondo($inability->fecha_diligenciamiento));

                //Entidad pagadora
                $pdf->SetFont('Arial', '', 6);
                $pdf->SetXY(83, 40.2);
                $pdf->MultiCell(40, 3, convertToISO88591Fondo($inability->entidad_pagadora_sucursal));

                //Nemomico
                $pdf->SetFont('Arial', '', 8);
                $texto = $inability->entidad_pagadora_sucursal;
                $regex = '/\(([^)]+)\)/';

                // Extraer el texto dentro de los paréntesis
                if (preg_match($regex, $texto, $coincidencia)) {
                    $textoExtraido = $coincidencia[1];
                    $pdf->SetXY(150, 40.2);
                    $pdf->Write(0, convertToISO88591Fondo($textoExtraido));
                }

                //Sucursal
                $pdf->SetXY(185, 42);
                $pdf->Write(0, '5');

                // nombre titular contratante
                $nombres_completos = $inability->primer_apellido . ' ' . $inability->segundo_apellido . ' ' . $inability->nombres_completos;
                $pdf->SetXY(35, 48.8);
                $pdf->Write(0, convertToISO88591Fondo($nombres_completos));

                //Cedula
                $pdf->SetXY(128, 48.8);
                $pdf->Write(0, convertToISO88591Fondo($inability->no_identificacion));

                //expedicion
                $ciudadExpedicionFondo = City::find($inability->ciudad_expedicion);
                $nombreCiudadExpedicionFondo = $ciudadExpedicionFondo ? $ciudadExpedicionFondo->name : '';
                $pdf->SetXY(173, 48.8);
                $pdf->Write(0, convertToISO88591Fondo($nombreCiudadExpedicionFondo));

                //calidad de
                $pdf->SetXY(55, 53);
                $pdf->Write(0, convertToISO88591Fondo($inability->ocupacion_asegurado));

                // No de Contrato
                // Extraer el año y el mes de la fecha
                $fecha = $inability->fecha_diligenciamiento;
                $ano = date('Y', strtotime($fecha));
                $mes = date('n', strtotime($fecha));
                $pdf->SetXY(143, 62);
                $pdf->Write(0, $ano . $mes . str_replace('.', '', $inability->no_identificacion));

                //previexequial exclusivo
                // if ($inability->servicios_prevision_exequial === 'si') {
                //     $pdf->SetXY(102, 65);
                //     $pdf->Write(0, 'X');
                // }

                //prevision exequial mascotas
                if ($inability->serv_prevision_exequial_mascotas === 'si') {
                    $pdf->SetXY(169.5, 65.2);
                    $pdf->Write(0, 'X');
                }

                //servicios de prevision salud mascotas
                // if ($inability->serv_prevision_salud === 'si') {
                //     $pdf->SetXY(102, 69);
                //     $pdf->Write(0, 'X');
                // }

                //beneficio diario por incapacidad temporal
                // if ($inability->beneficiario_diario_inc_temp === 'si') {
                //     $pdf->SetXY(102, 81);
                //     $pdf->Write(0, 'X');
                // }

                //No poliza
                // $pdf->SetXY(154, 81);
                // $pdf->Write(0, convertToISO88591Fondo($inability->no_poliza));

                //valor descuento
                $pdf->SetXY(58, 218.5);
                $pdf->Write(0, $inability->val_total_desc_mensual);

                //CC
                $pdf->SetXY(30, 248);
                $pdf->Write(0, convertToISO88591Fondo($inability->no_identificacion));

                //direccion
                $pdf->SetXY(107, 248);
                $pdf->Write(0, convertToISO88591Fondo($inability->direccion_residencia));

                //celular
                $pdf->SetXY(34, 251.6);
                $pdf->Write(0, convertToISO88591Fondo($inability->celular));

                //tel fijo
                $pdf->SetXY(100, 251.6);
                $pdf->Write(0, convertToISO88591Fondo($inability->telefono_fijo));
            }
        }

        $numeroDocumento = $inability->no_identificacion;
        $fechaActual = date('Y-m-d');
        $nombreArchivo = 'documento_' . $numeroDocumento . '_' . $fechaActual . '.pdf';

        // Ruta donde se va a almacenar el PDF
        $rutaCarpeta = storage_path('app/public/' . $storageFolder);
        if (!file_exists($rutaCarpeta)) {
            // Crea la carpeta si no existe
            if (!mkdir($rutaCarpeta, 0755, true)) {
                die('Error al crear la carpeta: ' . $rutaCarpeta);
            }
        }

        $rutaArchivo = $rutaCarpeta . '/' . $nombreArchivo;

        $pdf->Output('F', $rutaArchivo); // 'F' indica que se guarda en un archivo

        $inability->path_pago = $storageFolder . '/' . $nombreArchivo;
        $inability->save();

        // Retorna la ruta del archivo
        return $nombreArchivo;
    }
}
