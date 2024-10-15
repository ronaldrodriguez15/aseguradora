<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentSigned;
use Illuminate\Support\Carbon;

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

            // Enviar los datos a ViaFirma usando Guzzle
            $client = new Client();
            $response = $client->post(env('URL_VIA_FIRMA') . '/api/v3/set', [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic c3Znc2VndXJvczpTZWd1cm81MDYwKi4u',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents());

            // Guardar los codes y templateCodes en la sesión
            $codes = [];
            foreach ($responseData->messages as $message) {
                $codes[] = [
                    'messagesCode' => $message->code,
                    'templateCode' => $message->templateCode,
                ];
            }

            session(['via_firma_codes' => $codes]); // Almacena en la sesión

            return response()->json(['data' => $responseData]);
        } catch (\Exception $e) {
            Log::error('Error en sendToViaFirma: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function downloadFile(Request $request)
    {
        set_time_limit(180);

        try {
            $validated = $request->validate([
                'id' => 'required|exists:inabilities,id',
            ]);

            $inability = Inability::find($validated['id']);

            // Recuperar los codes y templateCodes de la sesión
            $codes = session('via_firma_codes', []);

            if (empty($codes)) {
                return response()->json(['error' => 'No hay códigos disponibles para descargar.'], 400);
            }

            $client = new Client();
            $allResponses = [];

            foreach ($codes as $code) {
                if (isset($code['messagesCode'])) {
                    $response = $client->get(env('URL_VIA_FIRMA') . '/api/v3/documents/download/signed/' . $code['messagesCode'], [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Basic c3Znc2VndXJvczpTZWd1cm81MDYwKi4u',
                        ],
                    ]);

                    // Decodificar la respuesta y agregarla al array
                    $decodedResponse = json_decode($response->getBody()->getContents(), true);
                    $allResponses[] = $decodedResponse; // Guardar cada respuesta en el array

                    // Extraer los campos necesarios del objeto de respuesta
                    $fileName = $decodedResponse['fileName'];
                    $link = $decodedResponse['link'];
                    $signedID = $decodedResponse['signedID'];
                    $expires = Carbon::createFromTimestampMs($decodedResponse['expires']);

                    // Descargar el archivo desde el link de la API
                    $fileContent = file_get_contents($link);

                    // Guardar el archivo en la carpeta 'documents_signed' dentro de storage
                    $storagePath = 'documents_signed/' . $fileName;
                    Storage::disk('public')->put($storagePath, $fileContent);

                    // Almacenar el documento en la base de datos
                    $documentSigned = new DocumentSigned();
                    $documentSigned->file_name = $fileName;
                    $documentSigned->signed_id = $signedID;
                    $documentSigned->expires = $expires;
                    $documentSigned->inability_id = $validated['id']; // Corregido el campo 'inability_id'
                    $documentSigned->document_path = $storagePath; // Guardamos la ruta de almacenamiento
                    $documentSigned->save();
                } else {
                    Log::error('messagesCode no encontrado en el objeto: ', (array)$code);
                }
            }

            // Obtiene el primer documento firmado guardado para mostrar en la vista
            $firstSignedDocument = DocumentSigned::where('inability_id', $validated['id'])->first();

            return response()->json([
                'allResponses' => $allResponses,
                'firstDocument' => $firstSignedDocument // Devuelve el primer documento firmado
            ]);
        } catch (\Exception $e) {
            Log::error('Error en la descarga de documentos firmados: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
        }
    }
}
