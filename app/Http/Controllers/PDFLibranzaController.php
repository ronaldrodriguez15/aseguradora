<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inability;
use setasign\Fpdi\Fpdi;

class PDFLibranzaController extends Controller
{
    public function generarPDF($id)
    {
        // Obtener los datos de la tabla inabilities
        $inability = Inability::find($id);

        if (!$inability) {
            abort(404, 'Incapacidad no encontrada.');
        }

        // Ruta del archivo PDF a usar como plantilla
        $pdfFilePath = public_path('documents/libranza.pdf');

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
                $pdf->SetXY(108, 41.5);
                $pdf->Write(0, '(SHG)');

                //Nemomico
                $pdf->SetXY(150, 42);
                $pdf->Write(0, 'SLGR');

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

        $pdf->Output('I', 'LibranzaGenerada.pdf');
    }
}
