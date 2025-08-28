<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use App\Models\DocumentSigned;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DocumentsApiController extends Controller
{
    public function sendToViaFirma(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:inabilities,id',
            ]);

            $inability = Inability::find($validated['id']);

            Log::info('Valor de path_aseguradora: ' . $inability->path_aseguradora);

            $documents = [
                $inability->path_estasseguro,
                $inability->path_aseguradora,
                $inability->path_pago
            ];

            if (count($documents) !== 3) {
                return response()->json(['error' => 'Faltan documentos'], 400);
            }

            $messages = array_map(function ($documentPath) use ($inability) {
                $fullPath = Storage::disk('public')->path($documentPath);
                if (!file_exists($fullPath)) {
                    throw new \Exception("El archivo no existe: " . $fullPath);
                }
                $pdfContent = base64_encode(file_get_contents($fullPath));
                $fileName = basename($documentPath);

                $templateCode = '';
                if ($documentPath === $inability->path_estasseguro) {
                    $templateCode = "Afiliacion1estaSSeguro";
                } elseif ($documentPath === $inability->path_aseguradora) {
                    $templateCode = strpos($documentPath, 'documentos_positiva/') !== false
                        ? "Afiliacion2Positiva"
                        : "Afiliacion2SegConfianza";
                } elseif ($documentPath === $inability->path_pago) {
                    $templateCode = strpos($documentPath, 'documentos_libranza/') !== false
                        ? "Afiliacion3DescuentoporNomina"
                        : "Afiliacion3DebitoAutomatico";
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

            $user = Auth::user();
            $baseUrl = $user->ambiente == 1
                ? 'https://documents.viafirma.com/documents/api/v3'
                : 'https://sandbox.viafirma.com/documents/api/v3';

            $data = [
                "groupCode" => "svgseguros",
                "title" => "SVG_PDFs",
                "description" => "Firma documentos",
                "recipients" => [
                    [
                        "key" => "signer" . $validated['id'],
                        "mail" => $inability->email_corporativo,
                        "name" => $inability->nombres_completos . ' ' . $inability->primer_apellido . ' ' . $inability->segundo_apellido,
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

            $client = new Client();
            $response = $client->post($baseUrl . '/set', [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('VIAFIRMA_API_KEY'),
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents());

            $codes = [];
            foreach ($responseData->messages as $message) {
                $codes[] = [
                    'messagesCode' => $message->code,
                    'templateCode' => $message->templateCode,
                ];
            }

            session(['via_firma_codes' => $codes]);

            return response()->json([
                'success' => true,
                'message' => 'El correo fue enviado exitosamente.',
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            Log::error('Error en sendToViaFirma: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error inesperado'], 500);
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
            $codes = session('via_firma_codes', []);

            if (empty($codes)) {
                return response()->json(['error' => 'No hay códigos disponibles para descargar.'], 400);
            }

            $user = Auth::user();
            $baseUrl = $user->ambiente == 1
                ? 'https://documents.viafirma.com/documents/api/v3'
                : 'https://sandbox.viafirma.com/documents/api/v3';

            $client = new Client();
            $allResponses = [];

            foreach ($codes as $code) {
                if (isset($code['messagesCode'])) {
                    $response = $client->get($baseUrl . '/documents/download/signed/' . $code['messagesCode'], [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Basic ' . env('VIAFIRMA_API_KEY'),
                        ],
                    ]);

                    $decodedResponse = json_decode($response->getBody()->getContents(), true);
                    $allResponses[] = $decodedResponse;

                    $fileName = $decodedResponse['fileName'];
                    $link = $decodedResponse['link'];
                    $signedID = $decodedResponse['signedID'];
                    $expires = Carbon::createFromTimestampMs($decodedResponse['expires']);

                    $fileContent = file_get_contents($link);
                    $storagePath = 'documents_signed/' . $fileName;
                    Storage::disk('public')->put($storagePath, $fileContent);

                    $documentSigned = new DocumentSigned();
                    $documentSigned->file_name = $fileName;
                    $documentSigned->signed_id = $signedID;
                    $documentSigned->expires = $expires;
                    $documentSigned->inability_id = $validated['id'];
                    $documentSigned->document_path = $storagePath;
                    $documentSigned->save();

                    Log::info('Documento firmado almacenado en la BD:', [
                        'file_name' => $fileName,
                        'signed_id' => $signedID,
                        'expires' => $expires->toDateTimeString(),
                        'inability_id' => $validated['id'],
                        'document_path' => $storagePath
                    ]);
                } else {
                    Log::error('messagesCode no encontrado en el objeto: ', (array)$code);
                }
            }

            $firstSignedDocument = DocumentSigned::where('inability_id', $validated['id'])->first();

            return response()->json([
                'allResponses' => $allResponses,
                'firstDocument' => $firstSignedDocument
            ]);

        } catch (\Exception $e) {
            Log::error('Error en la descarga de documentos firmados: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
        }
    }
}
