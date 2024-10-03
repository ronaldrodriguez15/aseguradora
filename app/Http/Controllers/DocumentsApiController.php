<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DocumentsApiController extends Controller
{
    public function sendToViaFirma(Request $request)
    {
        try {
            // Validar el ID de la incapacidad
            $validated = $request->validate([
                'id' => 'required|exists:inabilities,id',
            ]);

            // Encontrar la incapacidad por ID
            $inability = Inability::find($validated['id']);

            // Obtener los documentos
            $documents = [
                $inability->path_estasseguro,
                $inability->path_aseguradora,
                $inability->path_pago
            ];

            // Verificar que existen los documentos
            if (count($documents) !== 3) {
                return response()->json(['error' => 'Faltan documentos'], 400);
            }

            // Preparar los datos para la solicitud a la API de Via Firma
            $data = [
                "groupCode" => "svgseguros",
                "title" => "SVG_PDFs",
                "description" => "Firma documentos",
                "recipients" => [
                    [
                        "key" => "signer" . $validated['id'],
                        "mail" => $inability->email_corporativo,
                        "name" => $inability->name, // Asegúrate de que el nombre esté en el modelo
                        "id" => $validated['id']
                    ]
                ],
                "customization" => [
                    "requestMailSubject" => "Contrato listo para firmar",
                    "requestMailBody" => "Hola " . $inability->name . ". <br /><br />Ya puedes revisar y firmar el contrato.",
                    "requestSmsBody" => "En el siguiente link puedes revisar y firmar el contrato"
                ],
                //revisar
                "messages" => array_map(function ($documentPath) {
                    // Leer el contenido del documento y codificarlo en base64
                    $pdfContent = base64_encode(file_get_contents($documentPath)); // Asegúrate de que las rutas sean correctas

                    return [
                        "document" => [
                            "templateCode" => "your_template_code_here", // Cambia esto por el código del template correspondiente
                            "templateReference" => $pdfContent,
                            "templateType" => "base64"
                        ]
                    ];
                }, $documents)
            ];

            // Responder con los datos que se enviarán a Via Firma
            return response()->json(['data_to_send' => $data]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar y manejar excepciones de validación
            Log::error('Validation error: ' . $e->getMessage());

            return response()->json(['error' => 'Validation failed', 'messages' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Capturar otros tipos de excepciones
            Log::error('Error en sendToViaFirma: ' . $e->getMessage());

            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
