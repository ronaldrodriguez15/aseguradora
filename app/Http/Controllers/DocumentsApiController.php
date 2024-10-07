<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            $messages = array_map(function ($documentPath) use ($inability) {
                $fullPath = Storage::disk('public')->path($documentPath);
                if (!file_exists($fullPath)) {
                    throw new \Exception("El archivo no existe: " . $fullPath);
                }
                $pdfContent = base64_encode(file_get_contents($fullPath));

                // Obtener el nombre del archivo
                $fileName = basename($documentPath);

                $templateCode = '';
                if ($documentPath === $inability->path_estasseguro) {
                    $templateCode = "Afiliacion1estaSSeguro";
                } elseif ($documentPath === $inability->path_aseguradora) {
                    $templateCode = "Afiliacion2Positiva";
                } elseif ($documentPath === $inability->path_pago) {
                    $templateCode = "Afiliacion3DescuentoporNomina";
                }
                return [
                    "document" => [
                        "templateCode" => $templateCode,
                        "templateReference" => $pdfContent,
                        "templateType" => "base64",
                        "fileName" => $fileName,
                    ]
                ];
            }, $documents);

            // Construir la solicitud
            $data = [
                "groupCode" => "svgseguros",
                "title" => "SVG_PDFs",
                "description" => "Firma documentos",
                "recipients" => [
                    [
                        "key" => "signer" . $validated['id'],
                        "mail" => $inability->email_corporativo,
                        "name" => $inability->nombres_completos . $inability->primer_apellido . ' ' . $inability->segundo_apellido,
                        "id" => $validated['id']
                    ]
                ],
                "customization" => [
                    "requestMailSubject" => "Contrato listo para firmar",
                    "requestMailBody" => "Hola " . $inability->nombres_completos . ". <br /><br />Ya puedes revisar y firmar el contrato.",
                    "requestSmsBody" => "En el siguiente link puedes revisar y firmar el contrato"
                ],
                "messages" => $messages
            ];

            $credentials = base64_encode('svgseguros:Seguro5060*..');

            // Enviar los datos a ViaFirma usando Guzzle
            $client = new Client();
            $response = $client->post('https://sandbox.viafirma.com/documents/api/v3/set', [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic c3Znc2VndXJvczpTZWd1cm81MDYwKi4u',
                ],
            ]);

            return response()->json(['data' => json_decode($response->getBody()->getContents())]);
        } catch (\Exception $e) {
            Log::error('Error en sendToViaFirma: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
