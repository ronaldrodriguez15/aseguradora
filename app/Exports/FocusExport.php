<?php

namespace App\Exports;

use App\Models\Inability;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FocusExport implements FromCollection
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data; // Retorna la colección que se pasó en el constructor
    }

    // Método para guardar los datos en la plantilla
    public function saveTemplate($filePath)
    {
        // Cargar la plantilla existente
        $spreadsheet = IOFactory::load(public_path('plano_focus/plantilla.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        // Suponiendo que los datos comienzan en la fila 2 (la fila 1 puede ser el encabezado)
        $row = 3; // Fila donde comenzaremos a escribir los datos

        // Agregar datos a la plantilla
        foreach ($this->data as $inability) {
            $sheet->setCellValue('A' . $row, $inability->created_at->format('Y-m-d'));
            $sheet->setCellValue('B' . $row, $inability->no_solicitud);
            $sheet->setCellValue('C' . $row, $inability->insurer_name);
            $sheet->setCellValue('D' . $row, $inability->nombres_completos);
            $sheet->setCellValue('E' . $row, $inability->no_identificacion);
            $sheet->setCellValue('F' . $row, $inability->val_total_desc_mensual);
            $sheet->setCellValue('G' . $row, $inability->nombre_asesor);
            $sheet->setCellValue('H' . $row, $inability->estado_firmado);
            $row++;
        }

        // Guardar el archivo en la ruta especificada
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
