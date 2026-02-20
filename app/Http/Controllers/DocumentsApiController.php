<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use App\Models\DocumentSigned;
use App\Models\ViafirmaCode;
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
                    // Verificar si es libranza (normal o fondo) o débito automático
                    if (strpos($documentPath, 'documentos_libranza/') !== false || strpos($documentPath, 'documentos_fondo/') !== false) {
                        $templateCode = "Afiliacion3DescuentoporNomina";
                    } else {
                        $templateCode = "Afiliacion3DebitoAutomatico";
                    }
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

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \UnexpectedValueException('Respuesta inválida de Viafirma: ' . json_last_error_msg());
            }

            $responseData = $this->utf8ize($responseData);

            if (empty($responseData['messages']) || !is_array($responseData['messages'])) {
                throw new \UnexpectedValueException('Respuesta de Viafirma sin mensajes válidos.');
            }

            $codes = [];
            foreach ($responseData['messages'] as $message) {
                $messageCode = $message['code'] ?? null;
                $templateCode = $message['templateCode'] ?? null;

                $codes[] = [
                    'messagesCode' => $messageCode,
                    'templateCode' => $templateCode,
                ];

                if ($messageCode) {
                    ViafirmaCode::updateOrCreate(
                        [
                            'inability_id' => $inability->id,
                            'message_code' => $messageCode,
                        ],
                        [
                            'template_code' => $templateCode,
                        ]
                    );
                }
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

            $inability = Inability::findOrFail($validated['id']);

            $persistedDocuments = DocumentSigned::where('inability_id', $inability->id)
                ->orderBy('created_at')
                ->get();

            if ($persistedDocuments->isNotEmpty()) {
                return response()->json([
                    'documents' => $this->utf8ize($persistedDocuments->map(fn ($doc) => $this->formatSignedDocument($doc))->toArray()),
                    'firstDocument' => $this->utf8ize($this->formatSignedDocument($persistedDocuments->first())),
                    'fromCache' => true,
                    'message' => 'Documentos firmados recuperados correctamente.'
                ]);
            }

            $codes = ViafirmaCode::where('inability_id', $inability->id)
                ->orderBy('created_at')
                ->get()
                ->map(fn ($code) => [
                    'messagesCode' => $code->message_code,
                    'templateCode' => $code->template_code,
                ])
                ->filter(fn ($code) => isset($code['messagesCode']))
                ->values();

            if ($codes->isEmpty()) {
                // Mantener compatibilidad con la lÃ³gica actual basada en sesiÃ³n.
                $codes = collect(session('via_firma_codes', []))
                ->filter(fn ($code) => isset($code['messagesCode']))
                ->values();
            }

            if ($codes->isEmpty()) {
                return response()->json([
                    'documents' => [],
                    'firstDocument' => null,
                    'fromCache' => false,
                    'message' => 'Todavia no se registran documentos firmados para esta afiliacion.'
                ]);
            }

            $user = Auth::user();
            $baseUrl = $user->ambiente == 1
                ? 'https://documents.viafirma.com/documents/api/v3'
                : 'https://sandbox.viafirma.com/documents/api/v3';

            $client = new Client();

            foreach ($codes as $code) {
                $messageCode = $code['messagesCode'];

                $response = $client->get($baseUrl . '/documents/download/signed/' . $messageCode, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Basic ' . env('VIAFIRMA_API_KEY'),
                    ],
                ]);

                $decodedResponse = json_decode($response->getBody()->getContents(), true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \UnexpectedValueException('Respuesta inválida al descargar documento firmado: ' . json_last_error_msg());
                }

                $decodedResponse = $this->utf8ize($decodedResponse);

                $fileName = $decodedResponse['fileName'] ?? ('documento_' . $messageCode . '.pdf');
                $link = $decodedResponse['link'] ?? null;
                $signedID = $decodedResponse['signedID'] ?? $messageCode;
                $expires = isset($decodedResponse['expires'])
                    ? Carbon::createFromTimestampMs($decodedResponse['expires'])
                    : null;

                if ($link) {
                    $fileContent = @file_get_contents($link);
                    if ($fileContent !== false) {
                        // Determinar la carpeta correcta según el templateCode
                        $templateCode = $code['templateCode'] ?? '';
                        $folder = 'documents_signed/';
                        
                        if ($templateCode === "Afiliacion1estaSSeguro") {
                            $folder = 'documentos_estasseguro/';
                        } elseif ($templateCode === "Afiliacion2Positiva") {
                            $folder = 'documentos_positiva/';
                        } elseif ($templateCode === "Afiliacion2SegConfianza") {
                            $folder = 'documentos_confianza/';
                        } elseif ($templateCode === "Afiliacion3DescuentoporNomina") {
                            // Verificar si es fondo o libranza normal
                            if (!is_null($inability->fondo_entity_id) && $inability->fondo_entity_id > 0) {
                                $folder = 'documentos_fondo/';
                            } else {
                                $folder = 'documentos_libranza/';
                            }
                        } elseif ($templateCode === "Afiliacion3DebitoAutomatico") {
                            $folder = 'documentos_debito/';
                        }

                        $storagePath = $folder . $fileName;
                        Storage::disk('public')->put($storagePath, $fileContent);

                        DocumentSigned::updateOrCreate(
                            ['signed_id' => $signedID],
                            [
                                'file_name' => $fileName,
                                'expires' => $expires ? $expires->toDateTimeString() : null,
                                'inability_id' => $inability->id,
                                'document_path' => $storagePath,
                            ]
                        );

                        Log::info('Documento firmado almacenado o actualizado.', [
                            'file_name' => $fileName,
                            'signed_id' => $signedID,
                            'inability_id' => $inability->id,
                            'document_path' => $storagePath,
                            'templateCode' => $templateCode,
                            'folder' => $folder,
                        ]);
                    }
                }
            }

            $freshDocuments = DocumentSigned::where('inability_id', $inability->id)
                ->orderBy('created_at')
                ->get();

            return response()->json([
                'documents' => $this->utf8ize($freshDocuments->map(fn ($doc) => $this->formatSignedDocument($doc))->toArray()),
                'firstDocument' => $freshDocuments->first()
                    ? $this->utf8ize($this->formatSignedDocument($freshDocuments->first()))
                    : null,
                'fromCache' => false,
                'message' => $freshDocuments->isNotEmpty()
                    ? 'Documentos firmados descargados correctamente.'
                    : 'No hay documentos firmados disponibles en Viafirma todavia.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en la descarga de documentos firmados: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrio un error inesperado.'], 500);
        }
    }

    private function formatSignedDocument(DocumentSigned $document): array
    {
        return [
            'id' => $document->id,
            'file_name' => $document->file_name,
            'signed_id' => $document->signed_id,
            'document_path' => $document->document_path,
            'expires' => optional($document->expires)->toDateTimeString(),
            'created_at' => optional($document->created_at)->toDateTimeString(),
            'updated_at' => optional($document->updated_at)->toDateTimeString(),
        ];
    }

    protected function utf8ize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($data)) {
            if (!mb_check_encoding($data, 'UTF-8')) {
                $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8, ISO-8859-1, ISO-8859-15, Windows-1252');
            }

            if (!mb_check_encoding($data, 'UTF-8')) {
                $data = iconv('UTF-8', 'UTF-8//IGNORE', $data);
            }

            $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $data);
        }

        return $data;
    }
}
