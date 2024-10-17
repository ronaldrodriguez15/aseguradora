<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inability;
use App\Models\Insurer;
use setasign\Fpdi\Fpdi;

class PDFPositivaController extends Controller
{
    public function generarPDF($id)
    {
        // Obtener los datos de la tabla inabilities
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        $insurer = Insurer::find($inability->insurer_id);

        // Verificar que el asegurador exista
        if (!$insurer) {
            abort(404, 'Aseguradora no encontrada.');
        }

        // Obtener la ruta del archivo PDF almacenado
        $pdfFilePath = public_path("storage/" . $insurer->document_path);

        // Verificar si el archivo existe
        if (!file_exists($pdfFilePath)) {
            abort(404, 'Archivo PDF no encontrado en la ruta especificada.');
        }

        // Obtener el consecutivo máximo en la tabla inabilities
        $maxInability = Inability::orderBy('no_solicitud', 'desc')->first();

        // Verificar el consecutivo máximo
        $maxConsecutivo = $maxInability ? $maxInability->no_solicitud : 0;

        // Verificar si el consecutivo actual es menor que el máximo
        if ($inability->no_solicitud < $maxConsecutivo) {
            // Actualizar el consecutivo al máximo + 1
            $inability->no_solicitud = $maxConsecutivo + 1;
        } else {
            // Si el consecutivo actual es mayor o igual, incrementar en 1
            $inability->no_solicitud += 1;
        }

        // Guardar los cambios en el registro
        $inability->save();

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

            // Pagina 1
            if ($pageNo == 1) {
                // Agregar datos al PDF
                $pdf->SetFont('Arial', '', 8);

                //Fecha diligenciamiento
                $pdf->SetXY(57, 25);
                $pdf->Write(0, convertToISO88591($inability->fecha_diligenciamiento));

                //consecutivo
                $insurer = Insurer::find($inability->insurer_id);
                $pdf->SetXY(134, 12.8);
                $pdf->Write(0, convertToISO88591($insurer->identificador));

                // Nombre asesor
                $pdf->SetXY(57, 43);
                $pdf->Write(0, convertToISO88591($inability->nombre_asesor));

                // Codigo asesor
                $pdf->SetXY(106, 43);
                $pdf->Write(0, convertToISO88591($inability->codigo_asesor));

                // Valor IBC basico
                $pdf->SetXY(100, 64);
                $pdf->Write(0, '$ ' . $inability->valor_ibc_basico);

                // valor adicional
                $pdf->SetXY(138, 64);
                $pdf->Write(0, '$ ' . $inability->valor_adicional);

                // total
                $pdf->SetXY(170, 64);
                $pdf->Write(0, '$ ' . $inability->total);

                // amparo basico
                $pdf->SetXY(100, 69.5);
                $pdf->Write(0, '$ ' . $inability->amparo_basico);

                // amparo basico
                $pdf->SetXY(170, 69.5);
                $pdf->Write(0, '$ ' . $inability->amparo_basico);

                // prima pago prima seguro
                $pdf->SetXY(172, 75.5);
                $pdf->Write(0, '$ ' . $inability->prima_pago_prima_seguro);

                // Primer apellido
                $pdf->SetXY(39, 88);
                $pdf->Write(0, convertToISO88591($inability->primer_apellido));

                // Segundo apellido
                $pdf->SetXY(93, 88);
                $pdf->Write(0, convertToISO88591($inability->segundo_apellido));

                // Nombres completos
                $pdf->SetXY(153, 88);
                $pdf->Write(0, convertToISO88591($inability->nombres_completos));

                // No identificacion
                $pdf->SetXY(27, 98);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // tipo identificación
                if ($inability->tipo_identificacion === 'cedula_ciudadania') {
                    $pdf->SetXY(59.5, 98);
                    $pdf->Write(0, 'X');
                } else if ($inability->tipo_identificacion === 'cedula_extranjeria') {
                    $pdf->SetXY(69.5, 98);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(79.5, 98);
                    $pdf->Write(0, 'X');
                }

                // fecha de nacimiento
                $pdf->SetXY(95, 98);
                $pdf->Write(0, convertToISO88591($inability->fecha_nacimiento_asesor));

                // ciudad
                $pdf->SetXY(137, 98);
                $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

                // departamento
                $pdf->SetXY(172, 98);
                $pdf->Write(0, "Cundinamarca");

                // Genero
                if ($inability->genero === 'masculino') {
                    $pdf->SetXY(46, 103.5);
                    $pdf->Write(0, 'X');
                } else if ($inability->genero === 'femenino') {
                    $pdf->SetXY(35.5, 103.5);
                    $pdf->Write(0, 'X');
                }

                // direccion residencia
                $pdf->SetXY(85, 103.5);
                $pdf->Write(0, convertToISO88591($inability->direccion_residencia));

                // telefono
                $pdf->SetXY(170, 103.5);
                $pdf->Write(0, convertToISO88591($inability->telefono_fijo));

                // celular
                $pdf->SetXY(69, 108);
                $pdf->Write(0, convertToISO88591($inability->celular));

                // ciudad
                $pdf->SetXY(120, 108);
                $pdf->Write(0, convertToISO88591($inability->ciudad_residencia));

                // departamento
                $pdf->SetXY(175, 108);
                $pdf->Write(0, "Cundinamarca");

                // ocupacion
                $pdf->SetXY(39, 112.5);
                $pdf->Write(0, convertToISO88591($inability->ocupacion_asegurado));

                // eps actual
                $pdf->SetXY(120, 112.5);
                $pdf->Write(0, convertToISO88591($inability->nombre_eps));

                // descuento eps
                $pdf->SetXY(175, 112.5);
                $pdf->Write(0, '$ ' . $inability->descuento_eps);

                // email corporativo
                $pdf->SetXY(58, 116.5);
                $pdf->Write(0, convertToISO88591($inability->email_corporativo));

                // fuente recursos
                $pdf->SetXY(130, 123);
                $pdf->Write(0, convertToISO88591($inability->fuente_recursos));

                // ------------------ Referido 1
                // nombres y apellidos
                $pdf->SetXY(48, 141);
                $pdf->Write(0, convertToISO88591($inability->nombres_s1 . ' ' . $inability->apellidos_s1));

                // parentesco
                $pdf->SetXY(100, 141);
                $pdf->Write(0, convertToISO88591($inability->parentesco_s1));

                // %
                $pdf->SetXY(121.5, 141);
                $pdf->Write(0, $inability->porcentaje_s1);

                // calidad
                if ($inability->nombres_s1 !== null) {
                    $pdf->SetXY(134, 141);
                    $pdf->Write(0, 'Gratuito');
                }

                // tipo identidad
                $pdf->SetXY(152, 141);
                $pdf->Write(0, convertToISO88591($inability->tipo_identidad_s1));

                // no identificacion
                $pdf->SetXY(179.5, 141);
                $pdf->Write(0, convertToISO88591($inability->n_identificacion_s1));

                // ------------------ Referido 2
                // nombres y apellidos
                $pdf->SetXY(48, 144.3);
                $pdf->Write(0, convertToISO88591($inability->nombres_s2 . ' ' . $inability->apellidos_s2));

                // parentesco
                $pdf->SetXY(100, 144.3);
                $pdf->Write(0, convertToISO88591($inability->parentesco_s2));

                // %
                $pdf->SetXY(121.5, 144.3);
                $pdf->Write(0, $inability->porcentaje_s2);

                // calidad
                if ($inability->nombres_s2 !== null) {
                    $pdf->SetXY(134, 144.3);
                    $pdf->Write(0, 'Gratuito');
                }

                // tipo identidad
                $pdf->SetXY(152, 144.3);
                $pdf->Write(0, convertToISO88591($inability->tipo_identidad_s2));

                // no identificacion
                $pdf->SetXY(179.5, 144.3);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion_s2));

