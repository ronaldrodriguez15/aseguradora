<?php

namespace App\Http\Controllers;

use App\Models\Insurer;
use App\Models\Inability;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Carbon;

class PDFConfianzaController extends Controller
{
    public function generarPDF($id)
    {
        // Obtener los datos de la tabla inabilities
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        $insurer = Insurer::find($inability->insurer_id);

        $pdfFilePath = public_path("storage/" . $insurer->document_path);

        if (!file_exists($pdfFilePath)) {
            abort(404, 'Archivo PDF no encontrado en la ruta especificada.');
        }

        // Generar el PDF con la plantilla
        $nombreArchivoGenerado = $this->generarPDFConPlantilla($inability, $pdfFilePath);

        // Generar la URL pública del archivo PDF generado
        $pdfUrl = asset('storage/' . $inability->path_aseguradora);

        // Retornar la URL del PDF generado
        return response()->json(['pdf_url' => $pdfUrl]);
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

            // Pagina 1
            if ($pageNo == 1) {
                // Agregar datos al PDF
                $pdf->SetFont('Arial', '', 8);

                //consecutivo
                $pdf->SetXY(136, 17);
                $pdf->Write(0, convertToISO88591($inability->no_solicitud));

                //Fecha diligenciamiento
                $pdf->SetXY(108, 22.6);
                $pdf->Write(0, convertToISO88591($inability->fecha_diligenciamiento));

                // Nombre asesor
                $pdf->SetXY(55, 33);
                $pdf->Write(0, convertToISO88591($inability->nombre_asesor));

                // Codigo asesor
                $pdf->SetXY(104, 33);
                $pdf->Write(0, convertToISO88591($inability->codigo_asesor));

                // Primer apellido
                $pdf->SetXY(22, 44.5);
                $pdf->Write(0, convertToISO88591($inability->primer_apellido));

                // Segundo apellido
                $pdf->SetXY(50, 44.5);
                $pdf->Write(0, convertToISO88591($inability->segundo_apellido));

                // Nombres completos
                $pdf->SetXY(83, 44.5);
                $pdf->Write(0, convertToISO88591($inability->nombres_completos));

                // tipo identificación
                if ($inability->tipo_identificacion === 'cedula_ciudadania') {
                    $pdf->SetXY(126, 44.5);
                    $pdf->Write(0, 'X');
                } else if ($inability->tipo_identificacion === 'cedula_extranjeria') {
                    $pdf->SetXY(139, 44.5);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(151, 44.5);
                    $pdf->Write(0, 'X');
                }

                // No identificacion
                $pdf->SetXY(22, 50);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // Fecha nacimiento
                $pdf->SetXY(25, 52.5);
                $pdf->Write(0, convertToISO88591($inability->fecha_nacimiento_asesor));

                // Ciudad
                $pdf->SetXY(62, 52.5);
                $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

                $pdf->SetXY(100, 52.5);
                $pdf->Write(0, convertToISO88591('Cundinamarca'));

                // Telefono
                $pdf->SetXY(133, 52.5);
                $pdf->Write(0, convertToISO88591($inability->telefono_fijo));

                // Ocupacion
                $pdf->SetXY(157, 52.5);
                $pdf->Write(0, convertToISO88591($inability->ocupacion_asegurado));

                //-----------------------------------------------

                // celular
                $pdf->SetXY(24, 60.5);
                $pdf->Write(0, convertToISO88591($inability->celular));

                // email_corporativo
                $pdf->SetXY(65, 60.5);
                $pdf->Write(0, convertToISO88591($inability->email_corporativo));

                // edad
                $pdf->SetXY(133, 60.5);
                $pdf->Write(0, convertToISO88591($inability->edad));

                //nombre empresa
                $pdf->SetFont('Arial', '', 6);
                $pdf->SetXY(150, 60.5);
                $pdf->Write(0, convertToISO88591($inability->entidad_pagadora_sucursal));

                // amparo basico
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(102, 83);
                $pdf->Write(0, '$ ' . $inability->amparo_basico);

                // Valor IBC basico
                $pdf->SetXY(104, 77);
                $pdf->Write(0, '$ ' . $inability->valor_ibc_basico);

                // valor adicional
                $pdf->SetXY(139, 77);
                $pdf->Write(0, '$ ' . $inability->valor_adicional);

                // total
                $pdf->SetXY(170, 77);
                $pdf->Write(0, '$ ' . $inability->total);

                // amparo basico
                $pdf->SetXY(170, 83);
                $pdf->Write(0, '$ ' . $inability->amparo_basico);

                // prima_pago_prima_seguro 
                $pdf->SetXY(170, 93);
                $pdf->Write(0, '$ ' . $inability->prima_pago_prima_seguro);

                // ------------------ Beneficiarios 1

                $documento_abreviado = '';

                // Evaluamos el tipo de documento
                if ($inability->tipo_identidad_s1 === 'cedula_ciudadania') {
                    $documento_abreviado = 'CC';
                } elseif ($inability->tipo_identidad_s1 === 'cedula_extranjeria') {
                    $documento_abreviado = 'CE';
                } elseif ($inability->tipo_identidad_s1 === 'documento_identificacion') {
                    $documento_abreviado = 'DI';
                } elseif ($inability->tipo_identidad_s1 === 'nit') {
                    $documento_abreviado = 'NIT';
                } elseif ($inability->tipo_identidad_s1 === 'pasaporte') {
                    $documento_abreviado = 'PAS';
                } elseif ($inability->tipo_identidad_s1 === 'registro_civil') {
                    $documento_abreviado = 'RC';
                } elseif ($inability->tipo_identidad_s1 === 'tarjeta_extranjeria') {
                    $documento_abreviado = 'TE';
                } elseif ($inability->tipo_identidad_s1 === 'tarjeta_identidad') {
                    $documento_abreviado = 'TI';
                }

                // tipo identidad
                $pdf->SetXY(29.4, 110.5);
                $pdf->Write(0, convertToISO88591($documento_abreviado));

                // no identificacion
                $pdf->SetXY(30, 110.5);
                $pdf->Write(0, convertToISO88591($inability->n_identificacion_s1));

                // nombres y apellidos
                $pdf->SetXY(90, 110.5);
                $pdf->Write(0, convertToISO88591($inability->nombres_s1 . ' ' . $inability->apellidos_s1));

                // parentesco
                $pdf->SetXY(153, 110.5);
                $pdf->Write(0, convertToISO88591($inability->parentesco_s1));

                // %
                $pdf->SetXY(180, 110.5);
                $pdf->Write(0, $inability->porcentaje_s1);

                // ------------------ Referido 2
                $documento_abreviado = '';

                // Evaluamos el tipo de documento
                if ($inability->tipo_identidad_s2 === 'cedula_ciudadania') {
                    $documento_abreviado = 'CC';
                } elseif ($inability->tipo_identidad_s2 === 'cedula_extranjeria') {
                    $documento_abreviado = 'CE';
                } elseif ($inability->tipo_identidad_s2 === 'documento_identificacion') {
                    $documento_abreviado = 'DI';
                } elseif ($inability->tipo_identidad_s2 === 'nit') {
                    $documento_abreviado = 'NIT';
                } elseif ($inability->tipo_identidad_s2 === 'pasaporte') {
                    $documento_abreviado = 'PAS';
                } elseif ($inability->tipo_identidad_s2 === 'registro_civil') {
                    $documento_abreviado = 'RC';
                } elseif ($inability->tipo_identidad_s2 === 'tarjeta_extranjeria') {
                    $documento_abreviado = 'TE';
                } elseif ($inability->tipo_identidad_s2 === 'tarjeta_identidad') {
                    $documento_abreviado = 'TI';
                }

                // tipo identidad
                $pdf->SetXY(29.4, 112.5);
                $pdf->Write(0, convertToISO88591($documento_abreviado));

                // no identificacion
                $pdf->SetXY(30, 112.5);
                $pdf->Write(0, convertToISO88591($inability->n_identificacion_s2));

                // nombres y apellidos
                $pdf->SetXY(90, 112.5);
                $pdf->Write(0, convertToISO88591($inability->nombres_s2 . ' ' . $inability->apellidos_s2));

                // parentesco
                $pdf->SetXY(153, 112.5);
                $pdf->Write(0, convertToISO88591($inability->parentesco_s2));

                // %
                $pdf->SetXY(180, 112.5);
                $pdf->Write(0, $inability->porcentaje_s2);

                // ------------------ Referido 3
                $documento_abreviado = '';

                // Evaluamos el tipo de documento
                if ($inability->tipo_identidad_s2 === 'cedula_ciudadania') {
                    $documento_abreviado = 'CC';
                } elseif ($inability->tipo_identidad_s3 === 'cedula_extranjeria') {
                    $documento_abreviado = 'CE';
                } elseif ($inability->tipo_identidad_s3 === 'documento_identificacion') {
                    $documento_abreviado = 'DI';
                } elseif ($inability->tipo_identidad_s3 === 'nit') {
                    $documento_abreviado = 'NIT';
                } elseif ($inability->tipo_identidad_s3 === 'pasaporte') {
                    $documento_abreviado = 'PAS';
                } elseif ($inability->tipo_identidad_s3 === 'registro_civil') {
                    $documento_abreviado = 'RC';
                } elseif ($inability->tipo_identidad_s3 === 'tarjeta_extranjeria') {
                    $documento_abreviado = 'TE';
                } elseif ($inability->tipo_identidad_s3 === 'tarjeta_identidad') {
                    $documento_abreviado = 'TI';
                }

                // tipo identidad
                $pdf->SetXY(29.4, 112.5);
                $pdf->Write(0, convertToISO88591($documento_abreviado));

                // no identificacion
                $pdf->SetXY(30, 112.5);
                $pdf->Write(0, convertToISO88591($inability->n_identificacion_s3));

                // nombres y apellidos
                $pdf->SetXY(90, 112.5);
                $pdf->Write(0, convertToISO88591($inability->nombres_s3 . ' ' . $inability->apellidos_s3));

                // parentesco
                $pdf->SetXY(153, 112.5);
                $pdf->Write(0, convertToISO88591($inability->parentesco_s3));

                // %
                $pdf->SetXY(180, 112.5);
                $pdf->Write(0, $inability->porcentaje_s3);

                // ------------------ Declaraciòn de asegurabilidad

                // cardiovasculares
                $pdf->SetXY(67, 148.4);
                $pdf->Write(0, 'X');

                $pdf->SetXY(77, 148.4);
                $pdf->Write(0, 'X');

                // enf_cerebrovasculares
                if ($inability->enf_cerebrovasculares === 'si') {
                    $pdf->SetXY(67, 152.4);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(77, 152.4);
                    $pdf->Write(0, 'X');
                }

                // cancer
                if ($inability->cancer === 'si') {
                    $pdf->SetXY(67, 156.4);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(77, 156.4);
                    $pdf->Write(0, 'X');
                }

                // diabetes
                if ($inability->diabetes === 'si') {
                    $pdf->SetXY(67, 160.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(77, 160.2);
                    $pdf->Write(0, 'X');
                }

                // infecciones vih
                if ($inability->infeccion_vih === 'si') {
                    $pdf->SetXY(138, 163.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(77, 163.2);
                    $pdf->Write(0, 'X');
                }

                // renal cronica
                if ($inability->corazon === 'si') {
                    $pdf->SetXY(67, 167.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(77, 167.2);
                    $pdf->Write(0, 'X');
                }

                // pulmones
                if ($inability->pulmones === 'si') {
                    $pdf->SetXY(67, 171.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(77, 171.2);
                    $pdf->Write(0, 'X');
                }

                //----------------------------------------------------

                // hospitalizacion intervencion quirurgica
                if ($inability->hospitalizacion_intervencion_quirurgica === 'si') {
                    $pdf->SetXY(139, 148.4);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(149, 148.4);
                    $pdf->Write(0, 'X');
                }

                // alcoholismo
                if ($inability->alcoholismo === 'si') {
                    $pdf->SetXY(139, 152.4);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(149, 152.4);
                    $pdf->Write(0, 'X');
                }
                // tabaquismo
                if ($inability->tabaquismo === 'si') {
                    $pdf->SetXY(139, 156.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(149, 156.2);
                    $pdf->Write(0, 'X');
                }

                // presion arterial
                if ($inability->presion_arterial === 'si') {
                    $pdf->SetXY(139, 160.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(149, 160.2);
                    $pdf->Write(0, 'X');
                }

                // enf_congenitas
                if ($inability->enf_congenitas === 'si') {
                    $pdf->SetXY(139, 163.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(149, 163.2);
                    $pdf->Write(0, 'X');
                }

                // colageno
                if ($inability->enf_colageno === 'si') {
                    $pdf->SetXY(179, 167.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(149, 167.2);
                    $pdf->Write(0, 'X');
                }

                // enf_hematologicas
                if ($inability->enf_hematologicas === 'si') {
                    $pdf->SetXY(179, 171.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(149, 171.2);
                    $pdf->Write(0, 'X');
                }

                // descripcion enfermedades
                if ($inability->descripcion_de_enfermedades !== null) {
                    $pdf->SetXY(30, 185.5);
                    $pdf->Write(0, convertToISO88591($inability->descripcion_de_enfermedades));
                }


                // ciudad_expedicion
                $pdf->SetXY(50, 201);
                $pdf->Write(0, convertToISO88591($inability->ciudad_expedicion));

                $fecha = Carbon::parse($inability->fecha_diligenciamiento);

                $dia = $fecha->day;
                $mes = $fecha->month;
                $ano = $fecha->year;

                // Mostrar en el PDF el día y el mes

                // dia
                $pdf->SetXY(82, 201);
                $pdf->Write(0, convertToISO88591($dia));

                // mes
                $pdf->SetXY(115, 201);
                $pdf->Write(0, convertToISO88591($mes));

                // ano
                $pdf->SetXY(133, 201);
                $pdf->Write(0, convertToISO88591($ano));
            }
        }

        $numeroDocumento = $inability->no_identificacion;
        $fechaActual = date('Y-m-d');
        $nombreArchivo = 'documento_' . $numeroDocumento . '_' . $fechaActual . '.pdf';


        // Ruta donde se va a almacenar el PDF
        $rutaCarpeta = storage_path('app/public/documentos_confianza');
        if (!file_exists($rutaCarpeta)) {
            // Crea la carpeta si no existe
            if (!mkdir($rutaCarpeta, 0755, true)) {
                die('Error al crear la carpeta: ' . $rutaCarpeta);
            }
        }

        $rutaArchivo = $rutaCarpeta . '/' . $nombreArchivo;

        $pdf->Output('F', $rutaArchivo); // 'F' indica que se guarda en un archivo

        $inability->path_aseguradora = 'documentos_confianza/' . $nombreArchivo;
        $inability->save();

        // Retorna la ruta del archivo
        return $nombreArchivo;
    }
}
