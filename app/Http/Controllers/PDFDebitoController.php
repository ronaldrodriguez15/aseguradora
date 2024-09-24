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
                $pdf->SetXY(35, 25);
                $pdf->Write(0, convertToISO88591($inability->fecha_diligenciamiento));
            }
        }

        // Salida del PDF
        $pdf->Output('I', 'DebitoGenerado.pdf');
    }
}