                // ------------------ Referido 3
                // nombres y apellidos
                $pdf->SetXY(48, 147.3);
                $pdf->Write(0, convertToISO88591($inability->nombres_s3 . ' ' . $inability->apellidos_s3));

                // parentesco
                $pdf->SetXY(100, 147.3);
                $pdf->Write(0, convertToISO88591($inability->parentesco_s3));

                // calidad
                if ($inability->nombres_s3 !== null) {
                    $pdf->SetXY(134, 147.3);
                    $pdf->Write(0, 'Gratuito');
                }

                // tipo identidad
                $pdf->SetXY(152, 147.3);
                $pdf->Write(0, convertToISO88591($inability->tipo_identidad_s3));

                // no identificacion
                $pdf->SetXY(179.5, 147.3);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion_s3));

                // ------------------ Declaraciòn de asegurabilidad

                // cancer
                if ($inability->cancer === 'si') {
                    $pdf->SetXY(46.5, 166);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(59.8, 166);
                    $pdf->Write(0, 'X');
                }

                // corazon
                if ($inability->corazon === 'si') {
                    $pdf->SetXY(46.5, 170);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(59.8, 170);
                    $pdf->Write(0, 'X');
                }

                // diabetes
                if ($inability->diabetes === 'si') {
                    $pdf->SetXY(46.5, 173.5);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(59.8, 173.5);
                    $pdf->Write(0, 'X');
                }

                // enfermedades hepaticas
                if ($inability->enf_hepaticas === 'si') {
                    $pdf->SetXY(114.5, 166);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(125, 166);
                    $pdf->Write(0, 'X');
                }
                // enfermedades neurológicas
                if ($inability->enf_neurologicas === 'si') {
                    $pdf->SetXY(114.5, 170);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(125, 170);
                    $pdf->Write(0, 'X');
                }

                // pulmones
                if ($inability->pulmones === 'si') {
                    $pdf->SetXY(114.5, 173.5);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(125, 173.5);
                    $pdf->Write(0, 'X');
                }

                // presion arterial
                if ($inability->presion_arterial === 'si') {
                    $pdf->SetXY(179, 166);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(188.5, 166);
                    $pdf->Write(0, 'X');
                }

                // riñones
                if ($inability->rinones === 'si') {
                    $pdf->SetXY(179, 170);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(188.5, 170);
                    $pdf->Write(0, 'X');
                }

                // infecciones vih
                if ($inability->infeccion_vih === 'si') {
                    $pdf->SetXY(179, 173.5);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(188.5, 173.5);
                    $pdf->Write(0, 'X');
                }

                // perdida funcional anatomica
                if ($inability->perdida_funcional_anatomica === 'si') {
                    $pdf->SetXY(179, 177.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(188.5, 177.2);
                    $pdf->Write(0, 'X');
                }

                // accidentes laborales
                if ($inability->accidentes_labores_ocupacion === 'si') {
                    $pdf->SetXY(179, 180.7);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(188.5, 180.7);
                    $pdf->Write(0, 'X');
                }

                // hospitalizacion intervencion quirurgica
                if ($inability->hospitalizacion_intervencion_quirurgica === 'si') {
                    $pdf->SetXY(179, 184.5);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(188.5, 184.5);
                    $pdf->Write(0, 'X');
                }

                // enfermedad diferente
                if ($inability->enfermedad_diferente === 'si') {
                    $pdf->SetXY(179, 188.2);
                    $pdf->Write(0, 'X');
                } else {
                    $pdf->SetXY(188.5, 188.2);
                    $pdf->Write(0, 'X');
                }

                // descripcion enfermedades
                if ($inability->descripcion_de_enfermedades !== null) {
                    $pdf->SetXY(84, 195);
                    $pdf->Write(0, convertToISO88591($inability->descripcion_de_enfermedades));
                }

                // fecha
                $pdf->SetXY(32, 269);
                $pdf->Write(0, convertToISO88591($inability->fecha_diligenciamiento));

                // entidad pagadora
                $pdf->SetFont('Arial', '', 6);
                $pdf->SetXY(146, 269.5);
                $pdf->Write(0, convertToISO88591($inability->entidad_pagadora_sucursal));

                // valor a descontar
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(65, 274.8);
                $pdf->Write(0, convertToISO88591($inability->prima_pago_prima_seguro));

                // nombres
                $pdf->SetFont('Arial', '', 6);
                $pdf->SetXY(35, 287);
                $pdf->Write(0, convertToISO88591($inability->nombres_completos . ' ' . $inability->primer_apellido . ' ' . $inability->segundo_apellido));

                // cedula
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(130, 287);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // ciudad expedicion
                $pdf->SetXY(170, 287);
                $pdf->Write(0, convertToISO88591($inability->ciudad_expedicion));

                // ocupacion
                $pdf->SetXY(39, 291);
                $pdf->Write(0, convertToISO88591($inability->ocupacion_asegurado));

                // documento identidad
                $pdf->SetXY(100, 328);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // telefono
                $pdf->SetXY(140, 328);
                $pdf->Write(0, convertToISO88591($inability->celular));

                // primer apellido
                $pdf->SetXY(30, 336);
                $pdf->Write(0, convertToISO88591($inability->primer_apellido));

                // segundo apellido
                $pdf->SetXY(74, 336);
                $pdf->Write(0, convertToISO88591($inability->segundo_apellido));

                // nombres completos
                $pdf->SetXY(117, 336);
                $pdf->Write(0, convertToISO88591($inability->nombres_completos));
            }

            if ($pageNo == 2) {
                // n documento
                $pdf->SetXY(85, 194.5);
                $pdf->Write(0, convertToISO88591($inability->no_identificacion));

                // lugar expedicion
                $pdf->SetXY(114, 194.5);
                $pdf->Write(0, convertToISO88591($inability->ciudad_expedicion));
            }
        }

        $numeroDocumento = $inability->no_identificacion;
        $fechaActual = date('Y-m-d');
        $nombreArchivo = 'documento_' . $numeroDocumento . '_' . $fechaActual . '.pdf';


        // Ruta donde se va a almacenar el PDF
        $rutaCarpeta = storage_path('app/public/documentos_positiva');
        if (!file_exists($rutaCarpeta)) {
            // Crea la carpeta si no existe
            if (!mkdir($rutaCarpeta, 0755, true)) {
                die('Error al crear la carpeta: ' . $rutaCarpeta);
            }
        }

        $rutaArchivo = $rutaCarpeta . '/' . $nombreArchivo;

        $pdf->Output('F', $rutaArchivo); // 'F' indica que se guarda en un archivo

        $inability->path_aseguradora = 'documentos_positiva/' . $nombreArchivo;
        $inability->save();

        $pdf->Output('I', $nombreArchivo); // 'I' indica que se visualiza en el navegador
    }
}
